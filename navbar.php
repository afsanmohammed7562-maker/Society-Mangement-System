<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
nav {
    width: 100%;
    height: 70px;
    background: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 50px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    font-family: 'Poppins', sans-serif;
}
.logo-container {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}
.logo-text {
    font-size: 20px;
    font-weight: 700;
    color: #5b2b90;
    text-transform: uppercase;
}
.menu {
    display: flex;
    list-style: none;
    gap: 28px;
    margin: 0;
    padding: 0;
}
.menu li a {
    text-decoration: none;
    color: #333;
    font-size: 14px;
    font-weight: 500;
    transition: 0.3s;
}
.menu li a:hover, .menu li a.active {
    color: #5b2b90;
}
.right-icons {
    display: flex;
    align-items: center;
    gap: 18px;
}
.right-icons a {
    text-decoration: none;
    color: #333;
    font-size: 14px;
    font-weight: 500;
    transition: 0.3s;
}
.right-icons a:hover, .right-icons a.active {
    color: #5b2b90;
}
.user {
    font-size: 24px;
    color: #222;
    cursor: pointer;
}
.logout-box {
    width: 32px;
    height: 32px;
    border: 1px solid #d63384;
    border-radius: 6px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #d63384;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
}
.logout-box:hover {
    background: #d63384;
    color: #fff;
}
@media(max-width: 768px) {
    nav {
        flex-direction: column;
        height: auto;
        padding: 15px;
        gap: 10px;
    }
    .menu {
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
    }
    .right-icons {
        margin-top: 5px;
    }
}
</style>

<nav>
  <a href="index.php" class="logo-container">
    <i class="fa-solid fa-building-user" style="font-size: 28px; color: #5b2b90;"></i>
    <span class="logo-text">SMS</span>
  </a>

  <ul class="menu">
    <li><a href="" class="<?php echo ($current_page == '') ? 'active' : ''; ?>">Home</a></li>
    <li><a href="members.php" class="<?php echo ($current_page == 'members.php') ? 'active' : ''; ?>">Members</a></li>
    <li><a href="treasurer.php" class="<?php echo ($current_page == 'treasurer.php') ? 'active' : ''; ?>">Treasurer</a></li>
    <li><a href="contact.php" class="<?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>
    <li><a href="" class="<?php echo ($current_page == '') ? 'active' : ''; ?>">Secretary</a></li>
    <li><a href="" class="<?php echo ($current_page == '') ? 'active' : ''; ?>">Gallery</a></li>
    <li><a href="" class="<?php echo ($current_page == '') ? 'active' : ''; ?>">Notice Board</a></li>
    
  </ul>

  <div class="right-icons">
    <a href="account.php" class="<?php echo ($current_page == 'account.php') ? 'active' : ''; ?>">Account</a>
    <i class="fa-solid fa-circle-user user" onclick="window.location.href='account.php'"></i>
    <a href="index.php" class="logout-box" title="Logout" onclick="alert('Logged out successfully (Demo)')">
      <i class="fa-solid fa-right-from-bracket"></i>
    </a>
  </div>
</nav>
