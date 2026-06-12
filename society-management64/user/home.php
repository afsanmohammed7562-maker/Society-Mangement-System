<?php include 'user_header.php'; ?>
<?php 
include '../includes/db.php';
$uid = $_SESSION['user_id'];

// Data Fetching
// 1. Pending Amount (My Balance)
$q_bal = mysqli_query($conn, "SELECT SUM(balance) as my_balance FROM payments WHERE user_id='$uid'");
$my_balance = mysqli_fetch_assoc($q_bal)['my_balance'] ?? 0;

// 2. Total Society Amount
$q_total = mysqli_query($conn, "SELECT SUM(paid_amount) as total FROM payments");
$soc_total = mysqli_fetch_assoc($q_total)['total'] ?? 0;

// 3. Members List (Limit?)
$q_mems = mysqli_query($conn, "SELECT fullname FROM users WHERE role='user' LIMIT 5");

?>

<h1>Welcome, <?php echo $_SESSION['fullname']; ?></h1>

<div class="dashboard-grid">
    <div class="card">
        <h3>$<?php echo number_format($my_balance, 2); ?></h3>
        <p>My Pending Amount</p>
    </div>
    <div class="card">
        <h3>$<?php echo number_format($soc_total, 2); ?></h3>
        <p>Total Society Funds</p>
    </div>
</div>

<div class="dashboard-grid">
    <div class="table-container">
        <h3>Members List (Preview)</h3>
        <ul>
            <?php 
            while($m = mysqli_fetch_assoc($q_mems)){
                echo "<li>{$m['fullname']}</li>";
            }
            ?>
        </ul>
        <a href="#" style="color:var(--primary-color);">View All</a>
    </div>

    <div class="table-container">
        <h3>Latest Notice</h3>
        <?php
        $q_notice = mysqli_query($conn, "SELECT * FROM notice ORDER BY id DESC LIMIT 1");
        if($n = mysqli_fetch_assoc($q_notice)){
            echo "<h4>{$n['title']}</h4><p>" . substr($n['description'], 0, 100) . "...</p>";
            echo "<a href='notice.php' style='color:var(--primary-color);'>Read More</a>";
        } else {
            echo "<p>No notices.</p>";
        }
        ?>
    </div>
</div>

</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>
