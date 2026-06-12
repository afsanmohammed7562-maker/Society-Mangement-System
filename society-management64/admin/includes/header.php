<?php
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

<style>
.admin-navbar {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    padding: 0.8rem 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
.admin-navbar .navbar-brand {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
}
.admin-navbar .navbar-brand img {
    background: white !important;
    padding: 5px !important;
}
.admin-navbar .nav-link {
    color: rgba(255,255,255,0.8) !important;
    padding: 0.7rem 1.2rem !important;
    border-radius: 8px;
    margin: 0 3px;
    transition: all 0.3s ease;
    font-weight: 500;
    position: relative;
}
.admin-navbar .nav-link:hover {
    color: white !important;
    background: rgba(102, 126, 234, 0.3);
}
.admin-navbar .nav-link.active {
    color: white !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}
.admin-navbar .nav-link i {
    margin-right: 6px;
}
.admin-navbar .user-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 8px 16px;
    border-radius: 25px;
    color: white;
    font-weight: 500;
}
.admin-navbar .btn-logout {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    transition: all 0.3s ease;
}
.admin-navbar .btn-logout:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
    color: white;
}
.admin-navbar .navbar-toggler {
    border: 2px solid rgba(255,255,255,0.3);
    padding: 5px 10px;
}
.admin-navbar .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.95%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}
</style>

<nav class="navbar navbar-expand-lg admin-navbar sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold py-0" href="dashboard.php">
        <img src="../logo.jpeg?v=<?php echo time(); ?>" alt="SMS Logo" style="height: 42px; width: auto;" class="me-2 rounded-3"> Admin Panel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                <i class="fa fa-chart-line"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'messages.php' ? 'active' : ''; ?>" href="messages.php">
                <i class="fa fa-envelope"></i>Messages
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'members.php' ? 'active' : ''; ?>" href="members.php">
                <i class="fa fa-users"></i>Members
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'treasurer.php' ? 'active' : ''; ?>" href="treasurer.php">
                <i class="fa fa-coins"></i>Treasurer
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'secretary.php' ? 'active' : ''; ?>" href="secretary.php">
                <i class="fa fa-file-signature"></i>Secretary
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'gallery.php' ? 'active' : ''; ?>" href="gallery.php">
                <i class="fa fa-images"></i>Gallery
            </a>
        </li>
      </ul>
      <div class="d-flex align-items-center gap-3">
          <span class="user-info"><i class="fa fa-user-circle me-2"></i><?php echo ucfirst($_SESSION['role']); ?></span>
          <a href="../auth/logout.php" class="btn btn-logout"><i class="fa fa-sign-out-alt me-1"></i>Logout</a>
      </div>
    </div>
  </div>
</nav>
<div class="main-content container-fluid py-4">
