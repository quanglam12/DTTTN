<?php
require "../../config/db_connect.php";
include "../auto_login.php";
include "../settings.php";

// Kiểm tra quyền truy cập (chỉ Admin)
$user = autoLogin($conn);
if ($user['role'] != 'Admin' && $user['role'] != 'Manager' && $user['role'] != 'Author') {
    echo json_encode(['success' => 0, 'message' => 'Không có quyền truy cập']);
    exit;
}

header('Content-Type: application/json');

// Kiểm tra bảo mật
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => 0, 'message' => 'Chỉ hỗ trợ POST']);
    exit;
}

// Hàm slugify để tạo slug từ title
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
    return $str;
}

// Nhận dữ liệu từ request
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['post_id']) || !isset($data['title']) || !isset($data['content']['blocks']) || !isset($data['type'])) {
    echo json_encode(['success' => 0, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$post_id = $data['post_id'];
$title = $data['title'];
$slug = slugify($title); // Tạo slug mới từ title
$content = $data['content'];
$type = (int) $data['type'];
if ($user['role'] == 'Author') {
    if ($user['unit_id'] <= 8) {
        $type = 1;
    }
}

// Xử lý trạng thái bài viết
if (isset($data['status']) && $data['status'] === "Writing") {
    $status = "Writing";
} else {
    $status = "Pending";
}

// Thư mục nguồn và đích
$uploadDir = 'uploads/';
$savedDir = 'images/';

// Trích xuất danh sách file hình ảnh đang sử dụng trong content
$usedImages = [];
foreach ($content['blocks'] as $block) {
    if ($block['type'] === 'image' && isset($block['data']['file']['url'])) {
        $url = $block['data']['file']['url'];
        $fileName = basename(parse_url($url, PHP_URL_PATH));
        $usedImages[] = $fileName;
    }
}

// Lấy danh sách file trong thư mục uploads thuộc về user hiện tại
$existingFiles = array_diff(scandir($uploadDir), ['.', '..']);
$targetNumber = $user['user_id']; // Số cần lọc

$filteredFiles = array_filter($existingFiles, function ($file) use ($targetNumber) {
    if (preg_match('/^(\d+)-/', $file, $matches)) {
        return $matches[1] === $targetNumber;
    }
    return false;
});

// Di chuyển file đang sử dụng sang thư mục images
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

// Cập nhật URL trong dữ liệu content
$urlImg = null;
foreach ($content['blocks'] as &$block) {
    if ($block['type'] === 'image' && isset($block['data']['file']['url'])) {
        $fileName = basename($block['data']['file']['url']);
        $block['data']['file']['url'] = $Domain . 'server/' . $savedDir . $fileName;
        if ($urlImg === null) {
            $urlImg = $block['data']['file']['url'];
        }
    }
}
if ($urlImg === null) {
    $urlImg = $Domain . 'server/' . $savedDir . 'default.jpg';
}

// Chuẩn bị dữ liệu để cập nhật vào cơ sở dữ liệu
$content = json_encode($content, JSON_UNESCAPED_UNICODE);
$author = (int) $user['user_id'];

// Câu lệnh SQL để cập nhật bài viết
$sql = "UPDATE posts SET title = ?, slug = ?, image = ?, type = ?, content = ? WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssisi", $title, $slug, $urlImg, $type, $content, $post_id);

$response = [];
if ($stmt->execute()) {
    $response = [
        'success' => 1,
        'message' => 'Cập nhật bài viết thành công'
    ];
} else {
    // Kiểm tra lỗi (ví dụ: trùng slug, mã lỗi MySQL 1062)
    if ($stmt->errno === 1062) {
        $response = [
            'success' => 0,
            'message' => 'Tiêu đề bị trùng, vui lòng thay đổi'
        ];
    } else {
        $error_message = isset($conn) && $conn instanceof mysqli ? $conn->error : 'Không xác định';
        $response = [
            'success' => 0,
            'message' => 'Xảy ra lỗi: ' . $error_message
        ];
    }
}

// Đóng statement và kết nối
$stmt->close();
$conn->close();

// Trả về phản hồi JSON
echo json_encode($response);
exit;