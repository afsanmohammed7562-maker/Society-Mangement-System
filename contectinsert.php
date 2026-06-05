<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs to protect against SQL Injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contact_messages 
            (name, username, phone, message) 
            VALUES 
            ('$name', '$username', '$phone', '$message')";

    if(mysqli_query($conn, $sql))
    {
        echo "
        <script>
            alert('Message Sent Successfully');
            window.location.href='contact.php';
        </script>";
    }
    else
    {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: contact.php");
    exit();
}

mysqli_close($conn);
?>
