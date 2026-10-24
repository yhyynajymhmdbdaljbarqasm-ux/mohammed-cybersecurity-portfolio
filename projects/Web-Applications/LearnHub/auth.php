<?php
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$currentUserId = (int) $_SESSION['admin_id'];
$currentUserName = $_SESSION['admin_name'] ?? '';
$currentUserEmail = $_SESSION['admin_email'] ?? '';
$currentUserRole = $_SESSION['admin_role'] ?? 'user';

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}

function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
    if (($_SESSION['admin_role'] ?? '') !== 'admin') {
        http_response_code(403);
        die("ليس لديك صلاحية للوصول إلى هذه الصفحة.");
    }
}

if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token() {
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die("طريقة الطلب غير مسموحة.");
    }
    if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])) {
        http_response_code(403);
        die("طلب غير صالح.");
    }
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die("انتهت صلاحية الطلب أو أن الطلب غير صالح.");
    }
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
