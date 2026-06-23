<?php
require_once '../includes/db.php';
require_once 'includes/admin_check.php';
$msg = '';
// Generate Bills
if (isset($_POST['generate_bills'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $amount = $_POST['amount'];
    $target_user = $_POST['target_user']; // 'all' or user_id
    $month_year = "$month $year";
    $sql = "SELECT id FROM users";
    $params = [];
    
    if ($target_user !== 'all') {
        $sql .= " WHERE id = ?";
        $params[] = $target_user;
    }
    $users = $pdo->prepare($sql);
    $users->execute($params);
    
    $count = 0;
    while($u = $users->fetch()) {
        // Check if exists
        $check = $pdo->prepare("SELECT id FROM payments WHERE user_id=? AND month_year=?");
        $check->execute([$u['id'], $month_year]);
        if ($check->rowCount() == 0) {
            $stmt = $pdo->prepare("INSERT INTO payments (user_id, month_year, actual_amount, paid_amount) VALUES (?, ?, ?, 0)");
            $stmt->execute([$u['id'], $month_year, $amount]);
            $count++;
        }
    }
    $msg = "Generated bills for $count member(s) for $month_year.";
}
// Fetch all users for dropdown
$all_users = $pdo->query("SELECT id, register_no, full_name FROM users ORDER BY register_no ASC");
// Update Payment
if (isset($_POST['update_payment'])) {
    $id = $_POST['payment_id'];
    $actual = $_POST['actual_amount'];
    $paid = $_POST['paid_amount'];
    $desc = $_POST['description'];
    $date = date('Y-m-d');
    $stmt = $pdo->prepare("UPDATE payments SET actual_amount=?, paid_amount=?, description=?, payment_date=? WHERE id=?");
    $stmt->execute([$actual, $paid, $desc, $date, $id]);
    $msg = "Payment details updated.";
}
// Generate Report / View
$filter_month = $_GET['month'] ?? date('F');
$filter_year = $_GET['year'] ?? date('Y');
$current_month_year = "$filter_month $filter_year";
$sql = "SELECT p.*, u.register_no, u.full_name 
        FROM payments p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.month_year = ? 
        ORDER BY u.register_no ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$current_month_year]);
include 'includes/header.php';
?>
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-bold">Treasurer Management</h2>
        <?php if($msg): ?><div class="alert alert-success"><?php echo $msg; ?></div><?php endif; ?>
    </div>
    <!-- Actions -->
    <div class="col-md-4 d-print-none">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0 fw-bold">Generate Monthly Bills</h6>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Select Member</label>
                        <select name="target_user" class="form-select">
                            <option value="all">All Members</option>
                            <?php while($usr = $all_users->fetch()): ?>
                                <option value="<?php echo $usr['id']; ?>">
                                    <?php echo htmlspecialchars($usr['full_name'] . " (" . $usr['register_no'] . ")"); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <select name="month" class="form-select">
                                <?php 
                                $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                                foreach($months as $m) echo "<option value='$m'>$m</option>";
                                ?>
                            </select>
                        </div>
                        <div class="col-6 mb-2">
                            <select name="year" class="form-select">
                                <?php for($y=2024; $y<=2030; $y++) echo "<option value='$y'>$y</option>"; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (Rs.)</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>
                    <button type="submit" name="generate_bills" class="btn btn-warning w-100">Generate Bills</button>
                </form>
            </div>
        </div>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0 fw-bold">Upload Monthly Treasurer Report</h6>
            </div>
            <div class="card-body">
                 <!-- Reusing logic for report upload in secretary page, but adding here as requested -->
                 <form action="secretary.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="Treasurer">
                    <div class="mb-2">
                        <input type="text" name="month_year" class="form-control" placeholder="Month Year (e.g. Jan 2024)" required>
                    </div>
                    <div class="mb-2">
                        <input type="file" name="report_file" class="form-control" required>
                    </div>
                    <button type="submit" name="upload_report" class="btn btn-success w-100">Upload Report</button>
                 </form>
            </div>
        </div>
    </div>
    <!-- Report Table -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Records for <?php echo htmlspecialchars($current_month_year); ?></h5>
                <form class="d-flex d-print-none" method="GET">
                    <select name="month" class="form-select form-select-sm me-2">
                         <option value="<?php echo $filter_month; ?>"><?php echo $filter_month; ?></option>
                         <?php foreach($months as $m) if($m!=$filter_month) echo "<option value='$m'>$m</option>"; ?>
                    </select>
                    <select name="year" class="form-select form-select-sm me-2">
                         <option value="<?php echo $filter_year; ?>"><?php echo $filter_year; ?></option>
                         <?php for($y=2024; $y<=2030; $y++) if($y!=$filter_year) echo "<option value='$y'>$y</option>"; ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Go</button>
                    <button type="button" class="btn btn-outline-dark btn-sm ms-2" onclick="window.print()"><i class="fa fa-print"></i></button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Reg No</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Actual</th>
                                <th>Paid</th>
                                <th>Status</th>
                                <th class="d-print-none">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_actual = 0;
                            $total_paid = 0;
                            while($row = $stmt->fetch()): 
                                $bal = $row['actual_amount'] - $row['paid_amount'];
                                $total_actual += $row['actual_amount'];
                                $total_paid += $row['paid_amount'];
                            ?>
                            <tr>
                                <td><?php echo $row['register_no']; ?></td>
                                <td><?php echo $row['full_name']; ?></td>
                                <td><?php echo $row['payment_date']; ?></td>
                                <td><?php echo $row['actual_amount']; ?></td>
                                <td><?php echo $row['paid_amount']; ?></td>
                                <td><?php echo $bal <= 0 ? '<span class="badge bg-success">Paid</span>' : '<span class="badge bg-danger">Pending</span>'; ?></td>
                                <td class="d-print-none">
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#payModal<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></button>
                                </td>
                            </tr>
                            
                            <!-- Payment Modal -->
                            <div class="modal fade" id="payModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Payment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="payment_id" value="<?php echo $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label>Actual Amount</label>
                                                    <input type="number" name="actual_amount" class="form-control" value="<?php echo $row['actual_amount']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Paid Amount</label>
                                                    <input type="number" name="paid_amount" class="form-control" value="<?php echo $row['paid_amount']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Description</label>
                                                    <input type="text" name="description" class="form-control" value="<?php echo $row['description']; ?>">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="update_payment" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            <tr class="fw-bold">
                                <td colspan="3">Total</td>
                                <td><?php echo number_format($total_actual, 2); ?></td>
                                <td><?php echo number_format($total_paid, 2); ?></td>
                                <td colspan="2"></td>
                            </tr>
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