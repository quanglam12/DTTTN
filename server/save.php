<?php
require "../../config/db_connect.php";
include "../auto_login.php";
include "../settings.php";

$user = autoLogin($conn);

if ($user['role'] != 'Admin' && $user['role'] != 'Manager' && $user['role'] != 'Author') {
    exit("Không có quyền truy cập");
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
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'd' => 'đ|Đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'i' => 'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ'
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
$uploadDir = 'uploads/'; // Thư mục chứa cả hình ảnh và file đính kèm
$imageDir = 'images/';   // Thư mục đích cho hình ảnh
$fileDir = 'files/';     // Thư mục đích cho file đính kèm

// Tạo thư mục đích nếu chưa tồn tại
if (!is_dir($imageDir)) {
    mkdir($imageDir, 0755, true);
}
if (!is_dir($fileDir)) {
    mkdir($fileDir, 0755, true);
}
// Trích xuất danh sách file hình ảnh và file đính kèm đang sử dụng
$usedImages = [];
$usedFiles = [];
foreach ($content['blocks'] as $block) {
    // Xử lý khối image
    if ($block['type'] === 'image' && isset($block['data']['file']['url'])) {
        $url = $block['data']['file']['url'];
        $fileName = basename(parse_url($url, PHP_URL_PATH));
        $usedImages[] = $fileName;
    }
    // Xử lý khối attaches
    if ($block['type'] === 'attaches' && isset($block['data']['file']['url'])) {
        $url = $block['data']['file']['url'];
        $fileName = basename(parse_url($url, PHP_URL_PATH));
        $usedFiles[] = $fileName;
    }
}

// Lấy danh sách file trong thư mục uploads (bao gồm cả uploads/files/)
$existingFiles = array_diff(scandir($uploadDir . 'images/'), ['.', '..']);
$existingAttachFiles = array_diff(scandir($uploadDir . 'files/'), ['.', '..']);
$targetNumber = $user['user_id'] ?? 'default'; // Số cần lọc (user_id)

// Lọc các file thuộc về user_id
$filteredFiles = array_filter($existingFiles, function ($file) use ($targetNumber) {
    if (preg_match('/^(\d+)-/', $file, $matches)) {
        return $matches[1] === $targetNumber;
    }
    return false;
});
$filteredAttachFiles = array_filter($existingAttachFiles, function ($file) use ($targetNumber) {
    if (preg_match('/^(\d+)-/', $file, $matches)) {
        return $matches[1] === $targetNumber;
    }
    return false;
});

// Di chuyển file hình ảnh đang sử dụng sang thư mục images
foreach ($usedImages as $fileName) {
    $sourcePath = $uploadDir . 'images/' . $fileName;
    $destPath = $imageDir . $fileName;

    if (file_exists($sourcePath)) {
        rename($sourcePath, $destPath); // Di chuyển file
    }
}

// Di chuyển file đính kèm đang sử dụng sang thư mục files
foreach ($usedFiles as $fileName) {
    $sourcePath = $uploadDir . 'files/' . $fileName;
    $destPath = $fileDir . $fileName;

    if (file_exists($sourcePath)) {
        rename($sourcePath, $destPath); // Di chuyển file
    }
}

// Xóa các file không còn sử dụng trong uploads
foreach ($filteredFiles as $file) {
    if (!in_array($file, $usedImages)) {
        $filePath = $uploadDir . 'images/' . $file;
        if (file_exists($filePath)) {
            unlink($filePath); // Xóa file
        }
    }
}

// Xóa các file không còn sử dụng trong uploads/files
foreach ($filteredAttachFiles as $file) {
    if (!in_array($file, $usedFiles)) {
        $filePath = $uploadDir . 'files/' . $file;
        if (file_exists($filePath)) {
            unlink($filePath); // Xóa file
        }
    }
}

// Cập nhật URL trong dữ liệu
$urlImg = null;
foreach ($content['blocks'] as &$block) {
    // Cập nhật URL cho hình ảnh
    if ($block['type'] === 'image' && isset($block['data']['file']['url'])) {
        $fileName = basename($block['data']['file']['url']);
        $block['data']['file']['url'] = $Domain . 'server/' . $imageDir . $fileName;
        if ($urlImg == null) {
            $urlImg = $block['data']['file']['url'];
        }
    }
    // Cập nhật URL cho file đính kèm
    if ($block['type'] === 'attaches' && isset($block['data']['file']['url'])) {
        $fileName = basename($block['data']['file']['url']);
        $block['data']['file']['url'] = $Domain . 'server/' . $fileDir . $fileName;
    }
}
if ($urlImg == null) {
    $urlImg = $Domain . 'server/' . $savedDir . 'default.jpg';
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

if ($user['role'] == 'Author') {
    if ($user['unit_id'] <= 8) {
        $type = 1;
    }
}
$unit_id = (int) $user['unit_id'];

$sql = "INSERT INTO posts (title, slug, image, type, status, content, author_id, unit_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssissii", $title, $slug, $urlImg, $type, $status, $content, $author, $unit_id);

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