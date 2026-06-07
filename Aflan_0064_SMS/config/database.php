<?php
// ============================================================
// Database Configuration
// ============================================================
// This single connection file is used by ALL pages:
//   - login.php & register.php  (userd table)
//   - dashboard.php              (members table)
//   - add_member.php             (members table)
//   - manage_members.php         (members table)
//   - edit_member.php            (members table)
//   - delete_member.php          (members table)
//   - view_member.php            (members table)
// ============================================================

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "userlogin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
