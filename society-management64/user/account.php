<?php include 'user_header.php'; ?>
<?php 
include '../includes/db.php';
$uid = $_SESSION['user_id'];
$msg = "";
$error = "";

// Fetch Account Info
$sql = "SELECT * FROM users WHERE id='$uid'";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);

// Update Profile
if(isset($_POST['update_profile'])){
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone']; // Assuming phone field update
    
    mysqli_query($conn, "UPDATE users SET fullname='$fullname', phone='$phone' WHERE id='$uid'");
    // Refresh
    header("Location: account.php");
}

// Change Password
if(isset($_POST['change_pass'])){
    $old = $_POST['old_pass'];
    $new = $_POST['new_pass'];
    
    if(password_verify($old, $user['password'])){
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$new_hash' WHERE id='$uid'");
        $msg = "Password changed successfully.";
    } else {
        $error = "Incorrect old password.";
    }
}
?>

<div class="profile-card">
    <div class="profile-avatar"><i class="fa fa-user"></i></div>
    <h2><?php echo $user['fullname']; ?></h2>
    <p>Reg No: <?php echo $user['reg_no']; ?></p>
    <p>Username: <?php echo $user['username']; ?></p>
    
    <button onclick="document.getElementById('editForm').style.display='block'" style="margin-top:1rem;">Edit Profile</button>
    <button onclick="document.getElementById('passForm').style.display='block'" style="margin-top:0.5rem; background:var(--accent-color);">Change Password</button>
    
    <?php if($msg) echo "<p style='color:green'>$msg</p>"; ?>
    <?php if($error) echo "<p style='color:red'>$error</p>"; ?>

    <div id="editForm" style="display:none; margin-top:1rem; text-align:left;">
        <hr>
        <h3>Edit Details</h3>
        <form method="POST">
            <div class="form-group"><label>Full Name</label><input type="text" name="fullname" value="<?php echo $user['fullname']; ?>"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" value="<?php echo $user['phone']; ?>"></div>
            <button type="submit" name="update_profile">Save Changes</button>
        </form>
    </div>

    <div id="passForm" style="display:none; margin-top:1rem; text-align:left;">
        <hr>
        <h3>Change Password</h3>
        <form method="POST">
            <div class="form-group"><input type="password" name="old_pass" placeholder="Old Password" required></div>
            <div class="form-group"><input type="password" name="new_pass" placeholder="New Password" required></div>
            <button type="submit" name="change_pass">Update Password</button>
        </form>
    </div>
</div>

</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>
