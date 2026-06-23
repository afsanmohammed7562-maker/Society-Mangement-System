<?php include 'user_header.php'; ?>
<?php include '../includes/db.php'; ?>

<h2>Event Gallery</h2>

<div class="gallery-grid">
    <?php
    $sql = "SELECT * FROM gallery ORDER BY id DESC";
    $res = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($res)){
        echo "<div class='gallery-item'>
            <img src='../{$row['image']}' alt='Event Photo'>
            <div class='gallery-overlay'>
                <a href='../{$row['image']}' download class='btn-download'><i class='fa fa-download'></i> Download</a>
            </div>
        </div>";
    }
    ?>
</div>

</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>
