<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Society Members Directory</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="test.css">
  <link rel="stylesheet" href="footer.css">
</head>
        
<body>

<?php include 'navbar.php'; ?>
     
<div class="container">

  <div class="title" style="margin-top: 30px;">
    <i class="fa-solid fa-users"></i>
    <h1>Society Members Directory</h1>
  </div>

  <!-- FORM -->
  <div class="form-box">
    <h2 style="color: #0f1f4b; margin-bottom: 20px; font-size: 22px;">Add New Member</h2>
    <form action="insert.php" method="POST">

      <div class="form-grid">

        <div class="input-box">
          <label>Registration No</label>
          <input type="text" name="reg_no" placeholder="e.g. REG004" required>
        </div>

        <div class="input-box">
          <label>Username</label>
          <input type="text" name="username" placeholder="e.g. mark_twain" required>
        </div>

        <div class="input-box">
          <label>Full Name</label>
          <input type="text" name="fullname" placeholder="e.g. Mark Twain" required>
        </div>

        <div class="input-box">
          <label>Phone</label>
          <input type="text" name="phone" placeholder="e.g. +94 771234567" required>
        </div>

        <div class="input-box">
          <label>Email</label>
          <input type="email" name="email" placeholder="e.g. mark@example.com" required>
        </div>

        <div class="input-box">
          <label>Address</label>
          <input type="text" name="address" placeholder="e.g. 123 Pine St, Greenwood" required>
        </div>

      </div>

      <div class="btn-box">
        <button type="submit" name="submit">Register Member</button>
      </div>

    </form>

  </div>


  <!-- MEMBERS TABLE -->
  <div class="table-box">
    <h2 style="color: #0f1f4b; margin-bottom: 20px; font-size: 22px;">Registered Members</h2>
    <table>

      <thead>
        <tr>
          <th>Reg No</th>
          <th>Name</th>
          <th>Address</th>
          <th>Phone</th>
          <th>Email</th>
        </tr>
      </thead>

      <tbody>

<?php
$sql = "SELECT * FROM members ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
      <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
      <td><?php echo htmlspecialchars($row['fullname']); ?></td>
      <td><?php echo htmlspecialchars($row['address']); ?></td>
      <td><?php echo htmlspecialchars($row['phone']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
    </tr>
    <?php
  }
} else {
  ?>
  <tr>
    <td colspan="5" style="text-align: center; color: #777;">No members registered yet.</td>
  </tr>
  <?php
}
?>

      </tbody>

    </table>

  </div>

</div>

<?php include 'footer.php'; ?>

</body>
</html>
