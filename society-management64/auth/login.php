<?php
ob_start();
require_once '../includes/db.php';
require_once '../includes/session.php';

$error = '';
$remember = false;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_COOKIE['remember_token']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = base64_decode($_COOKIE['user_id']);
    $_SESSION['user_role'] = base64_decode($_COOKIE['user_role']);
    $_SESSION['is_admin'] = (base64_decode($_COOKIE['is_admin']) === '1');
    header("Location: " . ($_SESSION['is_admin'] ? "../admin/dashboard.php" : "../index.php"));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && (password_verify($password, $admin['password']) || $password === $admin['password'])) {
                regenerateSession();
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['role'] = $admin['role']; 
                $_SESSION['is_admin'] = true;
                if ($remember) {
                    setRememberMeCookie($admin['id'], $admin['role'], true);
                }
                header("Location: ../admin/dashboard.php");
                exit();
            }

            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
                regenerateSession();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = 'User';
                $_SESSION['is_admin'] = false;
                if ($remember) {
                    setRememberMeCookie($user['id'], 'User', false);
                }
                header("Location: ../index.php");
                exit();
            }

            $error = "Invalid Username or Password";

        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Society Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<style>
.login-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    overflow: hidden;
}
.login-page::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
    animation: pulse 15s infinite;
}
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
.login-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 24px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    position: relative;
    z-index: 1;
    backdrop-filter: blur(20px);
}
.login-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2.5rem 2rem;
    text-align: center;
}
.login-card-header i {
    font-size: 3rem;
    color: white;
    margin-bottom: 1rem;
    display: block;
}
.login-card-header h3 {
    color: white;
    font-weight: 700;
    margin: 0;
    font-size: 1.8rem;
}
.login-card-body {
    padding: 2.5rem 2rem;
}
.login-input-group {
    position: relative;
    margin-bottom: 1.5rem;
}
.login-input-group .form-control {
    padding: 1rem 1rem 1rem 3rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    font-size: 1rem;
    transition: all 0.3s ease;
}
.login-input-group .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}
.login-input-group .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1.1rem;
}
.login-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    color: white;
    transition: all 0.3s ease;
    width: 100%;
}
.login-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    color: white;
}
.login-alert {
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}
.login-footer {
    text-align: center;
    padding: 1.5rem 2rem;
    background: #f8f9fa;
    border-top: 1px solid #e2e8f0;
}
.login-footer a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}
.login-footer a:hover {
    color: #764ba2;
}
.floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0;
}
.floating-shapes .shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s infinite;
}
.floating-shapes .shape:nth-child(1) {
    width: 80px; height: 80px; top: 20%; left: 10%;
    animation-delay: 0s;
}
.floating-shapes .shape:nth-child(2) {
    width: 120px; height: 120px; top: 60%; right: 15%;
    animation-delay: 2s;
}
.floating-shapes .shape:nth-child(3) {
    width: 60px; height: 60px; bottom: 30%; left: 20%;
    animation-delay: 4s;
}
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}
</style>

<div class="login-page">
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <div class="login-card">
        <div class="login-card-header">
            <i class="fa-solid fa-building-user"></i>
            <h3>Society Portal</h3>
        </div>
        <div class="login-card-body">
            <?php if($error): ?>
                <div class="alert alert-danger login-alert">
                    <i class="fa fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="login-input-group">
                    <i class="fa fa-user input-icon"></i>
                    <input type="text" name="username" class="form-control" placeholder="Username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div class="login-input-group">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" style="border-radius:6px;">
                    <label class="form-check-label" for="remember" style="color:#667eea;font-weight:500;">
                        Remember Me
                    </label>
                </div>
                <button type="submit" class="login-btn">
                    <i class="fa fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
        </div>
        <div class="login-footer">
            
        </div>
    </div>
</div>
</body>
</html>
