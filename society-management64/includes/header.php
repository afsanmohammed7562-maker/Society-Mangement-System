<?php
// Determine active page for highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Society Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary py-0" href="index.php">
        <img src="logo.jpeg" alt="SMS Logo" style="height: 50px; width: auto;" class="me-2">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'index.php' ? 'active text-primary' : ''; ?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'contact.php' ? 'active text-primary' : ''; ?>" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'secretary.php' ? 'active text-primary' : ''; ?>" href="secretary.php">Secretary</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'treasurer.php' ? 'active text-primary' : ''; ?>" href="treasurer.php">Treasurer</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'gallery.php' ? 'active text-primary' : ''; ?>" href="gallery.php">Gallery</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'notice_board.php' ? 'active text-primary' : ''; ?>" href="notice_board.php">Notice Board</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'members.php' ? 'active text-primary' : ''; ?>" href="members.php">Members</a></li>
      </ul>
    </div>
    <div class="d-flex align-items-center">
        <a href="account.php" class="text-dark me-3"><i class="fa-solid fa-circle-user fa-2x"></i></a>
        <a href="auth/logout.php" class="btn btn-outline-danger btn-sm"><i class="fa fa-sign-out py-1"></i></a>
    </div>
  </div>
</nav>
<div class="main-content">
