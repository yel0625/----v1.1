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
$country = trim((string) ($_POST['country'] ?? ''));
$targetCapacity = trim((string) ($_POST['target_capacity'] ?? ''));
$website = trim((string) ($_POST['website'] ?? ''));
$locale = in_array(($_POST['locale'] ?? 'zh'), ['zh', 'en', 'ru'], true) ? $_POST['locale'] : 'zh';

$messages = [
    'zh' => ['invalid' => '请完整并正确填写联系表单信息。', 'success' => '留言提交成功，我们会尽快与您联系。', 'failed' => '留言暂时保存失败，请稍后重试。', 'slow' => '提交过于频繁，请稍后再试。'],
    'en' => ['invalid' => 'Please complete the contact form correctly.', 'success' => 'Your message was submitted successfully. We will contact you soon.', 'failed' => 'Your message could not be saved. Please try again later.', 'slow' => 'Too many submissions. Please try again later.'],
    'ru' => ['invalid' => 'Пожалуйста, правильно заполните все поля формы.', 'success' => 'Сообщение успешно отправлено. Мы скоро свяжемся с вами.', 'failed' => 'Не удалось сохранить сообщение. Повторите попытку позже.', 'slow' => 'Слишком много запросов. Повторите попытку позже.'],
][$locale];

$redirect = '../thanks.php?lang=' . rawurlencode($locale);
$formPage = $locale === 'zh' ? '../contact.php' : '../' . $locale . '/contact.html';

if ($website !== '') {
    header('Location: ' . $formPage);
    exit;
}

if ($name === '' || ($phone === '' && $email === '') || $message === ''
    || ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))
    || mb_strlen($name) > 100 || mb_strlen($company) > 200 || mb_strlen($phone) > 50 || mb_strlen($country) > 100 || mb_strlen($targetCapacity) > 100 || mb_strlen($message) > 5000) {
    echo '<script>alert(' . json_encode($messages['invalid'], JSON_UNESCAPED_UNICODE) . '); window.history.back();</script>';
    exit;
}

$contextLines = [];
if ($country !== '') {
    $contextLines[] = '国家或地区：' . $country;
}
if ($targetCapacity !== '') {
    $contextLines[] = '目标产能：' . $targetCapacity;
}
if ($contextLines) {
    $message = implode("\n", $contextLines) . "\n\n" . $message;
}

$rateFile = sys_get_temp_dir() . '/qilin-contact-' . hash('sha256', (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
$lastSubmit = is_file($rateFile) ? (int) file_get_contents($rateFile) : 0;
if ($lastSubmit > 0 && time() - $lastSubmit < 30) {
    http_response_code(429);
    echo '<script>alert(' . json_encode($messages['slow'], JSON_UNESCAPED_UNICODE) . '); window.history.back();</script>';
    exit;
}
file_put_contents($rateFile, (string) time(), LOCK_EX);

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
    $storageDir = dirname(__DIR__, 2) . '/qilin-private-storage';
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0770, true);
    }

    $record = [
        'submitted_at' => date('c'),
        'name' => $name,
        'company' => $company,
        'phone' => $phone,
        'email' => $email,
        'message' => $message,
        'country' => $country,
        'target_capacity' => $targetCapacity,
    ];

    $saved = (bool) file_put_contents($storageDir . '/contact-messages.log', json_encode($record, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
}

if ($saved) {
    echo '<script>alert(' . json_encode($messages['success'], JSON_UNESCAPED_UNICODE) . '); window.location.href=' . json_encode($redirect) . ';</script>';
    exit;
}

echo '<script>alert(' . json_encode($messages['failed'], JSON_UNESCAPED_UNICODE) . '); window.history.back();</script>';
