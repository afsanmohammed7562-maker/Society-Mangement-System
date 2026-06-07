<?php
require_once 'includes/auth_check.php';
checkRole(['admin', 'treasurer']);
include 'config/database.php';

$page_title = 'Financial Records';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = $_POST['member_id'];
    $new_balance = floatval($_POST['balance']);

    $check = $conn->prepare("SELECT id FROM accounts WHERE member_id = ?");
    $check->bind_param("s", $member_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE accounts SET balance = ? WHERE member_id = ?");
        $stmt->bind_param("ds", $new_balance, $member_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO accounts (member_id, balance) VALUES (?, ?)");
        $stmt->bind_param("sd", $member_id, $new_balance);
    }
    if ($stmt->execute()) {
        $success_msg = "Balance updated for this member!";
    } else {
        $error_msg = "Error updating balance.";
    }
    $stmt->close();
    $check->close();
}
?>
<div class="main-content">
    <div class="page-header">
        <h4>Financial Records</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $_SESSION['role']; ?>_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Financial Records</li>
            </ol>
        </nav>
    </div>

    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i> <?php echo $success_msg; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle me-2"></i> <?php echo $error_msg; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5">
            <div class="card form-card mb-4">
                <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Update Member Balance</div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Select a member and set their account balance. Creates account if none exists.</p>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Select Member <span class="text-danger">*</span></label>
                            <select class="form-select" name="member_id" required>
                                <option value="">-- Select Member --</option>
                                <?php
                                $members = $conn->query("SELECT m.member_id, m.full_name FROM members m WHERE m.status='Active' ORDER BY m.full_name");
                                if ($members && $members->num_rows > 0):
                                    while ($m = $members->fetch_assoc()):
                                ?>
                                <option value="<?php echo htmlspecialchars($m['member_id']); ?>"><?php echo htmlspecialchars($m['full_name'] . ' (' . $m['member_id'] . ')'); ?></option>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <option value="" disabled>--- No members found ---</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Balance (LKR)</label>
                            <input type="number" step="0.01" class="form-control" name="balance" value="0.00" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i> Save Balance</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card table-card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-wallet2 me-2"></i>Member Account Balances</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="accountsTable">
                            <thead>
                                <tr>
                                    <th>Member ID</th>
                                    <th>Full Name</th>
                                    <th>Balance (LKR)</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $accounts = $conn->query("SELECT a.*, m.full_name FROM accounts a JOIN members m ON a.member_id = m.member_id ORDER BY m.full_name");
                                if ($accounts && $accounts->num_rows > 0):
                                    while ($a = $accounts->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><span class="member-id-badge"><?php echo htmlspecialchars($a['member_id']); ?></span></td>
                                    <td><strong><?php echo htmlspecialchars($a['full_name']); ?></strong></td>
                                    <td><strong>LKR <?php echo number_format($a['balance'], 2); ?></strong></td>
                                    <td><?php echo date('d M Y h:i A', strtotime($a['updated_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editBalanceModal" data-member-id="<?php echo htmlspecialchars($a['member_id']); ?>" data-member-name="<?php echo htmlspecialchars($a['full_name']); ?>" data-balance="<?php echo $a['balance']; ?>">
                                            <i class="bi bi-pencil"></i> Update
                                        </button>
                                    </td>
                                </tr>
                                <?php
                                    endwhile;
                                else:
                                ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No accounts found. Create one to get started.</td></tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary">
                                    <th colspan="2" class="text-end">Total:</th>
                                    <th colspan="3">
                                        <?php
                                        $total = $conn->query("SELECT COALESCE(SUM(balance), 0) as total FROM accounts")->fetch_assoc()['total'];
                                        echo 'LKR ' . number_format($total, 2);
                                        ?>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editBalanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title">Update Account Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="member_id" id="editMemberId">
                    <p>Member: <strong id="editMemberName"></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Balance (LKR)</label>
                        <input type="number" step="0.01" class="form-control" name="balance" id="editBalance" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Balance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
