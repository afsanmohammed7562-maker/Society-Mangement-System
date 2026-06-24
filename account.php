<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Information</title>

  <link rel="stylesheet" href="Account.css">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav>

    <div class="logo">
      <img src="logo.png" alt="">
    </div>

    <ul class="menu">

      <li><a href="">Home</a></li>
      <li><a href="contect.html">Contact</a></li>
      <li><a href="#">Secretary</a></li>
      <li><a href="Treserer.html">Treasurer</a></li>
      <li><a href="#">Gallery</a></li>
      <li><a href="#">Notice Board</a></li>
      <li><a href="index.php">Members</a></li>

    </ul>

    <div class="right-icons">
      
      

      <div class="logout-box"><li><a href="Account.php"></a>
        <i class="fa-solid fa-right-from-bracket"></i>
      </div>

    </div>

  </nav>


<div class="container">

    <div class="card">

      <h2>Account Information</h2>

      <form action="insert.php" method="POST">

        <div class="row">

          <div class="col form-group">
            <label>Registration No</label>
            <input type="text" name="reg_no" value="" >
          </div>

          <div class="col form-group">
            <label>Username</label>
            <input type="text" name="username" value=>
          </div>

        </div>

        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="fullname" required>
        </div>

        <div class="row">

          <div class="col form-group">
            <label>Phone</label>
            <input type="text" name="phone" required>
          </div>

          <div class="col form-group">
            <label>Email</label>
            <input type="email" name="email" required>
          </div>

        </div>

        <div class="form-group">
          <label>Address</label>
          <textarea name="address" required></textarea>
        </div>

        <button type="submit" name="submit" class="btn">
          Update Info
        </button>

      </form>

    </div>

</div>

</body>
</html>