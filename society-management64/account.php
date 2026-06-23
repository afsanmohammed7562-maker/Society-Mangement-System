<?php
require_once 'includes/db.php';
require_once 'includes/auth_check.php';

$user_id = $_SESSION['user_id'];
$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_info'])) {
        $full_name = trim($_POST['full_name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $address = trim($_POST['address']);

        $stmt = $pdo->prepare("UPDATE users SET full_name=?, phone=?, email=?, address=? WHERE id=?");
        if ($stmt->execute([$full_name, $phone, $email, $address, $user_id])) {
            $msg = "Profile updated successfully!";
        } else {
            $err = "Failed to update profile.";
        }
    }

    if (isset($_POST['change_password'])) {
        $old_pass = $_POST['old_password'];
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        $stmt = $pdo->prepare("SELECT password FROM users WHERE id=?");
        $stmt->execute([$user_id]);
        $current = $stmt->fetch();

        if (password_verify($old_pass, $current['password'])) {
            if ($new_pass === $confirm_pass) {
                $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
                $stmt->execute([$hash, $user_id]);
                $msg = "Password changed successfully!";
            } else {
                $err = "New passwords do not match.";
            }
        } else {
            $err = "Incorrect old password.";
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if($msg): ?><div class="alert alert-success"><?php echo $msg; ?></div><?php endif; ?>
            <?php if($err): ?><div class="alert alert-danger"><?php echo $err; ?></div><?php endif; ?>

            <div class="card glass-card mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h3 class="fw-bold mb-0">Account Information</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Register Number (ReadOnly)</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['register_no']); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Username (ReadOnly)</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>
                        <button type="submit" name="update_info" class="btn btn-primary">Update Info</button>
                    </form>
                </div>
            </div>

            <div class="card glass-card">
                <div class="card-header bg-transparent border-bottom">
                    <h4 class="fw-bold mb-0">Change Password</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Old Password</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-warning text-white">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$hide_main_footer = true; // Use simple footer
include 'includes/footer.php'; 
?>
