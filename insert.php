<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "society_dp";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$regno = $_POST['regno'];
$name  = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];

$sql = "INSERT INTO members (regno, name, phone, email)
        VALUES ('$regno', '$name', '$phone', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "Member added successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>