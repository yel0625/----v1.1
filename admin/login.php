<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 验证管理员凭证
    if ($_POST['username'] === 'admin' && $_POST['password'] === '安全密码') {
        $_SESSION['admin_logged'] = true;
        header('Location: article-manage.php');
    }
}
?>
<!-- 登录表单HTML -->