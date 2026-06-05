<?php
require_once 'db_admin.php';
$msg = '';

if (isset($_POST['upload_image'])) {
    $title = $_POST['title'];
    
    // Use absolute path for robustness
    $base_upload_dir = dirname(__DIR__) . '/uploads/gallery/';
    
    // Create directory if it doesn't exist
    if (!is_dir($base_upload_dir)) {
        if (!mkdir($base_upload_dir, 0777, true)) {
            $msg = "Error: Failed to create upload directory.";
        }
    }

    if (!$msg) {
        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $file_name = "gallery_" . time() . "_" . rand(1000,9999) . "." . $file_ext;
        $target_file = $base_upload_dir . $file_name;
        
        if(in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $db_path = "uploads/gallery/" . $file_name;
                $stmt = $pdo->prepare("INSERT INTO gallery (image_path, title) VALUES (?, ?)");
                $stmt->execute([$db_path, $title]);
                $msg = "Image uploaded successfully!";
            } else {
                $msg = "Upload failed.";
            }
        } else {
            $msg = "Invalid image format (JPG, PNG, GIF only).";
        }
    }
}

if (isset($_GET['delete'])) {
    // Delete file and record
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if($row) {
        $file_abs_path = dirname(__DIR__) . '/' . $row['image_path'];
        if(file_exists($file_abs_path)) {
            unlink($file_abs_path);
        }
        $del = $pdo->prepare("DELETE FROM gallery WHERE id=?");
        $del->execute([$id]);
        header("Location: gallery.php");
        exit();
    }
}

$images = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");

include 'header.php';
?>

<div class="row gx-4 gy-4">
    <div class="col-12 mb-4">
        <h2 class="fw-bold">Gallery Management</h2>
        <?php if($msg): ?><div class="alert alert-info"><?php echo $msg; ?></div><?php endif; ?>
    </div>

    <!-- Upload Form -->
    <div class="col-xl-4 col-lg-5">
        <div class="card border-0 shadow-sm upload-card mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0 fw-bold">Upload Event Image</h6>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Image Title / Description</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Image</label>
                        <input type="file" name="image" class="form-control" required accept="image/*">
                    </div>
                    <button type="submit" name="upload_image" class="btn btn-primary w-100">Upload</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="col-xl-8 col-lg-7">
        <div class="row g-4">
            <?php while($row = $images->fetch()): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card gallery-card h-100">
                    <img src="../<?php echo $row['image_path']; ?>" class="gallery-img card-img-top" alt="Gallery">
                    <div class="card-body">
                        <h6 class="fw-bold text-truncate"><?php echo htmlspecialchars($row['title']); ?></h6>
                        <p class="text-muted mb-3 small"><?php echo date('d M Y', strtotime($row['uploaded_at'])); ?></p>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm w-100 btn-delete" onclick="return confirm('Delete image?');"><i class="fa fa-trash me-1"></i>Delete</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
