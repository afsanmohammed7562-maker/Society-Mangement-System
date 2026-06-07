<?php
require_once 'includes/auth_check.php';
checkRole(['admin', 'secretary', 'member']);
include 'config/database.php';

$page_title = 'Announcements';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

$success_msg = '';
$error_msg = '';

$can_post = in_array($_SESSION['role'], ['admin', 'secretary']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $can_post) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error_msg = "Title and content are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO messages (type, title, content, created_by) VALUES ('announcement', ?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $success_msg = "Announcement posted successfully!";
        } else {
            $error_msg = "Error posting announcement.";
        }
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id']) && $_SESSION['role'] === 'admin') {
    $delete_id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ? AND type = 'announcement'");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $success_msg = "Announcement deleted.";
    }
    $stmt->close();
}
?>
<div class="main-content">
    <div class="page-header">
        <h4>Announcements</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $_SESSION['role']; ?>_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Announcements</li>
            </ol>
        </nav>
    </div>

    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i> <?php echo $success_msg; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle me-2"></i> <?php echo $error_msg; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <?php if ($can_post): ?>
    <div class="card form-card mb-4">
        <div class="card-header"><i class="bi bi-megaphone me-2"></i>Post New Announcement</div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" placeholder="Announcement title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="content" rows="5" placeholder="Write your announcement here..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Post Announcement</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="card table-card">
        <div class="card-header"><h5 class="mb-0"><i class="bi bi-megaphone me-2"></i>All Announcements</h5></div>
        <div class="card-body p-3">
            <?php
            $ann = $conn->query("SELECT m.*, u.U_name as author FROM messages m LEFT JOIN userd u ON m.created_by = u.id WHERE m.type='announcement' ORDER BY m.created_at DESC");
            if ($ann && $ann->num_rows > 0):
            ?>
            <div class="list-group list-group-flush">
                <?php while ($row = $ann->fetch_assoc()): ?>
                <div class="list-group-item px-0">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <div class="text-end">
                            <small class="text-muted d-block"><?php echo date('d M Y h:i A', strtotime($row['created_at'])); ?></small>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                            <form method="POST" action="" class="d-inline" onsubmit="return confirm('Delete this announcement?');">
                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    <small class="text-muted">Posted by: <?php echo htmlspecialchars($row['author'] ?? 'Unknown'); ?></small>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <p class="text-muted text-center py-4 mb-0">No announcements have been posted yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
$conn->close();
include 'includes/footer.php';
?>
