<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>

    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="footer.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f2f4f7;
            color: #333;
        }

        .contact-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 20px;
            min-height: calc(100vh - 250px);
        }

        .contact-box {
            width: 100%;
            max-width: 550px;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
        }

        .icon {
            width: 70px;
            height: 70px;
            background: rgba(91, 43, 144, 0.1);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px auto;
        }

        .icon i {
            font-size: 30px;
            color: #5b2b90;
        }

        .contact-box h1 {
            font-size: 28px;
            color: #0f1f4b;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .contact-box p {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .input-box {
            display: flex;
            flex-direction: column;
            text-align: left;
            margin-bottom: 20px;
        }

        .input-box label {
            font-size: 14px;
            font-weight: 600;
            color: #1c2f67;
            margin-bottom: 8px;
        }

        .input-box input,
        .input-box textarea {
            padding: 14px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            background: #fafafa;
            transition: 0.3s;
        }

        .input-box input:focus,
        .input-box textarea:focus {
            border-color: #5b2b90;
            background: #fff;
        }

        .input-box textarea {
            height: 120px;
            resize: none;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: #5b2b90;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background: #421d69;
        }

        @media (max-width: 576px) {
            .contact-box {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Reusable Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Contact Form Section -->
    <div class="contact-container">
        <div class="contact-box">

            <div class="icon">
                <i class="fa-solid fa-paper-plane"></i>
            </div>

            <h1>Contact Admin</h1>
            <p>Have a question or issue? Send us a message.</p>

            <form action="contectinsert.php" method="POST">

                <div class="input-box">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Enter your name" required>
                </div>

                <div class="input-box">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Enter username" required>
                </div>

                <div class="input-box">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="Enter phone number" required>
                </div>

                <div class="input-box">
                    <label>Message</label>
                    <textarea name="message" placeholder="Write your message" required></textarea>
                </div>

                <button type="submit" name="submit" class="btn">Send Message</button>

            </form>

        </div>
    </div>

    <!-- Reusable Footer -->
    <?php include 'footer.php'; ?>

</body>
</html>
