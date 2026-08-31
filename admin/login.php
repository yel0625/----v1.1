<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = trim((string) ($_POST['password'] ?? ''));

    $attempts = (int) ($_SESSION['admin_login_attempts'] ?? 0);
    $lastAttempt = (int) ($_SESSION['admin_login_last_attempt'] ?? 0);
    if ($attempts >= 5 && time() - $lastAttempt < 300) {
        $error = '登录尝试过多，请五分钟后再试。';
    } elseif (!qilin_validate_csrf()) {
        $error = '表单已过期，请刷新页面后重试。';
    } elseif (qilin_verify_admin_credentials($username, $password)) {
        session_regenerate_id(true);
        $_SESSION['admin_logged'] = true;
        unset($_SESSION['admin_login_attempts'], $_SESSION['admin_login_last_attempt']);
        header('Location: article-manage.php');
        exit;
    } else {
        $_SESSION['admin_login_attempts'] = $attempts + 1;
        $_SESSION['admin_login_last_attempt'] = time();
        $error = qilin_admin_credentials_configured()
            ? '账号或密码错误。'
            : '后台尚未配置。请设置 QILIN_ADMIN_PASSWORD_HASH 环境变量。';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台登录 - <?php echo e(qilin_config('brand_name')); ?></title>
    <link rel="stylesheet" href="../styles/main.css">
    <style>
        .admin-login { max-width: 420px; margin: 120px auto 40px; padding: 32px; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .admin-login h1 { margin-bottom: 24px; color: #1e4b94; }
        .admin-login label { display: block; margin-bottom: 8px; color: #1e4b94; }
        .admin-login input { width: 100%; padding: 12px; margin-bottom: 18px; border: 1px solid #d9dfe8; border-radius: 8px; }
        .admin-login button { width: 100%; padding: 12px; border: 0; border-radius: 8px; background: #1e4b94; color: #fff; cursor: pointer; }
        .admin-login .error { margin-bottom: 16px; color: #c62828; }
    </style>
</head>
<body>
    <div class="admin-login">
        <h1>后台登录</h1>
        <?php if ($error !== ''): ?>
            <p class="error"><?php echo e($error); ?></p>
        <?php endif; ?>
        <form method="post">
            <?php echo qilin_csrf_input(); ?>
            <label for="username">用户名</label>
            <input id="username" name="username" type="text" required>

            <label for="password">密码</label>
            <input id="password" name="password" type="password" required>

            <button type="submit">登录后台</button>
        </form>
    </div>
</body>
</html>
