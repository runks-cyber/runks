<?php
session_start();
require_once 'db_con.php'; // Ensure this file contains your PDO connection

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $PDO->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission for updating the account
$message = '';
if (isset($_POST['update'])) {
    $fullname = $_POST['fullname'];
    $new_username = $_POST['username'];

    // Check if the new username already exists, excluding the current user
    $usernameCheckQuery = "SELECT * FROM users WHERE username = :new_username AND id != :id";
    $stmt = $PDO->prepare($usernameCheckQuery);
    $stmt->execute(['new_username' => $new_username, 'id' => $user['id']]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        $message = 'Username already exists. Please choose a different username.';
    } else {
        // If password is being updated
        if (!empty($_POST['password'])) {
            // Validation for new password
            if ($_POST['password'] !== $_POST['confirm-password']) {
                $message = 'Passwords do not match.';
            } elseif (strlen($_POST['password']) < 8) {
                $message = 'Password must be at least 8 characters long.';
            } elseif (!preg_match('/[A-Z]/', $_POST['password'])) {
                $message = 'Password must contain at least one uppercase letter.';
            } elseif (!preg_match('/[a-z]/', $_POST['password'])) {
                $message = 'Password must contain at least one lowercase letter.';
            } elseif (!preg_match('/[0-9]/', $_POST['password'])) {
                $message = 'Password must contain at least one number.';
            } else {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }
        } else {
            $password = $user['password']; // Keep the old password if not updating
        }

        // Update the database if no errors
        if (empty($message)) {
            $updateQuery = "UPDATE users SET fullname = :fullname, username = :new_username, password = :password WHERE id = :id";
            $stmt = $PDO->prepare($updateQuery);
        
            if ($stmt->execute([
                'fullname' => $fullname,
                'new_username' => $new_username,
                'password' => $password,
                'id' => $user['id']
            ])) {
                $_SESSION['username'] = $new_username; // Update session username
                $message = 'Account updated successfully!';
            } else {
                $message = 'Failed to update account. Please try again.';
            }
        }
    }
}

// Handle account deletion
if (isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM users WHERE id = :id";
    $stmt = $PDO->prepare($deleteQuery);
    
    if ($stmt->execute(['id' => $user['id']])) {
        session_destroy(); // End session and redirect to login
        header('Location: login.php');
        exit();
    } else {
        $message = 'Failed to delete account. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="vaccount.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2>My Profile</h2>
        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST" action="account.php">
        
            <label for="fullname">Full Name:</label><br>
        <div class="input-box">
            <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']); ?>" required><br>
        </div> 
           
            <label for="username">Username:</label><br>
        <div class="input-box">    
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" required><br>
        </div>   
            <label for="password">New Password (Leave blank to keep current):</label><br>
            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Enter your new password">
                <span class="toggle-password" onclick="togglePasswordVisibility('password', this)">
                    <i class="fas fa-eye-slash"></i>
                </span>
            </div>
            
            <label for="confirm-password">Confirm Password:</label><br>
            <div class="input-box">
                <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm your new password">
                <span class="toggle-password" onclick="togglePasswordVisibility('confirm-password', this)">
                    <i class="fas fa-eye-slash"></i>
                </span>
            </div>

            <button type="submit" name="update">Update Account</button>
        </form>
        
        <form method="POST" action="account.php">
            <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</button>
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