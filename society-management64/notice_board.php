<?php
require_once 'includes/db.php';
require_once 'includes/auth_check.php';

$stmt = $pdo->query("SELECT * FROM notices ORDER BY date_posted DESC");

include 'includes/header.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fa fa-bullhorn text-danger me-2"></i> Notice Board</h2>
    
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <?php while($row = $stmt->fetch()): ?>
            <div class="card glass-card mb-4 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="card-title fw-bold text-dark mb-0"><?php echo htmlspecialchars($row['title']); ?></h4>
                        <span class="badge bg-danger rounded-pill"><i class="fa fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($row['date_posted'])); ?></span>
                    </div>
                    <p class="card-text text-secondary fs-5"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
