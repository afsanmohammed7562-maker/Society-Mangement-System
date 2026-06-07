<?php
require_once 'includes/auth_check.php';
checkRole(['member']);
include 'config/database.php';

$page_title = 'Member Dashboard';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$member_id_ref = $_SESSION['member_id_ref'] ?? null;

$has_member_id_ref = $conn->query("SHOW COLUMNS FROM userd LIKE 'member_id_ref'");
$has_accounts_table = $conn->query("SHOW TABLES LIKE 'accounts'");

if (!$member_id_ref) {
    $find = $conn->prepare("SELECT member_id FROM members WHERE email = ? OR full_name = ? LIMIT 1");
    $email = $_SESSION['email'] ?? '';
    $name = $_SESSION['username'] ?? '';
    $find->bind_param("ss", $email, $name);
    $find->execute();
    $found = $find->get_result()->fetch_assoc();
    $member_id_ref = $found['member_id'] ?? null;
    if ($member_id_ref) {
        $_SESSION['member_id_ref'] = $member_id_ref;
    }
    $find->close();
}

$account = null;
if ($member_id_ref && $has_accounts_table && $has_accounts_table->num_rows > 0) {
    $acc_query = $conn->prepare("SELECT a.*, m.full_name FROM accounts a JOIN members m ON a.member_id = m.member_id WHERE a.member_id = ?");
    $acc_query->bind_param("s", $member_id_ref);
    $acc_query->execute();
    $account = $acc_query->get_result()->fetch_assoc();
    $acc_query->close();

    if (!$account) {
        $acct = $conn->prepare("INSERT IGNORE INTO accounts (member_id, balance) VALUES (?, 0.00)");
        $acct->bind_param("s", $member_id_ref);
        $acct->execute();
        $acct->close();
        $account = ['member_id' => $member_id_ref, 'balance' => 0.00, 'full_name' => ''];
    }
}
?>
<div class="main-content">
    <div class="welcome-section">
        <h3><i class="bi bi-person me-2"></i>Member Dashboard</h3>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Stay updated with society announcements.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon blue"><i class="bi bi-wallet2"></i></div>
                            <div class="card-number"><?php echo $account ? number_format($account['balance'], 2) : '0.00'; ?></div>
                            <div class="card-label">Account Balance (LKR)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="card-icon green"><i class="bi bi-megaphone"></i></div>
                            <div class="card-number">
                                <?php
                                $ann_count = $conn->query("SELECT COUNT(*) as total FROM messages WHERE type='announcement'")->fetch_assoc()['total'];
                                echo $ann_count;
                                ?>
                            </div>
                            <div class="card-label">Announcements</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card table-card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-megaphone me-2"></i>Latest Announcements</h5></div>
                <div class="card-body p-3">
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
                            <p class="mb-1 text-muted small"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
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
        <div class="col-lg-6">
            <div class="card table-card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-envelope me-2"></i>Secretary Messages</h5></div>
                <div class="card-body p-3">
                    <?php
                    $sec = $conn->query("SELECT m.*, u.U_name as author FROM messages m LEFT JOIN userd u ON m.created_by = u.id WHERE m.type='secretary' ORDER BY m.created_at DESC LIMIT 5");
                    if ($sec && $sec->num_rows > 0):
                    ?>
                    <div class="list-group list-group-flush">
                        <?php while ($row = $sec->fetch_assoc()): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h6>
                                <small class="text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                            </div>
                            <p class="mb-1 text-muted small"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center py-3 mb-0">No secretary messages yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-currency-dollar me-2"></i>Treasurer Messages</h5></div>
                <div class="card-body p-3">
                    <?php
                    $tre = $conn->query("SELECT m.*, u.U_name as author FROM messages m LEFT JOIN userd u ON m.created_by = u.id WHERE m.type='treasurer' ORDER BY m.created_at DESC LIMIT 5");
                    if ($tre && $tre->num_rows > 0):
                    ?>
                    <div class="list-group list-group-flush">
                        <?php while ($row = $tre->fetch_assoc()): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h6>
                                <small class="text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                            </div>
                            <p class="mb-1 text-muted small"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center py-3 mb-0">No treasurer messages yet.</p>
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
