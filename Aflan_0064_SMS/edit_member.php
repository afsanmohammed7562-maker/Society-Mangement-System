<?php
require_once 'includes/auth_check.php';
checkRole(['admin']);
include 'config/database.php';

$page_title = 'Edit Member';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$success_msg = '';
$error_msg = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_members.php");
    exit();
}

$id = intval($_GET['id']);
$member = $conn->prepare("SELECT * FROM members WHERE id = ?");
$member->bind_param("i", $id);
$member->execute();
$result = $member->get_result();

if ($result->num_rows == 0) {
    header("Location: manage_members.php");
    exit();
}

$row = $result->fetch_assoc();
$member->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    $check = $conn->prepare("SELECT id FROM members WHERE nic_number = ? AND id != ?");
    $check->bind_param("si", $nic_number, $id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $errors[] = "Another member with this NIC number already exists";
    }
    $check->close();

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
                if (!empty($row['profile_photo']) && file_exists('uploads/' . $row['profile_photo'])) {
                    unlink('uploads/' . $row['profile_photo']);
                }
                $profile_photo = $new_name;
            } else {
                $errors[] = "Failed to upload photo";
            }
        }
    } else {
        $profile_photo = $row['profile_photo'];
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE members SET full_name=?, nic_number=?, gender=?, date_of_birth=?, address=?, phone_number=?, email=?, membership_type=?, join_date=?, profile_photo=?, status=? WHERE id=?");
        $stmt->bind_param("sssssssssssi", $full_name, $nic_number, $gender, $date_of_birth, $address, $phone_number, $email, $membership_type, $join_date, $profile_photo, $status, $id);

        if ($stmt->execute()) {
            $success_msg = "Member updated successfully!";
            $row['full_name'] = $full_name;
            $row['nic_number'] = $nic_number;
            $row['gender'] = $gender;
            $row['date_of_birth'] = $date_of_birth;
            $row['address'] = $address;
            $row['phone_number'] = $phone_number;
            $row['email'] = $email;
            $row['membership_type'] = $membership_type;
            $row['join_date'] = $join_date;
            $row['profile_photo'] = $profile_photo;
            $row['status'] = $status;
        } else {
            $error_msg = "Error updating member: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_msg = implode("<br>", $errors);
    }
}
?>
<div class="main-content">
    <div class="page-header">
        <h4>Edit Member</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="manage_members.php">Manage Members</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Member</li>
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
            <i class="bi bi-pencil-square me-2"></i>Edit Member - <?php echo htmlspecialchars($row['member_id']); ?>
        </div>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-4">
                        <?php $photo = !empty($row['profile_photo']) ? 'uploads/' . $row['profile_photo'] : 'assets/img/default-avatar.png'; ?>
                        <div class="photo-upload-wrapper mb-4">
                            <img src="<?php echo $photo; ?>" alt="Profile Photo" id="photoPreview" onerror="this.src='https://via.placeholder.com/150?text=Photo'">
                            <div class="upload-overlay" onclick="document.getElementById('profilePhoto').click()">
                                <i class="bi bi-camera me-1"></i> Change
                            </div>
                            <input type="file" name="profile_photo" id="profilePhoto" accept="image/*" style="display:none">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Member ID</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['member_id']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="Active" <?php echo $row['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo $row['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIC Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nic_number" value="<?php echo htmlspecialchars($row['nic_number']); ?>" required>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo $row['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $row['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo $row['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date_of_birth" value="<?php echo $row['date_of_birth']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($row['phone_number']); ?>" required>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Membership Type <span class="text-danger">*</span></label>
                        <select class="form-select" name="membership_type" required>
                            <option value="">Select Type</option>
                            <option value="Regular" <?php echo $row['membership_type'] == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                            <option value="Premium" <?php echo $row['membership_type'] == 'Premium' ? 'selected' : ''; ?>>Premium</option>
                            <option value="Lifetime" <?php echo $row['membership_type'] == 'Lifetime' ? 'selected' : ''; ?>>Lifetime</option>
                            <option value="Honorary" <?php echo $row['membership_type'] == 'Honorary' ? 'selected' : ''; ?>>Honorary</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Join Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="join_date" value="<?php echo $row['join_date']; ?>" required>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-12">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="address" rows="3" required><?php echo htmlspecialchars($row['address']); ?></textarea>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-save me-1"></i> Update Member
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
