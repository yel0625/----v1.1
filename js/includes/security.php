session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_input() {
    return '<input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">';
}

function validate_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF验证失败");
    }
}