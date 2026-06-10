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
  <link rel="stylesheet" href="member.css">
  <link rel="stylesheet" href="footer.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">

  <div class="title" style="margin-top: 30px;">
    <i class="fa-solid fa-users"></i>
    <h1>Society Members Directory</h1>
  </div>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
      <i class="fa-solid fa-circle-check"></i>
      <?php
        if ($_GET['success'] === 'updated') echo 'Member record updated successfully!';
        elseif ($_GET['success'] === 'deleted') echo 'Member deleted successfully!';
        else echo 'Operation completed successfully!';
      ?>
    </div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-error">
      <i class="fa-solid fa-circle-xmark"></i>
      Error: <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
  <?php endif; ?>

  <!-- ADD MEMBER FORM -->
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
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
<?php
$sql    = "SELECT * FROM members ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id       = $row['id'];
        $reg_no   = htmlspecialchars($row['reg_no'],   ENT_QUOTES);
        $username = htmlspecialchars($row['username'],  ENT_QUOTES);
        $fullname = htmlspecialchars($row['fullname'],  ENT_QUOTES);
        $phone    = htmlspecialchars($row['phone'],     ENT_QUOTES);
        $email    = htmlspecialchars($row['email'],     ENT_QUOTES);
        $address  = htmlspecialchars($row['address'],   ENT_QUOTES);
        echo "
        <tr>
          <td>{$reg_no}</td>
          <td>{$fullname}</td>
          <td>{$address}</td>
          <td>{$phone}</td>
          <td>{$email}</td>
          <td style='text-align:center; white-space:nowrap;'>
            <button class='btn-icon btn-icon-edit' title='Edit Member'
              onclick=\"openEditModal('{$id}','{$reg_no}','{$username}','{$fullname}','{$phone}','{$email}','{$address}')\">
              <i class='fa-solid fa-pen-to-square'></i>
            </button>
            <button class='btn-icon btn-icon-delete' title='Delete Member'
              onclick=\"openDeleteModal('{$id}','{$fullname}')\">
              <i class='fa-solid fa-trash'></i>
            </button>
          </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center;color:#777;'>No members registered yet.</td></tr>";
}
?>
      </tbody>
    </table>
  </div>

</div>

<!-- ===== EDIT MODAL ===== -->
<div id="editModal" class="modal" onclick="if(event.target===this)closeEditModal()">
  <div class="modal-content">
    <div class="modal-header">
      <h2><i class="fa-solid fa-pen-to-square" style="color:#5b2b90;margin-right:8px;"></i>Edit Member</h2>
      <span class="close-btn" onclick="closeEditModal()">&times;</span>
    </div>
    <form action="update.php" method="POST" style="padding:25px;">
      <input type="hidden" name="id" id="edit_id">
      <div class="form-grid">
        <div class="input-box">
          <label>Registration No</label>
          <input type="text" name="reg_no" id="edit_reg_no" required>
        </div>
        <div class="input-box">
          <label>Username</label>
          <input type="text" name="username" id="edit_username" required>
        </div>
        <div class="input-box">
          <label>Full Name</label>
          <input type="text" name="fullname" id="edit_fullname" required>
        </div>
        <div class="input-box">
          <label>Phone</label>
          <input type="text" name="phone" id="edit_phone" required>
        </div>
        <div class="input-box">
          <label>Email</label>
          <input type="email" name="email" id="edit_email" required>
        </div>
        <div class="input-box">
          <label>Address</label>
          <input type="text" name="address" id="edit_address" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn-primary">
          <i class="fa-solid fa-floppy-disk" style="margin-right:6px;"></i>Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

//delete option

<div id="deleteModal" class="modal" onclick="if(event.target===this)closeDeleteModal()">
  <div class="modal-content delete-modal-content">
    <div class="modal-header">
      <h2><i class="fa-solid fa-trash" style="color:#e02424;margin-right:8px;"></i>Delete Member</h2>
      <span class="close-btn" onclick="closeDeleteModal()">&times;</span>
    </div>
    <div style="padding:25px;">
      <div class="modal-body">
        <div class="warning-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <p style="font-size:16px;color:#2d3748;font-weight:600;">Are you sure you want to delete</p>
        <p id="delete_name" style="font-size:18px;color:#5b2b90;font-weight:700;margin-top:6px;"></p>
        <p class="warning-text">This action cannot be undone. All linked payment records will also be removed.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Cancel</button>
        <a id="delete_link" href="#" class="btn-danger"
           style="text-decoration:none;padding:10px 24px;font-size:14px;font-weight:600;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
          <i class="fa-solid fa-trash"></i> Delete
        </a>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script>
function openEditModal(id, reg_no, username, fullname, phone, email, address) {
  document.getElementById('edit_id').value       = id;
  document.getElementById('edit_reg_no').value   = reg_no;
  document.getElementById('edit_username').value = username;
  document.getElementById('edit_fullname').value = fullname;
  document.getElementById('edit_phone').value    = phone;
  document.getElementById('edit_email').value    = email;
  document.getElementById('edit_address').value  = address;
  document.getElementById('editModal').classList.add('show');
}
function closeEditModal() {
  document.getElementById('editModal').classList.remove('show');
}

function openDeleteModal(id, name) {
  document.getElementById('delete_name').textContent = name;
  document.getElementById('delete_link').href = 'delete.php?id=' + id;
  document.getElementById('deleteModal').classList.add('show');
}
function closeDeleteModal() {
  document.getElementById('deleteModal').classList.remove('show');
}

// Auto-dismiss alert after 4 seconds
var alertEl = document.querySelector('.alert');
if (alertEl) {
  setTimeout(function() {
    alertEl.style.transition = 'opacity 0.5s';
    alertEl.style.opacity = '0';
    setTimeout(function() { alertEl.remove(); }, 500);
  }, 4000);
}
</script>

</body>
</html>
