<?php
// Determine active page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Society Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-warning py-0" href="dashboard.php">
        <img src="logo.jpeg =<?php echo time(); ?>" alt="SMS Logo" style="height: 45px; width: auto;" class="me-2 rounded-circle bg-white p-1"> Admin Panel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active text-warning' : ''; ?>" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'messages.php' ? 'active text-warning' : ''; ?>" href="messages.php">Messages</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'members.php' ? 'active text-warning' : ''; ?>" href="members.php">Members</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'treasurer.php' ? 'active text-warning' : ''; ?>" href="treasurer.php">Treasurer</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'secretary.php' ? 'active text-warning' : ''; ?>" href="secretary.php">Secretary</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $current_page == 'gallery.php' ? 'active text-warning' : ''; ?>" href="gallery.php">Gallery</a></li>
      </ul>
      <div class="d-flex align-items-center">
          <span class="text-white me-3">Welcome, <?php echo $_SESSION['role']; ?></span>
          <a href="../auth/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
      </div>
    </div>
  </div>
</nav>
<div class="main-content container-fluid py-4">