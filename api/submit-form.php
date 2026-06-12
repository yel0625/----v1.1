<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.php');
    exit;
}

$name = trim((string) ($_POST['name'] ?? ''));
$company = trim((string) ($_POST['company'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || $company === '' || $phone === '' || $email === '' || $message === '') {
    echo "<script>alert('请完整填写联系表单信息。'); window.history.back();</script>";
    exit;
}

$saved = false;
$conn = qilin_db();

if ($conn) {
    $sql = 'INSERT INTO messages (name, company, phone, email, message, submit_time) VALUES (?, ?, ?, ?, ?, NOW())';
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('sssss', $name, $company, $phone, $email, $message);
        $saved = $stmt->execute();
        $stmt->close();
    }
}

if (!$saved) {
    $storageDir = dirname(__DIR__) . '/storage';
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0777, true);
    }

    $record = [
        'submitted_at' => date('c'),
        'name' => $name,
        'company' => $company,
        'phone' => $phone,
        'email' => $email,
        'message' => $message,
    ];

    $saved = (bool) file_put_contents($storageDir . '/contact-messages.log', json_encode($record, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
}

if ($saved) {
    echo "<script>alert('留言提交成功，我们会尽快与您联系。'); window.location.href='../contact.php';</script>";
    exit;
}

echo "<script>alert('留言暂时保存失败，请稍后重试。'); window.history.back();</script>";
