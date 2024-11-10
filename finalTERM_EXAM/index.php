<?php
session_start();

if (isset($_SESSION['username'])) {
    // Redirect to home page if the user is already logged in
    header("Location: home.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <!-- Logo -->
        <img src="logo.png" alt="Site Logo" class="logo">

        <h1> BIG BREW </h1>

        <!-- Login and Register buttons -->
        <div class="buttons">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    </div>

</body>
</html>
