<?php
require_once 'includes/db.php';
require_once 'includes/auth_check.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT SUM(actual_amount - paid_amount) as pending FROM payments WHERE user_id = ?");
$stmt->execute([$user_id]);
$pending = $stmt->fetch()['pending'] ?? 0;
$stmt = $pdo->query("SELECT SUM(paid_amount) as total FROM payments");
$total_society_amount = $stmt->fetch()['total'] ?? 0;
$stmt = $pdo->query("SELECT * FROM reports WHERE type='Secretary' ORDER BY uploaded_at DESC LIMIT 1");
$sec_report = $stmt->fetch();
$stmt = $pdo->query("SELECT * FROM reports WHERE type='Treasurer' ORDER BY uploaded_at DESC LIMIT 1");
$treas_report = $stmt->fetch();
$members_stmt = $pdo->query("SELECT register_no, full_name, phone, email FROM users ORDER BY register_no ASC");
$notices_stmt = $pdo->query("SELECT * FROM notices ORDER BY date_posted DESC LIMIT 3");
$gallery_stmt = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC LIMIT 4");
include 'includes/header.php';
?>
<style>
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
}
.stats-card-balance {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
.stats-card-fund {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
.section-title {
    color: #2d3748;
    position: relative;
    padding-bottom: 10px;
}
.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
}
.notice-item {
    border-left: 4px solid #667eea;
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0 12px 12px 0;
    transition: all 0.3s ease;
}
.notice-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.report-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    color: white !important;
    text-decoration: none;
    transition: all 0.3s ease;
}
.report-link:hover {
    transform: scale(1.05);
    color: white;
}
.gallery-item {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.gallery-item:hover {
    transform: scale(1.03);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}
</style>

<!-- Hero Section -->
<div class="hero-modern">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Welcome to Your Society</h1>
                <p class="lead text-muted mb-0">Stay connected with your community, view notices, and manage your payments all in one place.</p>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class="card stats-card stats-card-balance h-100 text-center py-4">
                <div class="card-body">
                    <h5 class="card-title text-white-50">Your Pending Amount</h5>
                    <h2 class="display-4 fw-bold text-white">Rs. <?php echo number_format($pending, 2); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card stats-card stats-card-fund h-100 text-center py-4">
                <div class="card-body">
                    <h5 class="card-title text-white-50">Total Society Fund</h5>
                    <h2 class="display-4 fw-bold text-white">Rs. <?php echo number_format($total_society_amount, 2); ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 mb-4" style="border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.08);">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h4 class="section-title fw-bold"><i class="fa fa-users me-2" style="color:#667eea;"></i> Society Members</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr style="background:linear-gradient(135deg,#667eea,#764ba2);color:white;">
                                    <th class="px-4 py-3">Reg No</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Phone</th>
                                    <th class="px-4 py-3">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $members_stmt->fetch()): ?>
                                <tr>
                                    <td class="px-4"><?php echo htmlspecialchars($row['register_no']); ?></td>
                                    <td class="px-4 fw-medium"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td class="px-4"><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td class="px-4"><?php echo htmlspecialchars($row['email']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                    <h4 class="section-title fw-bold mb-0"><i class="fa fa-images me-2" style="color:#667eea;"></i> Recent Gallery</h4>
                    <a href="gallery.php" class="btn btn-sm text-white px-4" style="background:linear-gradient(135deg,#667eea,#764ba2);border-radius:20px;">View All</a>
                </div>
                <?php while($img = $gallery_stmt->fetch()): ?>
                <div class="col-md-3 col-6 mb-3">
                    <div class="gallery-item">
                        <img src="<?php echo htmlspecialchars($img['image_path']); ?>" class="img-fluid w-100" style="height:120px;object-fit:cover;display:block;" alt="Event">
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 mb-4" style="border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.08);">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4" style="color:#2d3748;">Monthly Reports</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3" style="border-radius:12px;background:linear-gradient(135deg,#f8f9fa,#fff);">
                        <span><i class="fa fa-file-invoice me-2" style="color:#4facfe;"></i> Secretary Report</span>
                        <?php if($sec_report): ?>
                            <a href="<?php echo $sec_report['file_path']; ?>" class="report-link" download><i class="fa fa-download"></i></a>
                        <?php else: ?>
                            <small class="text-muted">N/A</small>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3" style="border-radius:12px;background:linear-gradient(135deg,#f8f9fa,#fff);">
                        <span><i class="fa fa-coins me-2" style="color:#f093fb;"></i> Treasurer Report</span>
                        <?php if($treas_report): ?>
                            <a href="<?php echo $treas_report['file_path']; ?>" class="report-link" download><i class="fa fa-download"></i></a>
                        <?php else: ?>
                            <small class="text-muted">N/A</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card border-0 mb-4" style="border-radius:20px;box-shadow:0 10px 30px rgba(0,0,0,0.08);">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="section-title fw-bold"><i class="fa fa-bullhorn me-2" style="color:#f5576c;"></i> Notice Board</h5>
                </div>
                <div class="card-body pb-4">
                    <?php while($notice = $notices_stmt->fetch()): ?>
                    <div class="notice-item">
                        <h6 class="fw-bold mb-1" style="color:#2d3748;"><?php echo htmlspecialchars($notice['title']); ?></h6>
                        <small class="text-muted d-block mb-2"><?php echo date('d M Y', strtotime($notice['date_posted'])); ?></small>
                        <p class="mb-0 small text-secondary"><?php echo nl2br(htmlspecialchars(substr($notice['content'], 0, 100))) . '...'; ?></p>
                    </div>
                    <?php endwhile; ?>
                    <a href="notice_board.php" class="btn btn-link btn-sm text-decoration-none p-0 mt-2" style="color:#667eea;">View All Notices &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
// Show big footer only on home as requested
$hide_main_footer = false; 
include 'includes/footer.php'; 
?>