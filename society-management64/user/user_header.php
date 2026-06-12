<?php
require_once dirname(__DIR__) . '/includes/session.php';
checkUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Society Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo-area">
            <img src="../assets/images/logo.png" alt="Logo" class="logo" style="display:none;">
            <h2 style="color:var(--primary-color);">My Society</h2>
        </div>
        <nav>
            <a href="home.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>">Home</a>
            <a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a>
            <!-- Links as per prompt, creating pages for them -->
            <a href="secretory.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'secretory.php' ? 'active' : ''; ?>">Secretary</a>
            <a href="trusser.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'trusser.php' ? 'active' : ''; ?>">Treasurer</a>
            <a href="gallery.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">Gallery</a>
            <a href="notice.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'notice.php' ? 'active' : ''; ?>">Notice Board</a>
        </nav>
        <a href="account.php"><i class="fa fa-user-circle account-icon"></i></a>
        <a href="../auth/logout.php" style="margin-left:1rem; color:var(--accent-color); text-decoration:none;"><i class="fa fa-sign-out-alt"></i></a>
    </header>
    <main>
