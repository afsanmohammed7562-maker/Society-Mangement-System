<?php
require_once '../includes/db.php';
require_once 'includes/admin_check.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id=?");
    $stmt->execute([$id]);
    header("Location: messages.php");
    exit();
}

// Fetch messages with user email
$stmt = $pdo->query("SELECT m.*, u.email FROM messages m LEFT JOIN users u ON m.user_id = u.id ORDER BY m.created_at DESC");

include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <h2 class="fw-bold mb-4">User Messages</h2>
        
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $stmt->fetch()): ?>
                            <tr>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td style="max-width: 300px;"><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                                <td>
                                    <?php if($row['email']): ?>
                                    <a href="mailto:<?php echo $row['email']; ?>" class="btn btn-primary btn-sm me-2"><i class="fa fa-envelope"></i> Reply</a>
                                    <?php else: ?>
                                    <button class="btn btn-secondary btn-sm me-2" disabled>No Email</button>
                                    <?php endif; ?>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i> Delete</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
