<?php
require_once 'includes/auth_check.php';
checkRole(['treasurer']);
include 'config/database.php';

$page_title = 'Treasurer Dashboard';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$total_accounts = $conn->query("SELECT COUNT(*) as total FROM accounts")->fetch_assoc()['total'];
$total_balance = $conn->query("SELECT COALESCE(SUM(balance), 0) as total FROM accounts")->fetch_assoc()['total'];
$active_members = $conn->query("SELECT COUNT(*) as total FROM members WHERE status='Active'")->fetch_assoc()['total'];
$my_messages = $conn->query("SELECT COUNT(*) as total FROM messages WHERE type='treasurer' AND created_by = " . $_SESSION['user_id'])->fetch_assoc()['total'];
?>
<div class="main-content">
    <div class="welcome-section">
        <h3><i class="bi bi-currency-dollar me-2"></i>Treasurer Dashboard</h3>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage financial records and treasurer messages.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon blue"><i class="bi bi-wallet2"></i></div>
                            <div class="card-number"><?php echo $total_accounts; ?></div>
                            <div class="card-label">Accounts</div>
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
                            <div class="card-icon green"><i class="bi bi-cash-stack"></i></div>
                            <div class="card-number">LKR <?php echo number_format($total_balance, 2); ?></div>
                            <div class="card-label">Total Balance</div>
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
                            <div class="card-icon orange"><i class="bi bi-person-check"></i></div>
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
                            <div class="card-icon purple"><i class="bi bi-envelope"></i></div>
                            <div class="card-number"><?php echo $my_messages; ?></div>
                            <div class="card-label">My Messages</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header"><h4>Quick Actions</h4></div>
    <div class="row g-4">
        <div class="col-xl-4 col-md-6">
            <a href="financial_records.php" class="quick-action-card">
                <div class="action-icon text-primary"><i class="bi bi-wallet2"></i></div>
                <div class="action-title">Manage Financial Records</div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="treasurer_messages.php" class="quick-action-card">
                <div class="action-icon text-success"><i class="bi bi-currency-dollar"></i></div>
                <div class="action-title">Post Treasurer Message</div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="logout.php" class="quick-action-card">
                <div class="action-icon text-danger"><i class="bi bi-box-arrow-right"></i></div>
                <div class="action-title">Logout</div>
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-currency-dollar me-2"></i>My Recent Treasurer Messages</h5></div>
                <div class="card-body p-3">
                    <?php
                    $msgs = $conn->prepare("SELECT * FROM messages WHERE type='treasurer' AND created_by = ? ORDER BY created_at DESC LIMIT 5");
                    $msgs->bind_param("i", $_SESSION['user_id']);
                    $msgs->execute();
                    $result = $msgs->get_result();
                    if ($result->num_rows > 0):
                    ?>
                    <div class="list-group list-group-flush">
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h6>
                                <small class="text-muted"><?php echo date('d M Y h:i A', strtotime($row['created_at'])); ?></small>
                            </div>
                            <p class="mb-1 text-muted small"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center py-3 mb-0">No messages posted yet.</p>
                    <?php endif; ?>
                    <?php $msgs->close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$conn->close();
include 'includes/footer.php';
?>
