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

<div class="row mt-4">
    <div class="col-12 text-center">
        <div style="border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <img src="../logo.jpeg" class="img-fluid w-100" style="max-height: 350px; object-fit: cover;" alt="Society Image">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
