<?php include 'user_header.php'; ?>
<?php 
include '../includes/db.php';
$uid = $_SESSION['user_id'];
?>

<h2>Treasurer Center</h2>

<div class="table-container">
    <h3>My Payment History</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Due Amount</th>
                <th>Paid Amount</th>
                <th>Balance</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM payments WHERE user_id='$uid' ORDER BY date DESC";
            $res = mysqli_query($conn, $sql);
            if(mysqli_num_rows($res) > 0){
                while($row = mysqli_fetch_assoc($res)){
                    echo "<tr>
                        <td>{$row['date']}</td>
                        <td>{$row['actual_amount']}</td>
                        <td>{$row['paid_amount']}</td>
                        <td>{$row['balance']}</td>
                        <td>{$row['description']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No payments found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="card" style="margin-top:2rem;">
    <h3>Society Financial Report</h3>
    <p>Total Society Funds Collected: 
        <strong>
        <?php 
        $q = mysqli_query($conn, "SELECT SUM(paid_amount) as total FROM payments"); 
        echo "$" . number_format(mysqli_fetch_assoc($q)['total'], 2);
        ?>
        </strong>
    </p>
    <!-- Add more public financial reports here if needed -->
</div>

</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>
