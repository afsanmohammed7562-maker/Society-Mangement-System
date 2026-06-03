<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "sms_user";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>