<?php
require_once 'dp.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "SELECT * FROM gallery WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file = $row['image_file'];
        
        if (file_exists($file)) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $contentType = "image/jpeg";
            if ($ext === "png") {
                $contentType = "image/png";
            } else if ($ext === "gif") {
                $contentType = "image/gif";
            }
            
            header("Content-Type: $contentType");
            header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"");
            header("Content-Length: " . filesize($file));
            
            readfile($file);
            exit;
        } else {
            echo "Image file not found on server.";
        }
    } else {
        echo "Image record not found in database.";
    }
} else {
    echo "No image ID specified.";
}
?>
