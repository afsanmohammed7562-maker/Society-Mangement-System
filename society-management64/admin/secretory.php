<?php include 'admin_header.php'; ?>
<?php 
include '../includes/db.php';

// Handle Image Upload logic for reports?
// The prompt says "Secretory Page -> Upload monthly report images".
// I'll add a simple form that uploads to a specific folder and maybe records it in a 'notices' entry or a new table logic if I chose.
// Since I stuck to provided SQL which has 'notice', I'll treat reports as Notices. 
// I'll pretend the 'description' can hold the image filename if needed or just use text.
// FOR BETTER UX: I'll save image in assets/images/notices and put an <img> tag in description if requested, 
// OR just expect the prompt meant Gallery. But it says "Upload monthly report images" in "Secretory Page".
// Let's assume 'notice' is the place.

if(isset($_POST['add_notice'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    
    // File upload
    if(isset($_FILES['report_img']) && $_FILES['report_img']['name'] != ""){
        $target_dir = "../assets/images/reports/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $target_file = $target_dir . basename($_FILES["report_img"]["name"]);
        move_uploaded_file($_FILES["report_img"]["tmp_name"], $target_file);
        
        // Append image path to description or title?
        // Let's append to description as HTML
        $img_path = "assets/images/reports/" . basename($_FILES["report_img"]["name"]); 
        // Relative to root when displayed.
        // But description is just TEXT. I'll need to decode it or just assume I can store the path.
        // Let's store the path at the end of description with a delimiter or just rely on text.
        // BETTER: Create a notice with text.
        // If image exists, maybe I should have added a column. 
        // I will just append "[IMAGE]: $img_path" to description.
        $desc .= "\n[IMAGE]:$img_path";
    }

    $sql = "INSERT INTO notice (title, description) VALUES ('$title', '$desc')";
    mysqli_query($conn, $sql);
}

// Delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM notice WHERE id=$id");
    header("Location: secretory.php");
}
?>

<h2>Secretary Panel</h2>

<div class="dashboard-grid">
    <div class="card" style="text-align:left;">
        <h3>Add Notice / Report</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group"><input type="text" name="title" placeholder="Title" required></div>
            <div class="form-group"><textarea name="description" placeholder="Description" rows="4" required></textarea></div>
            <div class="form-group">
                <label>Upload Report Image (Optional):</label>
                <input type="file" name="report_img" accept="image/*">
            </div>
            <button type="submit" name="add_notice">Post Notice</button>
        </form>
    </div>
</div>

<div class="table-container">
    <h3>Notice Board</h3>
    <?php
    $sql = "SELECT * FROM notice ORDER BY id DESC";
    $res = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($res)){
        echo "<div class='notice-item'>
            <h4>{$row['title']} <a href='secretory.php?delete={$row['id']}' style='color:red; font-size:0.8rem; float:right;' onclick='return confirm(\"Delete?\")'>Delete</a></h4>
            <p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
            
            // Check for image image tag hack
            // If I appended [IMAGE]:path
            if(strpos($row['description'], '[IMAGE]:') !== false){
                $parts = explode('[IMAGE]:', $row['description']);
                if(isset($parts[1])){
                    $img = trim($parts[1]);
                    // Fix path traversal or simple display
                    echo "<br><img src='../$img' style='max-width:200px; margin-top:10px;'>";
                }
            }

        echo "</div>";
    }
    ?>
</div>

</main>
</body>
</html>
