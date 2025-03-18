<?php
require "../db_connect.php";
include "../auto_login.php";

$user = autoLogin($conn);

if ($user['role'] != 'Admin') {
    echo json_encode(['success' => 0, 'message' => 'Không có quyền truy cập']);
    exit;
}
header('Content-Type: application/json');

// Kiểm tra bảo mật
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Chỉ hỗ trợ POST');
}

// Hàm loại bỏ dấu và thay khoảng trắng bằng "-"
function slugify($str)
{
    $str = strtolower($str);

    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
    );
    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    $str = preg_replace('/\s+/', '-', $str);
    $str = preg_replace('/[^a-z0-9\-]/i', '', $str);
    // Không cần strtolower() lại ở đây vì đã xử lý ở đầu
    return $str;
}

// Nhận dữ liệu từ Editor.js
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['title']) || !isset($data['content']['blocks'])) {
    echo json_encode(['success' => 0, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$title = $data['title'];
$slug = slugify($data['title']);
$content = $data['content'];

// Thư mục nguồn và đích
$uploadDir = 'uploads/';
$savedDir = 'images/';

// Trích xuất danh sách file hình ảnh đang sử dụng
$usedImages = [];
foreach ($content['blocks'] as $block) {
    if ($block['type'] === 'image' && isset($block['data']['file']['url'])) {
        $url = $block['data']['file']['url'];
        $fileName = basename(parse_url($url, PHP_URL_PATH));
        $usedImages[] = $fileName;
    }
}

// Lấy danh sách file trong thư mục uploads
$existingFiles = array_diff(scandir($uploadDir), ['.', '..']);
$targetNumber = $user['user_id']; // Số cần lọc

$filteredFiles = array_filter($existingFiles, function ($file) use ($targetNumber) {
    if (preg_match('/^(\d+)-/', $file, $matches)) {
        return $matches[1] === $targetNumber;
    }
    return false;
});

// Di chuyển file đang sử dụng sang thư mục saved_images
foreach ($usedImages as $fileName) {
    $sourcePath = $uploadDir . $fileName;
    $destPath = $savedDir . $fileName;

    if (file_exists($sourcePath)) {
        rename($sourcePath, $destPath); // Di chuyển file
    }
}

// Xóa các file không còn sử dụng trong uploads
foreach ($filteredFiles as $file) {
    if (!in_array($file, $usedImages)) {
        $filePath = $uploadDir . $file;
        if (file_exists($filePath)) {
            unlink($filePath); // Xóa file
        }
    }
}

$urlImg = null;
// Cập nhật URL trong dữ liệu
foreach ($content['blocks'] as &$block) {
    if ($block['type'] === 'image' && isset($block['data']['file']['url'])) {
        $fileName = basename($block['data']['file']['url']);
        $block['data']['file']['url'] = 'http://localhost/server/' . $savedDir . $fileName;
        if ($urlImg == null) {
            $urlImg = $block['data']['file']['url'];
        }
    }
}
if ($urlImg == null) {
    $urlImg = 'http://localhost/server/' . $savedDir . 'default.jpg';
}
if (isset($data['status']) && $data['status'] == "Writing") {
    $status = "Writing";
} else {
    $status = "Pending";
}
$data['content'] = $content;
$content = json_encode($data['content'], JSON_UNESCAPED_UNICODE);
$author = (int) $user['user_id'];
$type = (int) $data['type'];

$sql = "INSERT INTO posts (title, slug, image, type, status, content, author_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssissi", $title, $slug, $urlImg, $type, $status, $content, $author);

$response = [];
if ($stmt->execute()) {
    $response = [
        'success' => 1,
        'message' => 'Lưu thành công'
    ];
} else {
    // Kiểm tra lỗi trùng slug (MySQL error code 1062)
    if ($stmt->errno === 1062) {
        $response = [
            'success' => 0,
            'message' => 'Tiêu đề bị trùng, vui lòng thay đổi'
        ];
    } else {
        $response = [
            'success' => 0,
            'message' => 'Xảy ra lỗi: ' // Có thể thêm chi tiết lỗi để debug
        ];
    }
}

// Đóng statement và kết nối
$stmt->close();
$conn->close();

// Trả về phản hồi JSON duy nhất
echo json_encode($response);
exit;