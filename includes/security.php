<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
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

function qilin_admin_credentials_configured(): bool
{
    return trim((string) getenv('QILIN_ADMIN_PASSWORD_HASH')) !== '';
}

function qilin_verify_admin_credentials(string $username, string $password): bool
{
    $expectedUsername = trim((string) (getenv('QILIN_ADMIN_USERNAME') ?: 'admin'));
    $passwordHash = trim((string) getenv('QILIN_ADMIN_PASSWORD_HASH'));

    return $passwordHash !== ''
        && hash_equals($expectedUsername, $username)
        && password_verify($password, $passwordHash);
}
