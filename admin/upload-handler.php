$allowed = [
    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif'
];

$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
$detectedType = finfo_file($fileInfo, $_FILES['file']['tmp_name']);

if (!in_array($detectedType, $allowed)) {
    die("非法文件类型");
}

// 生成安全文件名
$newFilename = sprintf("%s.%s",
    sha1_file($_FILES['file']['tmp_name']),
    pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION)
);