<?php
require "../../config/db_connect.php";
include "../auto_login.php";
include "../settings.php";

$user = autoLogin($conn);

if ($user['role'] != 'Admin'){
    echo json_encode(['success' => 0, 'message' => 'Không có quyền truy cập']);
    exit;
}

header('Content-Type: application/json');

$uploadDir = 'uploads/images/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $fileName = $user['user_id'] . '-' . uniqid("",true) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        echo json_encode([
            'success' => 1,
            'file' => [
                'url' => $Domain . 'server/' . $filePath
            ]
        ]);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Lỗi khi upload file']);
    }
} else {
    echo json_encode(['success' => 0, 'message' => 'Không có file được gửi']);
}

?>