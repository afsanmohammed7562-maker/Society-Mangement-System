<?php
require_once 'includes/auth_check.php';
checkRole(['admin']);
include 'config/database.php';

$page_title = 'Create Member Login';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$success_msg = '';
$error_msg = '';

$has_member_id_ref = $conn->query("SHOW COLUMNS FROM userd LIKE 'member_id_ref'");
$member_id_ref_exists = $has_member_id_ref && $has_member_id_ref->num_rows > 0;

if ($member_id_ref_exists) {
    $members_list = $conn->query("
        SELECT m.member_id, m.full_name FROM members m 
        WHERE m.status='Active' 
        ORDER BY m.full_name
    ");
} else {
    $members_list = $conn->query("
        SELECT m.member_id, m.full_name FROM members m 
        WHERE m.status='Active' 
        ORDER BY m.full_name
    ");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = $_POST['member_id'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = 'member';

    $errors = [];
    if (empty($member_id)) $errors[] = "Please select a member.";
    if (empty($username)) $errors[] = "Username is required.";
    if (empty($password) || strlen($password) < 4) $errors[] = "Password must be at least 4 characters.";

    if ($member_id_ref_exists) {
        $dup = $conn->prepare("SELECT id FROM userd WHERE member_id_ref = ?");
        $dup->bind_param("s", $member_id);
        $dup->execute();
        if ($dup->get_result()->num_rows > 0) {
            $errors[] = "This member already has a login account.";
        }
        $dup->close();
    }

    $check = $conn->prepare("SELECT id FROM userd WHERE U_name = ?");
    $check->bind_param("s", $username);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $errors[] = "Username already taken. Choose another.";
    }
    $check->close();

    if (empty($errors)) {
        $email = $username . '@member.sms';
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO userd (U_name, U_email, U_pass, role, member_id_ref) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashed, $role, $member_id);
        if ($stmt->execute()) {
            $member_name = '';
            $name_q = $conn->prepare("SELECT full_name FROM members WHERE member_id = ?");
            $name_q->bind_param("s", $member_id);
            $name_q->execute();
            $member_name = $name_q->get_result()->fetch_assoc()['full_name'] ?? '';
            $name_q->close();

            $acct_check = $conn->prepare("SELECT id FROM accounts WHERE member_id = ?");
            $acct_check->bind_param("s", $member_id);
            $acct_check->execute();
            if ($acct_check->get_result()->num_rows == 0) {
                $acct = $conn->prepare("INSERT INTO accounts (member_id, balance) VALUES (?, 0.00)");
                $acct->bind_param("s", $member_id);
                $acct->execute();
                $acct->close();
            }
            $acct_check->close();

            $success_msg = "Login created for <strong>{$member_name}</strong>!<br><br>
                <strong>Username:</strong> {$username}<br>
                <strong>Password:</strong> {$password}<br>
                <strong>Role:</strong> Member";
        } else {
            $error_msg = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_msg = implode("<br>", $errors);
    }
}
?>
<div class="main-content">
    <div class="page-header">
        <h4><i class="bi bi-person-plus-fill me-2"></i>Create Member Login</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Create Member Login</li>
            </ol>
        </nav>
    </div>

    <?php if ($success_msg): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo $success_msg; ?>
        </div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card form-card">
                <div class="card-header"><i class="bi bi-key me-2"></i>New Member Login</div>
                <div class="card-body">
                    <p class="text-muted mb-4">Select an active member and create their login credentials. This will also create a financial account for them.</p>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Select Member <span class="text-danger">*</span></label>
                            <select class="form-select" name="member_id" required>
                                <option value="">-- Choose a member --</option>
                                <?php 
                                if ($members_list && $members_list->num_rows > 0):
                                    while ($m = $members_list->fetch_assoc()): 
                                ?>
                                <option value="<?php echo htmlspecialchars($m['member_id']); ?>">
                                    <?php echo htmlspecialchars($m['full_name'] . ' (' . $m['member_id'] . ')'); ?>
                                </option>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <option value="" disabled>--- No members found ---</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" placeholder="e.g. john_doe" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="password" id="passwordField" value="member123" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('passwordField').value = Math.random().toString(36).slice(2, 10);">Generate</button>
                            </div>
                            <small class="text-muted">Default: member123</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-person-plus-fill me-1"></i> Create Login</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card table-card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-people me-2"></i>Existing Member Logins</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Member</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $existing = $conn->query("
                                    SELECT u.U_name, m.full_name, u.created_at 
                                    FROM userd u 
                                    JOIN members m ON u.member_id_ref = m.member_id 
                                    WHERE u.role='member' 
                                    ORDER BY u.created_at DESC
                                ");
                                if ($existing && $existing->num_rows > 0):
                                    while ($e = $existing->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($e['U_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($e['full_name']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($e['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr><td colspan="3" class="text-center py-3 text-muted">No member logins yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$conn->close();
include 'includes/footer.php';
?>
