<?php
include 'db.php';

$message = "";
$message_class = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $reg_no = mysqli_real_escape_string($conn, $_POST['reg_no']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

   
    $check_query = "SELECT * FROM members WHERE reg_no = '$reg_no'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Update existing member details
        $sql = "UPDATE members SET username=?, fullname=?, phone=?, email=?, address=? WHERE reg_no=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $username, $fullname, $phone, $email, $address, $reg_no);
        if (mysqli_stmt_execute($stmt)) {
            $message = "Account information updated successfully!";
            $message_class = "success";
        } else {
            $message = "Error updating details: " . mysqli_error($conn);
            $message_class = "error";
        }
    } else {
        // Insert new member details if registration number does not exist
        $sql = "INSERT INTO members (reg_no, username, fullname, phone, email, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $reg_no, $username, $fullname, $phone, $email, $address);
        if (mysqli_stmt_execute($stmt)) {
            $message = "New account profile created successfully!";
            $message_class = "success";
        } else {
            $message = "Error creating profile: " . mysqli_error($conn);
            $message_class = "error";
        }
    }
}

// Fetch member data if reg_no is selected in GET or POST
$reg_no = $_GET['reg_no'] ?? ($_POST['reg_no'] ?? '');

// If no reg_no is specified, retrieve the first member as default demo profile
if (empty($reg_no)) {
    $first_member_result = mysqli_query($conn, "SELECT reg_no FROM members LIMIT 1");
    if ($first_row = mysqli_fetch_assoc($first_member_result)) {
        $reg_no = $first_row['reg_no'];
    }
}

$member = null;
if (!empty($reg_no)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM members WHERE reg_no = ?");
    mysqli_stmt_bind_param($stmt, "s", $reg_no);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $member = mysqli_fetch_assoc($result);
}

// Fetch all members list for profile selector dropdown
$members_list_result = mysqli_query($conn, "SELECT reg_no, fullname FROM members ORDER BY fullname ASC");
$all_members = [];
while ($m_row = mysqli_fetch_assoc($members_list_result)) {
    $all_members[] = $m_row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Information</title>

  <link rel="stylesheet" href="Account.css">
  <link rel="stylesheet" href="footer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    /* Styling adjustments to blend with page layout */
    .container {
      min-height: calc(100vh - 250px);
      flex-direction: column;
      align-items: center;
      margin-top: 30px;
    }

    .selector-box {
      width: 700px;
      background: #fff;
      padding: 15px 35px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.03);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .selector-box label {
      margin-bottom: 0;
      font-weight: 600;
      color: #333;
    }

    .selector-box select {
      flex: 1;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      outline: none;
      font-family: 'Poppins', sans-serif;
    }

    .alert {
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 25px;
      font-size: 14px;
      font-weight: 500;
    }

    .alert-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    @media(max-width: 768px) {
      .selector-box {
        width: 95%;
        flex-direction: column;
        align-items: stretch;
      }
    }
  </style>
</head>
<body>

  <!-- Reusable Navigation Bar -->
  <?php include 'navbar.php'; ?>

  <div class="container">

    <!-- Profile Selector to choose who to update -->
    <div class="selector-box">
      <label for="profileSelect"><i class="fa-solid fa-user-gear" style="color: #4a4ae6; font-size: 18px;"></i> Select Profile to Manage:</label>
      <select id="profileSelect" onchange="window.location.href='account.php?reg_no=' + this.value">
        <option value="">-- Choose Member Profile --</option>
        <?php foreach ($all_members as $m): ?>
          <option value="<?php echo htmlspecialchars($m['reg_no']); ?>" <?php echo ($reg_no == $m['reg_no']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($m['fullname'] . ' (' . $m['reg_no'] . ')'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="card">
      <h2>Account Information</h2>

      <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $message_class; ?>">
          <i class="<?php echo ($message_class == 'success') ? 'fa-solid fa-circle-check' : 'fa-solid fa-triangle-exclamation'; ?>"></i>
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form action="account.php" method="POST">

        <div class="row">
          <div class="col form-group">
            <label>Registration No (Read-Only for edits)</label>
            <input type="text" name="reg_no" value="<?php echo htmlspecialchars($member['reg_no'] ?? ''); ?>" required <?php echo !empty($member) ? 'readonly style="background: #e9ecef; cursor: not-allowed;"' : ''; ?>>
          </div>

          <div class="col form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($member['username'] ?? ''); ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="fullname" value="<?php echo htmlspecialchars($member['fullname'] ?? ''); ?>" required>
        </div>

        <div class="row">
          <div class="col form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($member['phone'] ?? ''); ?>" required>
          </div>

          <div class="col form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($member['email'] ?? ''); ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label>Address</label>
          <textarea name="address" required><?php echo htmlspecialchars($member['address'] ?? ''); ?></textarea>
        </div>

        <button type="submit" name="update" class="btn">
          <i class="fa-solid fa-floppy-disk"></i> Update Info
        </button>

      </form>
    </div>

  </div>

  <!-- Reusable Footer -->
  <?php include 'footer.php'; ?>

</body>
</html>
