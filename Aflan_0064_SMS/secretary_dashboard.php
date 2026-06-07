<?php
require_once 'includes/auth_check.php';
checkRole(['secretary']);
include 'config/database.php';

$page_title = 'Secretary Dashboard';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$total_announcements = $conn->query("SELECT COUNT(*) as total FROM messages WHERE type='announcement'")->fetch_assoc()['total'];
$my_messages = $conn->query("SELECT COUNT(*) as total FROM messages WHERE type='secretary' AND created_by = " . $_SESSION['user_id'])->fetch_assoc()['total'];
?>
<div class="main-content">
    <div class="welcome-section">
        <h3><i class="bi bi-envelope me-2"></i>Secretary Dashboard</h3>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage secretary messages and view announcements.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon blue"><i class="bi bi-megaphone"></i></div>
                            <div class="card-number"><?php echo $total_announcements; ?></div>
                            <div class="card-label">Total Announcements</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon green"><i class="bi bi-envelope"></i></div>
                            <div class="card-number"><?php echo $my_messages; ?></div>
                            <div class="card-label">My Messages</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon orange"><i class="bi bi-clock-history"></i></div>
                            <div class="card-number">
                                <?php
                                $pending = $conn->query("SELECT COUNT(*) as total FROM messages WHERE type='secretary' AND DATE(created_at) = CURDATE()");
                                echo $pending ? $pending->fetch_assoc()['total'] : 0;
                                ?>
                            </div>
                            <div class="card-label">Posted Today</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header"><h4>Quick Actions</h4></div>
    <div class="row g-4">
        <div class="col-xl-4 col-md-6">
            <a href="secretary_messages.php" class="quick-action-card">
                <div class="action-icon text-primary"><i class="bi bi-envelope-plus"></i></div>
                <div class="action-title">Post Secretary Message</div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="announcements.php" class="quick-action-card">
                <div class="action-icon text-success"><i class="bi bi-megaphone"></i></div>
                <div class="action-title">View Announcements</div>
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
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-envelope me-2"></i>My Recent Secretary Messages</h5></div>
                <div class="card-body p-3">
                    <?php
                    $msgs = $conn->prepare("SELECT * FROM messages WHERE type='secretary' AND created_by = ? ORDER BY created_at DESC LIMIT 5");
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
