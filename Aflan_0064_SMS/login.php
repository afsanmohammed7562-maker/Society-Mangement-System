<?php
session_start();
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Userd WHERE U_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['U_pass'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['U_name'];
            $_SESSION['email'] = $user['U_email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['member_id_ref'] = $user['member_id_ref'] ?? null;

            $role = $user['role'];
            if ($role === 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($role === 'secretary') {
                header("Location: secretary_dashboard.php");
            } elseif ($role === 'treasurer') {
                header("Location: treasurer_dashboard.php");
            } else {
                header("Location: member_dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username not found. Please register first.'); window.location.href = 'register.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
