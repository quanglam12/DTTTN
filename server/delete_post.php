<?php
require "../../config/db_connect.php";
include "../auto_login.php";

// Kiểm tra quyền truy cập (chỉ Admin)
$user = autoLogin($conn);
if ($user['role'] != 'Admin') {
    echo json_encode(['success' => 0, 'message' => 'Không có quyền truy cập']);
    exit;
}

header('Content-Type: application/json');

// Kiểm tra bảo mật
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => 0, 'message' => 'Chỉ hỗ trợ GET']);
    exit;
}

// Lấy post_id từ URL (query parameter)
$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? null;
if (!$post_id) {
    echo json_encode(['success' => 0, 'message' => 'Không tìm thấy ID bài viết']);
    exit;
}

// Chuẩn bị và thực hiện truy vấn để lấy thông tin bài viết (bao gồm content để xóa file ảnh và file đính kèm)
$sql = "SELECT content, image FROM posts WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => 0, 'message' => 'Bài viết không tồn tại']);
    exit;
}

$post = $result->fetch_assoc();

// Giải mã content để lấy danh sách file ảnh và file đính kèm
$content = json_decode($post['content'], true);
$usedImages = [];
$usedFiles = [];
if ($content && isset($content['blocks'])) {
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
}

// Thêm hình ảnh chính (nếu có) vào danh sách cần xóa
if ($post['image']) {
    $mainImage = basename(parse_url($post['image'], PHP_URL_PATH));
    if (!in_array($mainImage, $usedImages)) {
        $usedImages[] = $mainImage;
    }
}

// Thư mục chứa ảnh đã lưu
$savedDirImages = 'images/';
// Thư mục chứa file đính kèm đã lưu
$savedDirFiles = 'files/';

// Xóa các file ảnh liên quan trong thư mục images
foreach ($usedImages as $fileName) {
    $filePath = $savedDirImages . $fileName;
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            // Xóa thành công (có thể ghi log nếu cần)
        } else {
            // Ghi log lỗi nếu xóa thất bại (tùy chọn)
        }
    }
}

// Xóa các file đính kèm liên quan trong thư mục files
foreach ($usedFiles as $fileName) {
    $filePath = $savedDirFiles . $fileName;
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            // Xóa thành công (có thể ghi log nếu cần)
        } else {
            // Ghi log lỗi nếu xóa thất bại (tùy chọn)
        }
    }
}

// Thực hiện xóa bài viết khỏi cơ sở dữ liệu
$sql_delete = "DELETE FROM posts WHERE post_id = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $post_id);

$response = [];
if ($stmt_delete->execute()) {
    $response = [
        'success' => 1,
        'message' => 'Xóa bài viết thành công'
    ];
} else {
    $response = [
        'success' => 0,
        'message' => 'Xảy ra lỗi khi xóa bài viết: ' . $conn->error
    ];
}

// Đóng các statement và kết nối
$stmt->close();
$stmt_delete->close();
$conn->close();

// Trả về phản hồi JSON
echo json_encode($response);
exit;