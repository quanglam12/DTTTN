<?php
header('Content-Type: application/json');


$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['files'])) {
    echo json_encode(['success' => false, 'message' => 'Không có file để xóa']);
    exit;
}

$deletedCount = 0;

foreach ($data['files'] as $url) {

    if (!preg_match('@^uploads/(images|files)/@', $url[0])) {
        echo json_encode(['success' => false, 'message' => 'Invalid file path'. $url[0]]);
        exit;
    }
    $filePath = __DIR__ .'/'. $url[0];
    echo json_encode(['success'=> true,'url'=> $filePath]);
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            $deletedCount++;
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa file: ' . $filePath]);
            exit;
        }
    }

}


echo json_encode([
    'success' => true,
    'message' => "Đã xóa $deletedCount file tạm."
]);
