<?php
require_once 'dp.php';

$sql = "SELECT * FROM reports ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Secretary Reports</title>

    <link rel="stylesheet" href="Secretary.css" />

  
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
   
 <link rel="stylesheet" href="heder.css">
<header class="header">

    
    <div class="logo">
       <img src="shrf.jpg" alt="">
    </div>

   
    <nav class="navbar">

        <a href="homepage.html">Home</a>
        <a href="">Contact</a>
        <a href="Secretary.php">Secretary</a>
        <a href="">Treasurer</a>
        <a href="gellery.php">Gallery</a>
        <a href="Notice_Borad.php">Notice Board</a>
        <a href="">Members</a>

    </nav>

   
    <div class="header-icons">

        <i class="fa-solid fa-circle-user"></i>

        <button0>
            <i class="fa-solid fa-right-from-bracket"></i>
        </button0>
        <link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    </div>
    

</header>



    <div class="container">

        <div class="title">
            <i class="fa-solid fa-file-lines"></i>
            <h1>Secretary Reports</h1>
        </div>

        <div class="report-container">

            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $report_name = htmlspecialchars($row['report_name']);
                    $pdf_file = $row['pdf_file'];
                    
                    
                    $file_path = "report/" . $pdf_file;
                    if (file_exists($file_path)) {
                        $uploaded_date = date("d M Y", filemtime($file_path));
                    } else {
                        $uploaded_date = "N/A";
                    }
                    ?>
                    <div class="card">
                        <i class="fa-solid fa-file card-icon"></i>
                        <h2><?php echo $report_name; ?></h2>
                        <p>Uploaded: <?php echo $uploaded_date; ?></p>
                        <a href="download.php?id=<?php echo $id; ?>" style="text-decoration: none;">
                            <button type="button">Download Report</button>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No reports found in the database</p>";
            }
            ?>

        </div>

    </div>

</body>
</html>

<link rel="stylesheet" href="fooder.css"/>

<footer class="footer">

    <div class="footer-container">

        
        <div class="footer-box">

            <h2>Quick Links</h2>

            <ul>
                <li><a href="homepage.html">Home</a></li>
                <li><a href="">Contact</a></li>
                <li><a href="Secretary.php">Secretary</a></li>
                <li><a href="">Treasurer</a></li>
                <li><a href="gellery.php">Gallery</a></li>
                <li><a href="Notice_Borad.php">Notice Board</a></li>
                <li><a href="">Members</a></li>
            </ul>

        </div>

        
        <div class="footer-box">

            <h2>Contact Info</h2>

            <p><i class="fa-solid fa-phone"></i> +94 761929402</p>

            <p><i class="fa-brands fa-whatsapp"></i> +94 761929402</p>

            <p><i class="fa-solid fa-envelope"></i> sharaf7@gmail.com</p>

            <p><i class="fa-solid fa-location-dot"></i> 133A, Vilinyadi 03, Sammanthurai</p>

        </div>

        
        <div class="footer-box">

            <h2>Follow Us</h2>

            <div class="social-icons">

                <a href="#"><i class="fa-brands fa-facebook"></i></a>

                <a href="#"><i class="fa-brands fa-instagram"></i></a>

                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>

                <a href="#"><i class="fa-brands fa-linkedin"></i></a>

            </div>

        </div>

    </div>

    
    <div class="footer-bottom">

        <p>© 2026 Society Management System. All Rights Reserved By SHARAF.</p>

    </div>

</footer>
