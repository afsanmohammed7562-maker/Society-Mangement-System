<?php
require_once '../includes/db.php';
require_once 'includes/admin_check.php';

$stmt = $pdo->query("SELECT SUM(actual_amount - paid_amount) as pending FROM payments");
$pending = $stmt->fetch()['pending'] ?? 0;
$stmt = $pdo->query("SELECT SUM(paid_amount) as total FROM payments");
$total = $stmt->fetch()['total'] ?? 0;
$stmt = $pdo->query("SELECT COUNT(*) as count FROM messages");
$msgs = $stmt->fetch()['count'] ?? 0;
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$members = $stmt->fetch()['count'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as count FROM notices");
$notices = $stmt->fetch()['count'] ?? 0;
$stmt = $pdo->query("SELECT COUNT(*) as count FROM gallery");
$gallery = $stmt->fetch()['count'] ?? 0;
$stmt = $pdo->query("SELECT COUNT(*) as count FROM reports");
$reports = $stmt->fetch()['count'] ?? 0;
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE) AND YEAR(created_at) = YEAR(CURRENT_DATE)");
$newMembers = $stmt->fetch()['count'] ?? 0;

$stmt = $pdo->query("SELECT * FROM notices ORDER BY date_posted DESC LIMIT 5");
$recentNotices = $stmt->fetchAll();

include 'includes/header.php';
?>

<style>
.stat-card {
    border: none;
    border-radius: 20px;
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}
.stat-pending {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
}
.stat-funds {
    background: linear-gradient(135deg, #26de81 0%, #20bf6b 100%);
}
.stat-messages {
    background: linear-gradient(135deg, #4b7bec 0%, #3867d6 100%);
}
.stat-members {
    background: linear-gradient(135deg, #a55eea 0%, #8854d0 100%);
}
.stat-notices {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
.stat-gallery {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
.stat-reports {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}
.stat-newmembers {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
}
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
}
.hero-image {
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
.society-section-title {
    color: #334155;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-left: 1rem;
    border-left: 4px solid #667eea;
}
.overview-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.overview-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}
.overview-card img {
    height: 200px;
    object-fit: cover;
    width: 100%;
}
.overview-card .card-body {
    padding: 1.5rem;
}
.section-divider {
    border-top: 2px dashed #e2e8f0;
    margin: 2.5rem 0;
}
</style>

<div class="dashboard-header">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-2">Admin Dashboard</h2>
            <p class="mb-0 opacity-75">Manage your society members, payments, and communications</p>
        </div>
        <div class="col-lg-4 text-end">
            <i class="fa fa-cogs fa-4x opacity-50"></i>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card stat-card stat-pending text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 opacity-75" style="font-size: 0.85rem;">Total Pending</h6>
                        <h3 class="fw-bold mb-0" style="font-size: 1.5rem;">Rs. <?php echo number_format($pending, 2); ?></h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fa fa-exclamation-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-funds text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 opacity-75" style="font-size: 0.85rem;">Total Funds</h6>
                        <h3 class="fw-bold mb-0" style="font-size: 1.5rem;">Rs. <?php echo number_format($total, 2); ?></h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fa fa-wallet fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-messages text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 opacity-75" style="font-size: 0.85rem;">Messages</h6>
                        <h3 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $msgs; ?></h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fa fa-envelope fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-members text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 opacity-75" style="font-size: 0.85rem;">Members</h6>
                        <h3 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $members; ?></h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fa fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card stat-card stat-notices text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 opacity-75" style="font-size: 0.85rem;">Notices</h6>
                        <h3 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $notices; ?></h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fa fa-bullhorn fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-gallery text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 opacity-75" style="font-size: 0.85rem;">Gallery Items</h6>
                        <h3 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $gallery; ?></h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fa fa-images fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-reports text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 opacity-75" style="font-size: 0.85rem;">Reports</h6>
                        <h3 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $reports; ?></h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fa fa-file-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-newmembers text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-2 opacity-75" style="font-size: 0.85rem;">New Members (Month)</h6>
                        <h3 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $newMembers; ?></h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fa fa-user-plus fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="section-divider">

<h4 class="society-section-title">Society Overview</h4>
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card overview-card h-100">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR3ih-QYMgKnNO2mFXOtx_37vOOeAsjXtZGk7V7F1RnNw&s=10" alt="Society Building">
            <div class="card-body">
                <h5 class="fw-bold" style="color: #334155;">Our Society</h5>
                <p style="color: #64748b; font-size: 0.95rem;">A well-managed residential community with modern amenities and a strong sense of togetherness.</p>
                <a href="members.php" class="btn btn-primary btn-sm">View Members</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card overview-card h-100">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS8z9-pdZILgnXtutkRBmOXNl5dT5_b4c8KiTha6-FDwg&s" alt="Society Events">
            <div class="card-body">
                <h5 class="fw-bold" style="color: #334155;">Community Events</h5>
                <p style="color: #64748b; font-size: 0.95rem;">Celebrating festivals, events, and gatherings that bring our society members together.</p>
                <a href="gallery.php" class="btn btn-primary btn-sm">View Gallery</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card overview-card h-100">
            <img src="meeting.jpg" alt="Society Meeting">
            <div class="card-body">
                <h5 class="fw-bold" style="color: #334155;">Committee Meetings</h5>
                <p style="color: #64748b; font-size: 0.95rem;">Regular committee meetings to discuss society welfare, maintenance, and upcoming projects.</p>
                <a href="secretary.php" class="btn btn-primary btn-sm">View Reports</a>
            </div>
        </div>
    </div>
</div>

<?php if (count($recentNotices) > 0): ?>
<hr class="section-divider">

<h4 class="society-section-title">Recent Notice Board</h4>
<div class="row g-3 mb-5">
    <?php foreach ($recentNotices as $notice): ?>
    <div class="col-md-6">
        <div class="notice-board-item">
            <div class="d-flex justify-content-between align-items-start">
                <h6 class="fw-bold mb-1" style="color: #334155;"><?php echo htmlspecialchars($notice['title']); ?></h6>
                <small style="color: #94a3b8; white-space: nowrap; margin-left: 1rem;"><?php echo date('d M Y', strtotime($notice['date_posted'])); ?></small>
            </div>
            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 0;"><?php echo htmlspecialchars(substr($notice['content'], 0, 150)) . (strlen($notice['content']) > 150 ? '...' : ''); ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="row mt-2">
    <div class="col-12 text-center">
        <div style="border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <img src="../logo.jpeg" class="img-fluid w-100" style="max-height: 350px; object-fit: cover;" alt="Society Image">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
