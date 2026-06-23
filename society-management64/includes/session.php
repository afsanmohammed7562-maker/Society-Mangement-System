<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function regenerateSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_regenerate_id(true);
}

function setRememberMeCookie($user_id, $role, $is_admin) {
    $token = bin2hex(random_bytes(32));
    $expiry = time() + (30 * 24 * 60 * 60);
    setcookie('remember_token', $token, $expiry, '/', '', false, true);
    setcookie('user_id', base64_encode($user_id), $expiry, '/', '', false, true);
    setcookie('user_role', base64_encode($role), $expiry, '/', '', false, true);
    setcookie('is_admin', base64_encode($is_admin ? '1' : '0'), $expiry, '/', '', false, true);
    return $token;
}

function clearRememberMeCookies() {
    setcookie('remember_token', '', time() - 3600, '/');
    setcookie('user_id', '', time() - 3600, '/');
    setcookie('user_role', '', time() - 3600, '/');
    setcookie('is_admin', '', time() - 3600, '/');
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }
}

function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php");
        exit();
    }
}

function checkUser() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
        header("Location: ../auth/login.php");
        exit();
    }
}
?>
