<?php
require_once 'db_admin.php';


$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    $reg_no = $_POST['reg_no'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (register_no, full_name, address, phone, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$reg_no, $name, $address, $phone, $email, $username, $password]);
        $msg = "Member added successfully!";
    } catch (PDOException $e) {
        $msg = "Error: " . $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY register_no ASC");

include 'includes/header.php';
?>

<div class="row">
    <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold">Members Management</h2>
        <button class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i> Print List</button>
    </div>

    <!-- Add Member Form -->
    <div class="col-md-4 d-print-none">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold">Add New Member</h5>
            </div>
            <div class="card-body">
                <?php if($msg): ?><div class="alert alert-info"><?php echo $msg; ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-2">
                        <label class="form-label small">Register No</label>
                        <input type="text" name="reg_no" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Creaete Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Create Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="add_member" class="btn btn-primary w-100">Add Member</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Members List -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
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
                                <td><?php echo htmlspecialchars($row['register_no']); ?></td>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
