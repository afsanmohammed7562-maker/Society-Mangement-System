<?php
require_once 'includes/auth_check.php';
checkRole(['admin']);
include 'config/database.php';

$page_title = 'Manage Users';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $role = $_POST['role'];
        $member_id_ref = !empty($_POST['member_id_ref']) ? trim($_POST['member_id_ref']) : null;

        $errors = [];
        if (empty($username)) $errors[] = "Username is required";
        if (empty($email)) $errors[] = "Email is required";
        if (empty($password) || strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
        if (empty($role)) $errors[] = "Role is required";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";

        if (empty($errors)) {
            $check = $conn->prepare("SELECT id FROM userd WHERE U_name = ? OR U_email = ?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $error_msg = "Username or email already exists.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO userd (U_name, U_email, U_pass, role, member_id_ref) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $username, $email, $hashed, $role, $member_id_ref);
                if ($stmt->execute()) {
                    $success_msg = "User '{$username}' created successfully as {$role}.";
                } else {
                    $error_msg = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
            $check->close();
        } else {
            $error_msg = implode("<br>", $errors);
        }
    } elseif ($_POST['action'] == 'delete' && isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
        if ($user_id == $_SESSION['user_id']) {
            $error_msg = "You cannot delete your own account.";
        } else {
            $stmt = $conn->prepare("DELETE FROM userd WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $success_msg = "User deleted successfully.";
            } else {
                $error_msg = "Error deleting user.";
            }
            $stmt->close();
        }
    } elseif ($_POST['action'] == 'reset_password' && isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
        $new_password = $_POST['new_password'] ?? 'password123';
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE userd SET U_pass = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $user_id);
        if ($stmt->execute()) {
            $success_msg = "Password reset successfully. New password: {$new_password}";
        } else {
            $error_msg = "Error resetting password.";
        }
        $stmt->close();
    }
}
?>
<div class="main-content">
    <div class="page-header">
        <h4>Manage Users</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Manage Users</li>
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
        <div class="col-lg-4">
            <div class="card form-card">
                <div class="card-header"><i class="bi bi-person-plus me-2"></i>Create New User</div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="password" value="password123" required>
                            <small class="text-muted">Default: password123</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="member">Member</option>
                                <option value="secretary">Secretary</option>
                                <option value="treasurer">Treasurer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link to Member (optional)</label>
                            <select class="form-select" name="member_id_ref">
                                <option value="">-- None --</option>
                                <?php
                                $members = $conn->query("SELECT member_id, full_name FROM members WHERE status='Active' ORDER BY full_name");
                                while ($m = $members->fetch_assoc()):
                                ?>
                                <option value="<?php echo htmlspecialchars($m['member_id']); ?>"><?php echo htmlspecialchars($m['full_name'] . ' (' . $m['member_id'] . ')'); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-person-plus me-1"></i> Create User</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card table-card">
                <div class="card-header"><h5 class="mb-0"><i class="bi bi-people me-2"></i>All System Users</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Linked Member</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = $conn->query("SELECT u.*, m.full_name as member_name FROM userd u LEFT JOIN members m ON u.member_id_ref = m.member_id ORDER BY u.id");
                                while ($u = $users->fetch_assoc()):
                                $roleBadge = match($u['role']) {
                                    'admin' => 'danger',
                                    'secretary' => 'warning text-dark',
                                    'treasurer' => 'success',
                                    default => 'primary'
                                };
                                ?>
                                <tr>
                                    <td><?php echo $u['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($u['U_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($u['U_email']); ?></td>
                                    <td><span class="badge bg-<?php echo $roleBadge; ?>"><?php echo ucfirst($u['role']); ?></span></td>
                                    <td><?php echo $u['member_name'] ? htmlspecialchars($u['member_name']) : '<span class="text-muted">-</span>'; ?></td>
                                    <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <form method="POST" action="" class="d-inline" onsubmit="return confirm('Reset password for <?php echo htmlspecialchars($u['U_name']); ?>?');">
                                                <input type="hidden" name="action" value="reset_password">
                                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" class="btn btn-warning btn-sm" title="Reset Password"><i class="bi bi-key"></i></button>
                                            </form>
                                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                            <form method="POST" action="" class="d-inline" onsubmit="return confirm('Delete user <?php echo htmlspecialchars($u['U_name']); ?>?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="bi bi-trash"></i></button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
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
