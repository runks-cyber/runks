<?php
session_start();

if (isset($_SESSION['username'])) {
    // Redirect to home page if the user is already logged in
    header("Location: home.php");
    exit();
}

require_once 'db_con.php'; // Ensure this file contains your PDO connection

$message = ''; // Initialize an empty message

try {
    if (isset($_POST["register"])) {
        // Validate input
        if (empty($_POST["fullname"]) || empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["confirm-password"])) {
            $message = '<label>All Fields are required</label>';
        } elseif ($_POST["password"] !== $_POST["confirm-password"]) {
            // Check if passwords match
            $message = '<label>Passwords do not match</label>';
        } elseif (strlen($_POST['password']) < 8) {
            // Check if the password is at least 8 characters long
            $message = '<label>Password must be at least 8 characters long</label>';
        } elseif (!preg_match('/[A-Z]/', $_POST['password'])) {
            // Check if the password contains at least one uppercase letter
            $message = '<label>Password must contain at least one uppercase letter</label>';
        } elseif (!preg_match('/[a-z]/', $_POST['password'])) {
            // Check if the password contains at least one lowercase letter
            $message = '<label>Password must contain at least one lowercase letter</label>';
        } elseif (!preg_match('/[0-9]/', $_POST['password'])) {
            // Check if the password contains at least one number
            $message = '<label>Password must contain at least one number</label>';
        }  else {
            // Check if the username already exists
            $pdoQuery = "SELECT * FROM users WHERE username = :username";
            $pdoResult = $PDO->prepare($pdoQuery);
            $pdoResult->execute(['username' => $_POST["username"]]);

            if ($pdoResult->rowCount() > 0) {
                $message = '<label>Username already exists. Please choose another.</label>';
            } else {
                // Hash the password
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                // Insert new user into the database
                $sql = "INSERT INTO users (fullname, username, password) VALUES (:fullname, :username, :password)";
                $stmt = $PDO->prepare($sql);
                if ($stmt->execute(['fullname' => $_POST['fullname'], 'username' => $_POST['username'], 'password' => $password])) {
                    // Redirect to login page after successful registration
                    header("Location: login.php");
                    exit(); // Make sure to exit after redirection
                } else {
                    $message = 'Error during registration. Please try again.';
                }
            }
        }
    }
} catch (PDOException $error) {
    $message = 'Database error: ' . htmlspecialchars($error->getMessage()); // Capture and sanitize any errors
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <div class="wrapper">
    <h2>Registration</h2>
    <?php if ($message) { echo '<div class="error">' . $message . '</div>'; } ?>
    <form method="POST" action="register.php">
      <div class="input-box">
        <input type="text" name="fullname" placeholder="Full Name" required>
      </div>
      <div class="input-box">
        <input type="text" name="username" placeholder="Username" required>
      </div>
      
      <div class="input-box">
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
        <span class="toggle-password" onclick="togglePasswordVisibility('password', this)">
          <i class="fas fa-eye-slash"></i> <!-- Start with eye-slash icon -->
        </span>
      </div>
      
      <div class="input-box">  
        <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm your password" required>
        <span class="toggle-password" onclick="togglePasswordVisibility('confirm-password', this)">
          <i class="fas fa-eye-slash"></i> <!-- Start with eye-slash icon -->
        </span>
      </div>
      
      <div class="input-box button">
        <input type="submit" name="register" value="Register Now">
      </div>
      <div class="text">
        <h3>Already have an account? <a href="login.php">Login now</a></h3>
      </div>
    </form>
  </div>

  <script>
    // Toggle password visibility and update icon
    function togglePasswordVisibility(id, element) {
        var field = document.getElementById(id);
        var icon = element.querySelector('i');

        if (field.type === "password") {
            field.type = "text"; // Show the password
            icon.classList.remove('fa-eye-slash'); // Remove 'eye-slash' icon
            icon.classList.add('fa-eye'); // Add 'eye' icon
        } else {
            field.type = "password"; // Hide the password
            icon.classList.remove('fa-eye'); // Remove 'eye' icon
            icon.classList.add('fa-eye-slash'); // Add 'eye-slash' icon
        }
    }
  </script>

</body>
</html>
