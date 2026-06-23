<?php
session_start();
include '../includes/db.php';
// Note: Registration is primarily done by Admin as per requirements. 
// This file serves as a placeholder or if public registration is enabled in future.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Society Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="auth-form" style="text-align:center;">
            <h2>Registration</h2>
            <p>Please contact the Society Administration or Secretary to create your account.</p>
            <br>
            <a href="login.php" style="color:var(--primary-color);">Back to Login</a>
        </div>
    </div>
</body>
</html>
