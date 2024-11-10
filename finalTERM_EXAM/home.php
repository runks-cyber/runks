<?php
session_start();
require_once 'db_con.php'; 

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect if not logged in
    header("Location: login.php"); 
    exit();
}

// Fetch user's full name for greeting
$username = $_SESSION['username'];
$sql = "SELECT fullname FROM users WHERE username = :username";
$stmt = $PDO->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$fullname = $user['fullname'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Website</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  </head>

  <body>
    <header>
      <nav class="navbar">
        <a href="#" class="logo">Big Brew<span></span></a>
        <ul class="menu-links">
          <li><a href="#">Home</a></li>
          <li><a href="Product.php">Products</a></li>
          <li><a href="#">Testimonials</a></li>
          <li><a href="#">About Us</a></li>
          <?php if (isset($_SESSION["username"])): ?>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="account.php"><i class="fas fa-user-circle"></i></a></li>
          <?php else: ?>
            <li><a href="login.php">Login</a></li>
          <?php endif; ?>
        </ul>    
      </nav>
    </header>

    <main>
      <section class="hero-section">
        <div class="content">
          <?php if ($fullname): ?>
            <p class="greeting">Hello, <?= htmlspecialchars($fullname); ?>!</p>
          <?php endif; ?>
          <h1>What's Your Big Brew Combo?</h1>
          <p>From classic coffee to fruity adventures, we've got a drink for every mood</p>
          <button>Order Now</button>
        </div>
      </section>
    </main>
  </body>
</html>
