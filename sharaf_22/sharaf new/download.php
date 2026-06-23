<?php

$conn = new mysqli("localhost", "root", "", "society_dp");

if ($conn->connect_error) {
    die("Database connection failed");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM reports WHERE id = $id";
$result = $conn->query($sql);

if($result->num_rows > 0){

    $row = $result->fetch_assoc();

    $file = "report/" . $row['pdf_file'];

    if(file_exists($file)){

        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");
        header("Content-Length: " . filesize($file));

        readfile($file);
        exit;

    } else {
        echo "PDF file not found!";
    }

} else {
    echo "Report not found!";
}

?>