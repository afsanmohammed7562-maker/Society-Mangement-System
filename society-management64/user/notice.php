<?php include 'user_header.php'; ?>
<?php include '../includes/db.php'; ?>

<h2>Notice Board</h2>

<div class="dashboard-grid" style="display:block;">
    <?php
    $sql = "SELECT * FROM notice ORDER BY id DESC";
    $res = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($res)){
        echo "<div class='notice-item' style='background:white; border-left: 5px solid var(--primary-color); padding:1.5rem; margin-bottom:1.5rem; border-radius:5px; box-shadow:var(--card-shadow);'>
            <h3>{$row['title']}</h3>
            <span class='notice-date' style='color:#777; font-size:0.9rem;'>Posted on {$row['created_at']}</span>
            <hr style='border:0; border-top:1px solid #eee; margin:10px 0;'>
            <p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
            
            // Image hack checks
            if(strpos($row['description'], '[IMAGE]:') !== false){
                $parts = explode('[IMAGE]:', $row['description']);
                if(isset($parts[1])){
                    $img = trim($parts[1]);
                    echo "<br><img src='../$img' style='max-width:100%; border-radius:8px; margin-top:1rem;'>";
                }
            }

        echo "</div>";
    }
    ?>
</div>

</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>
