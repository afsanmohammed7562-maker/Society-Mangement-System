<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contacts(name, email, message)
            VALUES('$name', '$email', '$message')";

    if (mysqli_query($conn, $sql)) {
        echo "Message submitted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>