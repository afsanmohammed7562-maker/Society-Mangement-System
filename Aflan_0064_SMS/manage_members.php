<?php
require_once 'includes/auth_check.php';
checkRole(['admin']);
include 'config/database.php';

$page_title = 'Manage Members';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$success_msg = '';
$error_msg = '';

if (isset($_GET['success'])) {
    if ($_GET['success'] == 'added') $success_msg = "Member added successfully!";
    elseif ($_GET['success'] == 'updated') $success_msg = "Member updated successfully!";
    elseif ($_GET['success'] == 'deleted') $success_msg = "Member deleted successfully!";
}

if (isset($_GET['error'])) {
    if ($_GET['error'] == 'delete') $error_msg = "Error deleting member. Please try again.";
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
?>
<div class="main-content">
    <div class="page-header">
        <h4>Manage Members</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Members</li>
            </ol>
        </nav>
    </div>

    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i> <?php echo $success_msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i> <?php echo $error_msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="bi bi-people me-2"></i>All Members</h5>
            <a href="add_member.php" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus me-1"></i> Add New Member
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="membersTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Member ID</th>
                            <th>Full Name</th>
                            <th>NIC</th>
                            <th>Phone</th>
                            <th>Membership</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM members";
                        if (!empty($search)) {
                            $search_param = '%' . $search . '%';
                            $query .= " WHERE full_name LIKE ? OR member_id LIKE ? OR nic_number LIKE ? OR phone_number LIKE ? OR email LIKE ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("sssss", $search_param, $search_param, $search_param, $search_param, $search_param);
                        } else {
                            $stmt = $conn->prepare($query);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                                $photo = !empty($row['profile_photo']) ? 'uploads/' . $row['profile_photo'] : 'assets/img/default-avatar.png';
                        ?>
                            <tr>
                                <td>
                                    <img src="<?php echo $photo; ?>" alt="Photo" class="member-photo" onerror="this.src='https://via.placeholder.com/45?text=U'">
                                </td>
                                <td><span class="member-id-badge"><?php echo htmlspecialchars($row['member_id']); ?></span></td>
                                <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['nic_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['membership_type']); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Active'): ?>
                                        <span class="badge-active">Active</span>
                                    <?php else: ?>
                                        <span class="badge-inactive">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="view_member.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="edit_member.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete_member.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this member?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 d-block text-muted mb-2"></i>
                                    <span class="text-muted">No members found.</span>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php $stmt->close(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
