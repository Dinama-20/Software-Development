<?php
// Start the session
session_start();

// Include the configuration file and User model
require_once '../config/config.php';
require_once '../models/User.php';

use Models\User;

// Check if the login form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        // Create a User instance and authenticate
        $userModel = new User($db);
        $user = $userModel->authenticate($username, $password);

        // Store user information in the session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirect to the homepage (index.php)
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage(); // Display the error message
    }
}
?>

<!-- Include external CSS and JavaScript files -->
<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/script.js" defer></script>

<!-- Include the header file -->
<?php include '../includes/header.php'; ?>

    <!-- Login form container -->
    <div class="login-container">
        <h2>Login</h2>
        <!-- Display error message if login fails -->
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <!-- Login form -->
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <!-- Link to the registration page -->
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

<!-- Include the footer file -->
<?php include '../includes/footer.php'; ?>
