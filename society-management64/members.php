<?php
require_once 'includes/db.php';
require_once 'includes/auth_check.php';

$stmt = $pdo->query("SELECT register_no, full_name, phone, email, address FROM users ORDER BY register_no ASC");

include 'includes/header.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fa fa-users text-primary me-2"></i> Society Members Directory</h2>
    
    <div class="card glass-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Reg No</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $stmt->fetch()): ?>
                        <tr>
                            <td class="fw-bold"><?php echo htmlspecialchars($row['register_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
