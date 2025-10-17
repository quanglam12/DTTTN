<?php
require "../../config/db_connect.php";
include "../auto_login.php";
include "../settings.php";

$user = autoLogin($conn);

if ($user['role'] != 'Admin' && $user['role'] != 'Manager' && $user['role'] != 'Author') {
    echo json_encode(['success' => 0, 'message' => 'Không có quyền truy cập']);
    exit;
}
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$url = $data['url'] ?? '';

if ($url) {
    $uploadDir = 'uploads/images/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = $user['user_id'] . '-' . uniqid("", true) . "." . pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
    $filePath = $uploadDir . $fileName;

    $imageContent = file_get_contents($url);
    if ($imageContent && file_put_contents($filePath, $imageContent)) {
        echo json_encode([
            'success' => 1,
            'file' => [
                'url' => $Domain . 'server/' . $filePath
            ]
        ]);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Lỗi khi tải ảnh từ URL']);
    }
} else {
    echo json_encode(['success' => 0, 'message' => 'URL không hợp lệ']);
}
?>