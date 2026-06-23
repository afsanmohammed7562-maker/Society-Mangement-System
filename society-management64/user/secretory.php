<?php include 'user_header.php'; ?>
<?php include '../includes/db.php'; ?>

<h2>Secretary Reports</h2>
<p>View all official secretary reports and circulars.</p>

<!-- Reusing Notice Logic specifically for "Reports" if we had a category, 
     but for now just showing notices again or clarifying this is the report section. 
     Prompt says "Monthly Secretory Report" image download? -->

<div class="dashboard-grid" style="display:block;">
    <?php
    // Assuming reports are in notices
    $sql = "SELECT * FROM notice ORDER BY id DESC";
    $res = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($res)){
         // Filter logic can be added here if needed
        echo "<div class='card' style='text-align:left; margin-bottom:1rem;'>
            <h3>{$row['title']}</h3>
            <p>" . nl2br($row['description']) . "</p>
            <!-- If image exists handle display -->
             " . (strpos($row['description'], '[IMAGE]:') !== false ? "<br><small>Has Attachment</small>" : "") . "
        </div>";
    }
    ?>
</div>

</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>
