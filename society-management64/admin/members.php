<?php
require_once '../includes/db.php';
require_once 'includes/admin_check.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    $reg_no = $_POST['reg_no'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT id FROM users WHERE register_no = ?");
    $check->execute([$reg_no]);
    if ($check->fetch()) {
        $msg = "Error: Register number '$reg_no' already exists!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (register_no, full_name, address, phone, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$reg_no, $name, $address, $phone, $email, $username, $password]);
            $msg = "Member added successfully!";
        } catch (PDOException $e) {
            $msg = "Error: " . $e->getMessage();
        }
    }
}

// Handle edit member form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_member'])) {
    $id = $_POST['id'];
    $reg_no = $_POST['reg_no'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    $check = $pdo->prepare("SELECT id FROM users WHERE register_no = ? AND id != ?");
    $check->execute([$reg_no, $id]);
    if ($check->fetch()) {
        $msg = "Error: Register number '$reg_no' already exists!";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE users SET register_no=?, full_name=?, address=?, phone=?, email=?, username=? WHERE id=?");
            $stmt->execute([$reg_no, $name, $address, $phone, $email, $username, $id]);
            $msg = "Member updated successfully!";
        } catch (PDOException $e) {
            $msg = "Error: " . $e->getMessage();
        }
    }
}

// Fetch member data for editing
$editMember = null;
if (isset($_GET['editid'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['editid']]);
    $editMember = $stmt->fetch();
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY register_no ASC");

include 'includes/header.php';
?>

<div class="row">
    <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold">Members Management</h2>
        <button class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i> Print List</button>
    </div>

    <!-- Add / Edit Member Form -->
    <div class="col-md-4 d-print-none">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header <?php echo $editMember ? 'bg-warning text-dark' : 'bg-primary text-white'; ?>">
                <h5 class="mb-0 fw-bold"><?php echo $editMember ? 'Edit Member' : 'Add New Member'; ?></h5>
            </div>
            <div class="card-body">
                <?php if($msg): ?><div class="alert alert-info"><?php echo $msg; ?></div><?php endif; ?>
                <form method="POST">
                    <?php if($editMember): ?>
                    <input type="hidden" name="id" value="<?php echo $editMember['id']; ?>">
                    <?php endif; ?>
                    <div class="mb-2">
                        <label class="form-label small">Register No</label>
                        <input type="text" name="reg_no" class="form-control" value="<?php echo $editMember ? htmlspecialchars($editMember['register_no']) : ''; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $editMember ? htmlspecialchars($editMember['full_name']) : ''; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Address</label>
                        <input type="text" name="address" class="form-control" value="<?php echo $editMember ? htmlspecialchars($editMember['address']) : ''; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo $editMember ? htmlspecialchars($editMember['phone']) : ''; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $editMember ? htmlspecialchars($editMember['email']) : ''; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $editMember ? htmlspecialchars($editMember['username']) : ''; ?>" required>
                    </div>
                    <?php if(!$editMember): ?>
                    <div class="mb-3">
                        <label class="form-label small">Create Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <?php endif; ?>
                    <?php if($editMember): ?>
                    <button type="submit" name="update_member" class="btn btn-warning w-100">Update Member</button>
                    <a href="members.php" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
                    <?php else: ?>
                    <button type="submit" name="add_member" class="btn btn-primary w-100">Add Member</button>
                    <?php endif; ?>
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
                                <th class="d-print-none">Actions</th>
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
                                <td class="d-print-none">
                                    <a href="members.php?editid=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>
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
