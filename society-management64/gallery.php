<?php
require_once 'includes/db.php';
require_once 'includes/auth_check.php';

$stmt = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");

include 'includes/header.php';
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fa fa-images text-primary me-2"></i> Gallery</h2>
    <div class="row g-4">
        <?php while($row = $stmt->fetch()): ?>
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm border-0 glass-card overflow-hidden">
                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="card-img-top gallery-img" alt="<?php echo htmlspecialchars($row['title']); ?>">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['title']); ?></h5>
                    <p class="card-text text-muted small"><i class="fa fa-clock me-1"></i> <?php echo date('d M Y', strtotime($row['uploaded_at'])); ?></p>
                    <a href="<?php echo htmlspecialchars($row['image_path']); ?>" class="btn btn-primary w-100" download>Download Image</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
