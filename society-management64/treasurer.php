<?php
require_once 'includes/db.php';
require_once 'includes/auth_check.php';

$user_id = $_SESSION['user_id'];

// Reports
$reports_stmt = $pdo->query("SELECT * FROM reports WHERE type='Treasurer' ORDER BY uploaded_at DESC");

// My Payments
$payments_stmt = $pdo->prepare("SELECT * FROM payments WHERE user_id=? ORDER BY created_at DESC");
$payments_stmt->execute([$user_id]);

include 'includes/header.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fa fa-coins text-warning me-2"></i> Treasurer Section</h2>

    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">Monthly Reports</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">My Payment History</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!-- Reports Tab -->
        <div class="tab-pane fade show active" id="reports" role="tabpanel">
            <div class="row">
                <?php while($row = $reports_stmt->fetch()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card glass-card h-100">
                        <div class="card-body text-center">
                            <i class="fa fa-chart-pie fa-3x text-warning mb-3"></i>
                            <h5 class="card-title"><?php echo htmlspecialchars($row['month_year']); ?></h5>
                            <a href="<?php echo htmlspecialchars($row['file_path']); ?>" class="btn btn-sm btn-outline-warning mt-2 w-100" download>Download</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php if($reports_stmt->rowCount() == 0): ?>
                    <p class="text-muted">No reports found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Payments Tab -->
        <div class="tab-pane fade" id="payments" role="tabpanel">
            <div class="card glass-card">
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Month/Year</th>
                                <th>Actual Amt</th>
                                <th>Paid Amt</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($pay = $payments_stmt->fetch()): 
                                $bal = $pay['actual_amount'] - $pay['paid_amount'];
                                $status = $bal <= 0 ? '<span class="badge bg-success">Paid</span>' : '<span class="badge bg-danger">Pending</span>';
                            ?>
                            <tr>
                                <td><?php echo $pay['payment_date'] ? date('d M Y', strtotime($pay['payment_date'])) : '-'; ?></td>
                                <td><?php echo htmlspecialchars($pay['month_year']); ?></td>
                                <td><?php echo number_format($pay['actual_amount'], 2); ?></td>
                                <td><?php echo number_format($pay['paid_amount'], 2); ?></td>
                                <td><?php echo number_format($bal, 2); ?></td>
                                <td><?php echo $status; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
