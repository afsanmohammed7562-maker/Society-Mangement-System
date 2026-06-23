<?php
$root = realpath(dirname(__DIR__) . '/..');
require_once $root . '/includes/session.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: ../auth/login.php");
    exit();
}
?>
