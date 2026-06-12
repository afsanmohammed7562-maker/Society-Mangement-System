<?php
// Script to set up or reset passwords to known defaults
require_once 'includes/db.php';

$admin_pass = password_hash('admin123', PASSWORD_BCRYPT);
$user_pass = password_hash('user123', PASSWORD_BCRYPT);

echo "<!DOCTYPE html><html><head><title>Setup Passwords</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head><body class='bg-light'><div class='container mt-5'><div class='card shadow'><div class='card-body'>";
echo "<h2 class='card-title text-primary fw-bold'>System Setup</h2>";
echo "<p class='lead'>Resetting all passwords to defaults...</p><hr>";

try {
    // Update Admins
    $sql = "UPDATE admins SET password = :pass";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pass' => $admin_pass]);
    echo "<div class='alert alert-success'>✅ Admin passwords reset to <strong>admin123</strong></div>";

    // Update Users (specifically 'john', but let's do all for setup)
    $sql = "UPDATE users SET password = :pass";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pass' => $user_pass]);
    echo "<div class='alert alert-success'>✅ User passwords reset to <strong>user123</strong></div>";

    echo "<hr><a href='auth/login.php' class='btn btn-primary btn-lg w-100'>Go to Login Page</a>";

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<p>Please ensure your database 'society_management' is imported correctly.</p>";
}

echo "</div></div></div></body></html>";
?>
