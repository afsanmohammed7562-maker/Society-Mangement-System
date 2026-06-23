<?php
require_once 'dp.php';

$sql = "SELECT * FROM notices ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Notice Board</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="heder.css">
    <link rel="stylesheet" href="fooder.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

   
      <header>

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
            <div class="icon">📢</div>
            <h1>Notice Board</h1>
        </div>

        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $title = htmlspecialchars($row['title']);
                $description = htmlspecialchars($row['description']);
                $notice_date = htmlspecialchars($row['notice_date']);
                ?>
                <div class="card">
                    <div>
                        <h2><?php echo $title; ?></h2>
                        <p><?php echo $description; ?></p>
                    </div>

                    <div class="date">📅 <?php echo $notice_date; ?></div>
                </div>
                <?php
            }
        } else {
            echo "<div class='card'><div><h2>No notices</h2><p>No notices posted yet.</p></div></div>";
        }
        ?>

    </div>

   
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

</body>

</html>
