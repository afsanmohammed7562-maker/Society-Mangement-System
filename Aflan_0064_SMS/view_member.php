<?php
require_once 'includes/auth_check.php';
checkRole(['admin']);
include 'config/database.php';

$page_title = 'View Member';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_members.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: manage_members.php");
    exit();
}

$member = $result->fetch_assoc();
$stmt->close();
?>
<div class="main-content">
    <div class="page-header">
        <h4>Member Details</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage_members.php">Manage Members</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Member</li>
            </ol>
        </nav>
    </div>

    <div class="card form-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-person me-2"></i>Member Profile</span>
            <div>
                <a href="edit_member.php?id=<?php echo $member['id']; ?>" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <?php if ($member['status'] == 'Active'): ?>
                    <span class="badge-active">Active</span>
                <?php else: ?>
                    <span class="badge-inactive">Inactive</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 text-center mb-4">
                    <?php $photo = !empty($member['profile_photo']) ? 'uploads/' . $member['profile_photo'] : 'assets/img/default-avatar.png'; ?>
                    <img src="<?php echo $photo; ?>" alt="Profile Photo" class="member-photo-lg" onerror="this.src='https://via.placeholder.com/120?text=User'">
                    <h5 class="mt-3 mb-1"><?php echo htmlspecialchars($member['full_name']); ?></h5>
                    <span class="member-id-badge"><?php echo htmlspecialchars($member['member_id']); ?></span>
                </div>

                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Full Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($member['full_name']); ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">NIC Number</div>
                            <div class="detail-value"><?php echo htmlspecialchars($member['nic_number']); ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Gender</div>
                            <div class="detail-value"><?php echo htmlspecialchars($member['gender']); ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Date of Birth</div>
                            <div class="detail-value"><?php echo date('d F Y', strtotime($member['date_of_birth'])); ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Phone Number</div>
                            <div class="detail-value"><?php echo htmlspecialchars($member['phone_number']); ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?php echo !empty($member['email']) ? htmlspecialchars($member['email']) : '<span class="text-muted">Not provided</span>'; ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-label">Membership Type</div>
                            <div class="detail-value"><?php echo htmlspecialchars($member['membership_type']); ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-label">Join Date</div>
                            <div class="detail-value"><?php echo date('d F Y', strtotime($member['join_date'])); ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="detail-label">Address</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($member['address'])); ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php if ($member['status'] == 'Active'): ?>
                                    <span class="badge-active">Active</span>
                                <?php else: ?>
                                    <span class="badge-inactive">Inactive</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="detail-label">Member Since</div>
                            <div class="detail-value"><?php echo date('d F Y, h:i A', strtotime($member['created_at'])); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <a href="manage_members.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Members
                </a>
                <a href="edit_member.php?id=<?php echo $member['id']; ?>" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit Member
                </a>
                <a href="delete_member.php?id=<?php echo $member['id']; ?>" class="btn btn-danger btn-delete" onclick="return confirm('Are you sure you want to delete this member?');">
                    <i class="bi bi-trash me-1"></i> Delete Member
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
