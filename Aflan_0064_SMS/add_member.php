<?php
require_once 'includes/auth_check.php';
checkRole(['admin']);
include 'config/database.php';

$page_title = 'Add Member';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = $_POST['member_id'];
    $full_name = trim($_POST['full_name']);
    $nic_number = trim($_POST['nic_number']);
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = trim($_POST['address']);
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['email']);
    $membership_type = $_POST['membership_type'];
    $join_date = $_POST['join_date'];
    $status = $_POST['status'];
    $created_by = $_SESSION['user_id'];

    $errors = [];

    if (empty($full_name)) $errors[] = "Full name is required";
    if (empty($nic_number)) $errors[] = "NIC number is required";
    if (empty($gender)) $errors[] = "Gender is required";
    if (empty($date_of_birth)) $errors[] = "Date of birth is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($phone_number)) $errors[] = "Phone number is required";
    if (empty($membership_type)) $errors[] = "Membership type is required";
    if (empty($join_date)) $errors[] = "Join date is required";

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    $check = $conn->prepare("SELECT id FROM members WHERE nic_number = ?");
    $check->bind_param("s", $nic_number);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $errors[] = "A member with this NIC number already exists";
    }
    $check->close();

    $profile_photo = '';
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['profile_photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errors[] = "Invalid file type. Allowed: jpg, jpeg, png, gif, webp";
        } else {
            $new_name = uniqid() . '.' . $ext;
            $upload_path = 'uploads/' . $new_name;
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                $profile_photo = $new_name;
            } else {
                $errors[] = "Failed to upload photo";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO members (member_id, full_name, nic_number, gender, date_of_birth, address, phone_number, email, membership_type, join_date, profile_photo, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssi", $member_id, $full_name, $nic_number, $gender, $date_of_birth, $address, $phone_number, $email, $membership_type, $join_date, $profile_photo, $status, $created_by);

        if ($stmt->execute()) {
            $success_msg = "Member added successfully!";
        } else {
            $error_msg = "Error adding member: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_msg = implode("<br>", $errors);
    }
}

$last_member = $conn->query("SELECT member_id FROM members ORDER BY id DESC LIMIT 1");
$last_id = '';
if ($last_member && $last_member->num_rows > 0) {
    $row = $last_member->fetch_assoc();
    $last_id = $row['member_id'];
    $num = intval(substr($last_id, 4)) + 1;
    $member_id_auto = 'SMS-' . str_pad($num, 5, '0', STR_PAD_LEFT);
} else {
    $member_id_auto = 'SMS-00001';
}
?>
<div class="main-content">
    <div class="page-header">
        <h4>Add New Member</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Member</li>
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

    <div class="card form-card">
        <div class="card-header">
            <i class="bi bi-person-plus me-2"></i>Member Registration Form
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Member ID</label>
                        <input type="text" class="form-control" name="member_id" id="memberId" value="<?php echo $member_id_auto; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="full_name" id="fullName" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIC Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nic_number" placeholder="e.g. 123456789V" required>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date_of_birth" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="phone_number" placeholder="e.g. +94 77 123 4567" required>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" placeholder="email@example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Membership Type <span class="text-danger">*</span></label>
                        <select class="form-select" name="membership_type" required>
                            <option value="">Select Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Premium">Premium</option>
                            <option value="Lifetime">Lifetime</option>
                            <option value="Honorary">Honorary</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Join Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="join_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-12">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="address" rows="3" placeholder="Enter full address" required></textarea>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-save me-1"></i> Save Member
                        </button>
                        <a href="manage_members.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>
