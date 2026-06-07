<?php
$role = $_SESSION['role'] ?? 'member';
$dashboard_link = $role . '_dashboard.php';
$roleLabel = ucfirst($role);
?>
<nav class="navbar navbar-expand-lg fixed-top navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $dashboard_link; ?>">
            <i class="bi bi-building me-2"></i>
            <span>SMS - <?php echo $roleLabel; ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                        <span class="badge bg-<?php echo $role === 'admin' ? 'danger' : ($role === 'secretary' ? 'warning text-dark' : ($role === 'treasurer' ? 'success' : 'primary')); ?> ms-2" style="font-size:0.65rem;"><?php echo $roleLabel; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo $dashboard_link; ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
