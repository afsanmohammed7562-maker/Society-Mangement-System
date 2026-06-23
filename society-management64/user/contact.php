<?php include 'user_header.php'; ?>
<?php 
include '../includes/db.php';

$msg = "";
if(isset($_POST['send'])){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $message = $_POST['message'];
    
    $sql = "INSERT INTO messages (name, phone, username, message) VALUES ('$name', '$phone', '$username', '$message')";
    if(mysqli_query($conn, $sql)){
        $msg = "Message sent successfully!";
    }
}
?>

<div class="login-container" style="min-height:60vh;">
    <form class="auth-form" method="POST" style="max-width:500px;">
        <h2>Contact Us</h2>
        <?php if($msg) echo "<p style='color:green; text-align:center;'>$msg</p>"; ?>
        <div class="form-group"><input type="text" name="name" placeholder="Your Name" required></div>
        <div class="form-group"><input type="text" name="phone" placeholder="Phone Number" required></div>
        <div class="form-group"><input type="text" name="username" placeholder="Username" required></div>
        <div class="form-group"><textarea name="message" placeholder="Message" rows="4" required></textarea></div>
        <button type="submit" name="send">Send Message</button>
    </form>
</div>

</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>
