<?php

include "db_connect.php";

if(isset($_POST['submit'])){

    $reg_no   = $_POST['reg_no'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $phone    = $_POST['phone'];
    $email    = $_POST['email'];
    $address  = $_POST['address'];

    $sql = "INSERT INTO members
            (reg_no, username, fullname, phone, email, address)
            VALUES
            ('$reg_no', '$username', '$fullname', '$phone', '$email', '$address')";

    $result = mysqli_query($conn, $sql);

    if($result){
        echo "<script>
                alert('Data Saved Successfully');
                window.location='Account.php';
              </script>";
    }
    else{
        echo "Error : " . mysqli_error($conn);
    }
}

?>