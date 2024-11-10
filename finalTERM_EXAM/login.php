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
    if (isset($_POST["login"])) {
        if (empty($_POST["username"]) || empty($_POST["password"])) {
            $message = '<label>All Fields are required</label>';
        } else {
            // Prepare the SQL statement to prevent SQL injection
            $pdoQuery = "SELECT * FROM users WHERE username = :username";
            $pdoResult = $PDO->prepare($pdoQuery);
            $pdoResult->execute(['username' => $_POST["username"]]);
            $user = $pdoResult->fetch(PDO::FETCH_ASSOC);
            
            // Verify the user exists and check the password
            if ($user && password_verify($_POST["password"], $user['password'])) {
                $_SESSION["username"] = $user["username"]; // Store username in session
                header("location: home.php"); // Redirect to home page
                exit; // Stop further execution
            } else {
                $message = '<label>Username or password are incorrect.</label>';
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
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <?php if ($message) { echo '<div class="error">' . $message . '</div>'; } ?>
        <form method="POST" action="login.php">
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('password', this)">
                    <i class="fas fa-eye-slash"></i> <!-- Start with eye-slash icon -->
                </span>
            </div>
            <div class="input-box button">
                <input type="submit" name="login" value="Login">
            </div>
            <div class="text">
                <h3>Don't have an account? <a href="register.php">Register now</a></h3>
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
