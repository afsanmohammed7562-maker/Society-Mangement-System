<?php include 'admin_header.php'; ?>
<?php 
include '../includes/db.php';

// Add Payment (Pending)
if(isset($_POST['add_payment'])){
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount']; // Actual Amount
    $date = $_POST['date'];
    $desc = $_POST['description'];

    $sql = "INSERT INTO payments (user_id, date, actual_amount, paid_amount, balance, description) 
            VALUES ('$user_id', '$date', '$amount', 0, '$amount', '$desc')";
    mysqli_query($conn, $sql);
}

// Update Payment
if(isset($_POST['update_payment'])){
    $pay_id = $_POST['payment_id'];
    $paid = $_POST['paid_amount'];
    
    // Get current actual
    $q = mysqli_query($conn, "SELECT actual_amount FROM payments WHERE id='$pay_id'");
    $r = mysqli_fetch_assoc($q);
    $actual = $r['actual_amount'];
    $balance = $actual - $paid;

    $sql = "UPDATE payments SET paid_amount='$paid', balance='$balance' WHERE id='$pay_id'";
    mysqli_query($conn, $sql);
}

// Fetch users for dropdown
$users = mysqli_query($conn, "SELECT id, fullname, reg_no FROM users WHERE role='user'");
?>

<h2>Treasurer (Trusser) Dashboard</h2>

<div class="dashboard-grid">
    <div class="card" style="text-align:left;">
        <h3>Add Monthly Due</h3>
        <form method="POST">
            <div class="form-group">
                <select name="user_id" required>
                    <option value="">Select Member</option>
                    <?php 
                    mysqli_data_seek($users, 0); 
                    while($u = mysqli_fetch_assoc($users)){
                        echo "<option value='{$u['id']}'>{$u['fullname']} ({$u['reg_no']})</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group"><input type="number" name="amount" placeholder="Amount Due" step="0.01" required></div>
            <div class="form-group"><input type="date" name="date" required></div>
            <div class="form-group"><input type="text" name="description" placeholder="Description (e.g. Jan 2024 Maintenance)"></div>
            <button type="submit" name="add_payment">Add Due</button>
        </form>
    </div>

    <div class="card" style="text-align:left;">
        <h3>Update Payment</h3>
        <form method="POST">
            <div class="form-group"><input type="number" name="payment_id" placeholder="Payment ID" required></div>
            <div class="form-group"><input type="number" name="paid_amount" placeholder="Paid Amount" step="0.01" required></div>
            <button type="submit" name="update_payment">Update</button>
        </form>
    </div>
</div>

<div class="table-container">
    <h3>Monthly Report</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reg No</th>
                <th>Name</th>
                <th>Date</th>
                <th>Actual</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Desc</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT p.*, u.fullname, u.reg_no FROM payments p JOIN users u ON p.user_id = u.id ORDER BY p.date DESC";
            $res = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($res)){
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['reg_no']}</td>
                    <td>{$row['fullname']}</td>
                    <td>{$row['date']}</td>
                    <td>{$row['actual_amount']}</td>
                    <td>{$row['paid_amount']}</td>
                    <td>{$row['balance']}</td>
                    <td>{$row['description']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</main>
</body>
</html>
