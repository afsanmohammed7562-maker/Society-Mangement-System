<?php
require_once 'includes/auth_check.php';
checkRole(['admin']);
include 'config/database.php';

$page_title = 'Admin Dashboard';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$total_members = $conn->query("SELECT COUNT(*) as total FROM members")->fetch_assoc()['total'];
$active_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE status = 'Active'")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) as total FROM userd")->fetch_assoc()['total'];
$total_announcements = $conn->query("SELECT COUNT(*) as total FROM messages WHERE type='announcement'")->fetch_assoc()['total'];
$new_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")->fetch_assoc()['total'];
?>
<div class="main-content">
    <div class="welcome-section">
        <h3><i class="bi bi-shield-lock me-2"></i>Admin Dashboard</h3>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage your society from here.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon blue"><i class="bi bi-people"></i></div>
                            <div class="card-number"><?php echo $total_members; ?></div>
                            <div class="card-label">Total Members</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon green"><i class="bi bi-person-check"></i></div>
                            <div class="card-number"><?php echo $active_members; ?></div>
                            <div class="card-label">Active Members</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon orange"><i class="bi bi-person-plus"></i></div>
                            <div class="card-number"><?php echo $new_members; ?></div>
                            <div class="card-label">New This Month</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon purple"><i class="bi bi-people-fill"></i></div>
                            <div class="card-number"><?php echo $total_users; ?></div>
                            <div class="card-label">System Users</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header"><h4>Quick Actions</h4></div>
    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <a href="create_member_login.php" class="quick-action-card">
                <div class="action-icon text-danger"><i class="bi bi-person-plus-fill"></i></div>
                <div class="action-title">Create Member Login</div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="manage_users.php" class="quick-action-card">
                <div class="action-icon text-primary"><i class="bi bi-people-fill"></i></div>
                <div class="action-title">Manage Users</div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="add_member.php" class="quick-action-card">
                <div class="action-icon text-success"><i class="bi bi-person-plus-fill"></i></div>
                <div class="action-title">Add New Member</div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="manage_members.php" class="quick-action-card">
                <div class="action-icon text-warning"><i class="bi bi-people"></i></div>
                <div class="action-title">Manage Members</div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="announcements.php" class="quick-action-card">
                <div class="action-icon text-info"><i class="bi bi-megaphone"></i></div>
                <div class="action-title">Announcements</div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="financial_records.php" class="quick-action-card">
                <div class="action-icon text-secondary"><i class="bi bi-wallet2"></i></div>
                <div class="action-title">Financial Records</div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="secretary_messages.php" class="quick-action-card">
                <div class="action-icon text-primary"><i class="bi bi-envelope"></i></div>
                <div class="action-title">Secretary Messages</div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="treasurer_messages.php" class="quick-action-card">
                <div class="action-icon text-success"><i class="bi bi-currency-dollar"></i></div>
                <div class="action-title">Treasurer Messages</div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="logout.php" class="quick-action-card">
                <div class="action-icon text-danger"><i class="bi bi-box-arrow-right"></i></div>
                <div class="action-title">Logout</div>
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-megaphone me-2"></i>Recent Announcements</h5>
                    <a href="announcements.php" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <?php
                    $ann = $conn->query("SELECT m.*, u.U_name as author FROM messages m LEFT JOIN userd u ON m.created_by = u.id WHERE m.type='announcement' ORDER BY m.created_at DESC LIMIT 5");
                    if ($ann && $ann->num_rows > 0):
                    ?>
                    <div class="list-group list-group-flush">
                        <?php while ($row = $ann->fetch_assoc()): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h6>
                                <small class="text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                            </div>
                            <p class="mb-1 text-muted"><?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 150))); ?>...</p>
                            <small>By: <?php echo htmlspecialchars($row['author'] ?? 'Unknown'); ?></small>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center py-3 mb-0">No announcements yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$conn->close();
include 'includes/footer.php';
?>
