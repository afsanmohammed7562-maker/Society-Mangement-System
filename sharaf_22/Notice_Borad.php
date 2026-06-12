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

            <button>
                <i class="fa-solid fa-right-from-bracket"></i>
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

   
    <footer class="footer">

        <div class="footer-container">

            <div class="footer-box">
                <h2>Quick Links</h2>

                <ul>
                    <li><a href="homepage.html">Home</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="Secretary.php">Secretary</a></li>
                    <li><a href="Treasurer.php">Treasurer</a></li>
                    <li><a href="gellery.php">Gallery</a></li>
                    <li><a href="Notice_Borad.php">Notice Board</a></li>
                    <li><a href="members.php">Members</a></li>
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

</body>

</html>
