<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';

qilin_require_admin();
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !qilin_validate_csrf()) {
    http_response_code(403);
    echo json_encode(['error' => '请求无效或表单已过期。'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => '请选择有效的图片文件。'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_FILES['file']['size'] <= 0 || $_FILES['file']['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => '图片大小必须在 5MB 以内。'], JSON_UNESCAPED_UNICODE);
    exit;
}

$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
$detectedType = $fileInfo ? finfo_file($fileInfo, $_FILES['file']['tmp_name']) : false;
if ($fileInfo) {
    finfo_close($fileInfo);
}

if (!is_string($detectedType) || !isset($allowed[$detectedType])) {
    http_response_code(400);
    echo json_encode(['error' => '仅支持 JPG、PNG 和 GIF 图片。'], JSON_UNESCAPED_UNICODE);
    exit;
}

$uploadDir = dirname(__DIR__) . '/images/uploads';
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
    http_response_code(500);
    echo json_encode(['error' => '无法创建上传目录。'], JSON_UNESCAPED_UNICODE);
    exit;
}

$newFilename = bin2hex(random_bytes(16)) . '.' . $allowed[$detectedType];
if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . '/' . $newFilename)) {
    http_response_code(500);
    echo json_encode(['error' => '图片保存失败。'], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['path' => 'images/uploads/' . $newFilename], JSON_UNESCAPED_UNICODE);
