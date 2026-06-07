<?php
require_once 'includes/auth_check.php';
checkRole(['admin']);
include 'config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_members.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT profile_photo FROM members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $member = $result->fetch_assoc();
    if (!empty($member['profile_photo']) && file_exists('uploads/' . $member['profile_photo'])) {
        unlink('uploads/' . $member['profile_photo']);
    }
}
$stmt->close();

$stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: manage_members.php?success=deleted");
} else {
    header("Location: manage_members.php?error=delete");
}
$stmt->close();
$conn->close();
exit();
?>
