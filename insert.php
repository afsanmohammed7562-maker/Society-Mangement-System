<?php
include "db.php";

if(isset($_POST['submit'])){

    // Sanitize user inputs to protect against SQL Injection
    $reg_no   = mysqli_real_escape_string($conn, $_POST['reg_no']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $address  = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if member already exists to prevent duplicate entries
    $check_query = "SELECT * FROM members WHERE reg_no='$reg_no' OR username='$username'";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('Error: Registration Number or Username already exists!');
                window.history.back();
              </script>";
        exit();
    }

    $sql = "INSERT INTO members
            (reg_no, username, fullname, phone, email, address)
            VALUES
            ('$reg_no', '$username', '$fullname', '$phone', '$email', '$address')";

    $result = mysqli_query($conn, $sql);

    if($result){
        echo "<script>
                alert('Member Registered Successfully');
                window.location='members.php';
              </script>";
    }
    else{
        echo "Error : " . mysqli_error($conn);
    }
}
?>