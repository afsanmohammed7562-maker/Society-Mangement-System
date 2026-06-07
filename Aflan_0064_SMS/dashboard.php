<?php
require_once 'includes/auth_check.php';

$role = $_SESSION['role'];
$dashboard_map = [
    'admin' => 'admin_dashboard.php',
    'member' => 'member_dashboard.php',
    'secretary' => 'secretary_dashboard.php',
    'treasurer' => 'treasurer_dashboard.php'
];
header("Location: " . ($dashboard_map[$role] ?? 'login.html'));
exit();
