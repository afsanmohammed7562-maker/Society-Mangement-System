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

   <header>
<link rel="stylesheet" href="heder.css">
    <div class="logo">
        <img src="shrf.jpg" alt="Logo">
    </div>

    <nav>
        <ul>
            <li><a href="homepage.html">Home</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="Secretary.php">Secretary</a></li>
            <li><a href="#">Treasurer</a></li>
            <li><a href="gellery.php">Gallery</a></li>
            <li><a href="Notice_Borad.php">Notice Board</a></li>
            <li><a href="#">Members</a></li>
        </ul>
    </nav>

    <div class="header-icons">
        <div class="profile-icon">
            <i class="fas fa-user"></i>
        </div>

        <button class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
        </button>
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

<link rel="stylesheet" href="fooder.css">

<footer>

    <div class="footer-content">

        <!-- Quick Links -->
        <div class="footer-section">

            <h3>Quick Links</h3>

            <ul>
                <li><a href="homepage.html">Home</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="Secretary.php">Secretary</a></li>
                <li><a href="#">Treasurer</a></li>
                <li><a href="gellery.php">Gallery</a></li>
            </ul>

        </div>

        <!-- Contact Info -->
        <div class="footer-section contact-info">

            <h3>Contact Info</h3>

            <p><i class="fas fa-phone"></i> +94786686201</p>
            <p><i class="fab fa-whatsapp"></i> +94742590972</p>
            <p><i class="fas fa-envelope"></i> smmsharaf7@gmail.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Sainthamaruthu, Sri Lanka</p>

        </div>

        <!-- Follow Us -->
        <div class="footer-section">

            <h3>Follow Us</h3>

            <div class="social-icons">

                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>

            </div>

        </div>

    </div>

    <div class="footer-bottom">
        © 2026 Society Management System. All Rights Reserved By SHRF.
    </div>

</footer>