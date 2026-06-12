<?php
require_once '../includes/db.php';
require_once 'includes/admin_check.php';

$msg = '';

// Handle Report Upload
if (isset($_POST['upload_report'])) {
    $type = $_POST['type']; // Secretary or Treasurer
    $month_year = $_POST['month_year'];
    
    // Define absolute path to uploads directory to avoid relative path issues
    // __DIR__ is '.../admin', so go up one level to root, then 'uploads/reports/'
    $base_upload_dir = dirname(__DIR__) . '/uploads/reports/';
    
    // Create directory if it doesn't exist
    if (!is_dir($base_upload_dir)) {
        if (!mkdir($base_upload_dir, 0777, true)) {
            $msg = "Error: Failed to create upload directory.";
        }
    }

    if (!$msg) {
        $file_ext = strtolower(pathinfo($_FILES["report_file"]["name"], PATHINFO_EXTENSION));
        // Sanitize filename
        $safe_month_year = preg_replace('/[^a-zA-Z0-9_-]/', '_', $month_year);
        $file_name = $type . "_" . $safe_month_year . "_" . time() . "." . $file_ext;
        $target_file = $base_upload_dir . $file_name;
        
        // Allow basic types
        if(in_array($file_ext, ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'])) {
            if (move_uploaded_file($_FILES["report_file"]["tmp_name"], $target_file)) {
                // Save to DB (Relative path for web access)
                $stmt = $pdo->prepare("INSERT INTO reports (type, month_year, file_path) VALUES (?, ?, ?)");
                $db_path = "uploads/reports/" . $file_name;
                $stmt->execute([$type, $month_year, $db_path]);
                $msg = "$type Report uploaded successfully!";
                
                if($type == 'Treasurer') {
                    header("Location: treasurer.php?msg=uploaded");
                    exit();
                }
            } else {
                $msg = "File upload failed. Check folder permissions.";
            }
        } else {
            $msg = "Invalid file type.";
        }
    }
}

// Handle Notice Add
if (isset($_POST['add_notice'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $stmt = $pdo->prepare("INSERT INTO notices (title, content) VALUES (?, ?)");
    $stmt->execute([$title, $content]);
    $msg = "Notice added successfully!";
}

// Handle Notice Delete
if (isset($_GET['delete_notice'])) {
    $id = $_GET['delete_notice'];
    $stmt = $pdo->prepare("DELETE FROM notices WHERE id=?");
    $stmt->execute([$id]);
    header("Location: secretary.php");
    exit();
}

$notices = $pdo->query("SELECT * FROM notices ORDER BY date_posted DESC");
$reports = $pdo->query("SELECT * FROM reports WHERE type='Secretary' ORDER BY uploaded_at DESC");

include 'includes/header.php';
?>

<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-bold">Secretary Management</h2>
        <?php if($msg): ?><div class="alert alert-info"><?php echo $msg; ?></div><?php endif; ?>
    </div>

    <!-- Upload Report -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0 fw-bold">Upload Secretary Report</h6>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="Secretary">
                    <div class="mb-3">
                        <label class="form-label">Month Year</label>
                        <input type="text" name="month_year" class="form-control" placeholder="e.g. January 2024" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Report File</label>
                        <input type="file" name="report_file" class="form-control" required>
                    </div>
                    <button type="submit" name="upload_report" class="btn btn-info w-100 text-white">Upload</button>
                </form>
            </div>
        </div>

        <!-- Add Notice -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0 fw-bold">Add Notice Board Update</h6>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" name="add_notice" class="btn btn-danger w-100">Post Notice</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Lists -->
    <div class="col-md-8">
        <!-- Reports List -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Secretary Reports History</h6>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php while($rep = $reports->fetch()): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($rep['month_year']); ?></strong>
                            <small class="text-muted d-block"><?php echo date('d M Y', strtotime($rep['uploaded_at'])); ?></small>
                        </div>
                        <a href="../<?php echo $rep['file_path']; ?>" class="btn btn-sm btn-outline-primary" download>Download</a>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <!-- Notices List -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Active Notices</h6>
            </div>
            <div class="card-body">
                <?php while($notice = $notices->fetch()): ?>
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-bold"><?php echo htmlspecialchars($notice['title']); ?></h5>
                        <a href="?delete_notice=<?php echo $notice['id']; ?>" class="text-danger" onclick="return confirm('Delete?');"><i class="fa fa-trash"></i></a>
                    </div>
                    <p class="text-secondary mb-1"><?php echo nl2br(htmlspecialchars($notice['content'])); ?></p>
                    <small class="text-muted">Posted on: <?php echo date('d M Y h:i A', strtotime($notice['date_posted'])); ?></small>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
