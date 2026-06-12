<?php
require_once 'includes/db.php';
require_once 'includes/auth_check.php';

$stmt = $pdo->query("SELECT * FROM reports WHERE type='Secretary' ORDER BY uploaded_at DESC");

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fa fa-file-invoice text-info me-2"></i> Secretary Reports</h2>
    </div>

    <div class="row">
        <?php while($row = $stmt->fetch()): ?>
        <div class="col-md-4 mb-4">
            <div class="card glass-card h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fa fa-file-lines fa-4x text-muted mb-3"></i>
                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['month_year']); ?></h5>
                    <p class="text-muted small">Uploaded: <?php echo date('d M Y', strtotime($row['uploaded_at'])); ?></p>
                    <a href="<?php echo htmlspecialchars($row['file_path']); ?>" class="btn btn-outline-primary mt-auto" download>Download Report</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        
        <?php if($stmt->rowCount() == 0): ?>
        <div class="col-12 text-center text-muted">
            <p>No reports available.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
