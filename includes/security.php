<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function qilin_csrf_token(): string
{
    return $_SESSION['csrf_token'];
}

function qilin_csrf_input(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(qilin_csrf_token()) . '">';
}

function qilin_validate_csrf(): bool
{
    return isset($_POST['csrf_token']) && hash_equals(qilin_csrf_token(), (string) $_POST['csrf_token']);
}

function qilin_is_admin(): bool
{
    return !empty($_SESSION['admin_logged']);
}

function qilin_require_admin(): void
{
    if (!qilin_is_admin()) {
        header('Location: login.php');
        exit;
    }
}
