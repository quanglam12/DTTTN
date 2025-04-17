<?php
require "../../config/db_connect.php";
include "../auto_login.php";

$user = autoLogin($conn);

if ($user['role'] != 'Admin'){
    echo json_encode(['success' => 0, 'message' => 'Không có quyền truy cập']);
    exit;
}

header('Content-Type: application/json');

$uploadDir = 'uploads/files/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['file'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $userId = $user['user_id'] ?? 'default';
    $filename = $userId . '-' . uniqid("", true) . '.' . $extension;
    $filepath = $uploadDir . $filename;
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        echo json_encode([
            'success' => 1,
            'file' => [
                'url' => 'http://localhost/server/' . $filepath,
                'name' => $file['name'],
                'size' => $file['size'],
                'extension' => $extension 
            ]
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => 'Tải lên thất bại.'
        ]);
    }
} else {
    $message = 'Không có file hoặc có lỗi khi tải lên.';
    if (isset($_FILES['file']['error'])) {
        $message .= ' Mã lỗi: ' . $_FILES['file']['error'];
    }
    echo json_encode([
        'success' => 0,
        'message' => $message
    ]);
}