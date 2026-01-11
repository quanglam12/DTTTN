<?php
require "../../config/db_connect.php";
include "../auto_login.php";
include "../settings.php";

$user = autoLogin($conn);

if ($user['role'] != 'Admin' && $user['role'] != 'Manager' && $user['role'] != 'Author'){
    echo json_encode(['success' => 0, 'message' => 'Không có quyền truy cập']);
    exit;
}

header('Content-Type: application/json');

$uploadDir = 'uploads/images/';

if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode([
            'success' => 0,
            'message' => 'Không thể tạo thư mục upload'
        ]);
        exit;
    }
}

if (!isset($_FILES['image'])) {
    echo json_encode([
        'success' => 0,
        'message' => 'Không có file được gửi'
    ]);
    exit;
}

$file = $_FILES['image'];

/* 🔴 BẮT LỖI PHP UPLOAD */
if ($file['error'] !== UPLOAD_ERR_OK) {
    $uploadErrors = [
        UPLOAD_ERR_INI_SIZE   => 'File vượt quá dung lượng cho phép trên server',
        UPLOAD_ERR_FORM_SIZE  => 'File vượt quá dung lượng form cho phép',
        UPLOAD_ERR_PARTIAL    => 'File chỉ upload được một phần',
        UPLOAD_ERR_NO_FILE    => 'Không có file được upload',
        UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm trên server',
        UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file lên ổ đĩa',
        UPLOAD_ERR_EXTENSION  => 'Upload bị chặn bởi extension PHP'
    ];

    echo json_encode([
        'success' => 0,
        'message' => $uploadErrors[$file['error']] ?? 'Lỗi upload không xác định'
    ]);
    exit;
}

/* 🟡 KIỂM TRA KÍCH THƯỚC (PHÒNG THỦ) */
$maxSize = 50 * 1024 * 1024; // 50MB
if ($file['size'] > $maxSize) {
    echo json_encode([
        'success' => 0,
        'message' => 'File quá lớn (tối đa 50MB)'
    ]);
    exit;
}

/* 🟢 KIỂM TRA ĐỊNH DẠNG */
$allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExt)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Định dạng file không được hỗ trợ'
    ]);
    exit;
}

/* 🟢 TẠO TÊN FILE */
$fileName = $user['user_id'] . '-' . uniqid('', true) . '.' . $ext;
$filePath = $uploadDir . $fileName;

/* 🟢 MOVE FILE */
if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Không thể lưu file lên server'
    ]);
    exit;
}

/* ✅ THÀNH CÔNG */
echo json_encode([
    'success' => 1,
    'file' => [
        'url' => $Domain . 'server/' . $filePath,
        'name' => $fileName,
        'size' => $file['size']
    ]
]);

?>
