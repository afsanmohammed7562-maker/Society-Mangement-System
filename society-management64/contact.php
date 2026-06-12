<?php
require_once 'includes/db.php';
require_once 'includes/auth_check.php';

$user_id = $_SESSION['user_id'];
$msg_sent = false;

// Fetch user details to pre-fill
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $message = trim($_POST['message']);

    $stmt = $pdo->prepare("INSERT INTO messages (user_id, name, phone, username, message) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $name, $phone, $username, $message])) {
        $msg_sent = true;
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card glass-card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fa fa-paper-plane fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold">Contact Admin</h2>
                        <p class="text-muted">Have a question or issue? Send us a message.</p>
                    </div>

                    <?php if($msg_sent): ?>
                        <div class="alert alert-success text-center">Message sent successfully!</div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
