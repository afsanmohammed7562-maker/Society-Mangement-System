<?php
$role = $_SESSION['role'] ?? 'member';
$current_page = basename($_SERVER['PHP_SELF']);
$dashboard_link = $role . '_dashboard.php';
?>
<div class="sidebar" id="sidebarMenu">
    <div class="sidebar-header">
        <div class="sidebar-brand d-flex align-items-center">
            <i class="bi bi-building fs-4 me-2"></i>
            <span>SMS</span>
        </div>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == $dashboard_link ? 'active' : ''; ?>" href="<?php echo $dashboard_link; ?>">
                <i class="bi bi-speedometer2 me-2"></i>
                <span>Dashboard</span>
            </a>
        </li>

<?php if ($role === 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'create_member_login.php' ? 'active' : ''; ?>" href="create_member_login.php">
                <i class="bi bi-person-plus-fill me-2"></i>
                <span>Create Member Login</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'manage_users.php' ? 'active' : ''; ?>" href="manage_users.php">
                <i class="bi bi-people-fill me-2"></i>
                <span>Manage Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'add_member.php' ? 'active' : ''; ?>" href="add_member.php">
                <i class="bi bi-person-plus me-2"></i>
                <span>Add Member</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo in_array($current_page, ['manage_members.php', 'edit_member.php', 'view_member.php']) ? 'active' : ''; ?>" href="manage_members.php">
                <i class="bi bi-people me-2"></i>
                <span>Manage Members</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'announcements.php' ? 'active' : ''; ?>" href="announcements.php">
                <i class="bi bi-megaphone me-2"></i>
                <span>Announcements</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'secretary_messages.php' ? 'active' : ''; ?>" href="secretary_messages.php">
                <i class="bi bi-envelope me-2"></i>
                <span>Secretary Messages</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'treasurer_messages.php' ? 'active' : ''; ?>" href="treasurer_messages.php">
                <i class="bi bi-currency-dollar me-2"></i>
                <span>Treasurer Messages</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'financial_records.php' ? 'active' : ''; ?>" href="financial_records.php">
                <i class="bi bi-wallet2 me-2"></i>
                <span>Financial Records</span>
            </a>
        </li>

<?php elseif ($role === 'secretary'): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'secretary_messages.php' ? 'active' : ''; ?>" href="secretary_messages.php">
                <i class="bi bi-envelope me-2"></i>
                <span>Secretary Messages</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'announcements.php' ? 'active' : ''; ?>" href="announcements.php">
                <i class="bi bi-megaphone me-2"></i>
                <span>View Announcements</span>
            </a>
        </li>

<?php elseif ($role === 'treasurer'): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'treasurer_messages.php' ? 'active' : ''; ?>" href="treasurer_messages.php">
                <i class="bi bi-currency-dollar me-2"></i>
                <span>Treasurer Messages</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'financial_records.php' ? 'active' : ''; ?>" href="financial_records.php">
                <i class="bi bi-wallet2 me-2"></i>
                <span>Financial Records</span>
            </a>
        </li>

<?php else: // member ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'announcements.php' ? 'active' : ''; ?>" href="announcements.php">
                <i class="bi bi-megaphone me-2"></i>
                <span>Announcements</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'secretary_messages.php' ? 'active' : ''; ?>" href="secretary_messages.php">
                <i class="bi bi-envelope me-2"></i>
                <span>Secretary Messages</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_page == 'treasurer_messages.php' ? 'active' : ''; ?>" href="treasurer_messages.php">
                <i class="bi bi-currency-dollar me-2"></i>
                <span>Treasurer Messages</span>
            </a>
        </li>
<?php endif; ?>

        <li class="nav-item mt-3">
            <hr class="text-secondary">
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">
                <i class="bi bi-box-arrow-left me-2"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>
