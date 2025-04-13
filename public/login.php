<?php
session_start();
require_once '../vendor/autoload.php'; // Composer autoloader

use Models\User;

// Check if the user is already logged in
if (isset($_SESSION['user'])) {
    header("Location: index.php");  // Redirect to the homepage if the user is already logged in
    exit;
}

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    // Sanitize and collect login credentials
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Please enter a valid email address.";
        header("Location: login.php"); // Redirect back to login page with error message
        exit;
    }

    // Create credentials array
    $credentials = [
        'email' => $email,
        'password' => $password
    ];

    // Call the login method from the User class
    $loggedUser = $user->login($credentials);

    // Check if login failed
    if ($loggedUser === false) {
        $_SESSION['error_message'] = "Invalid email or password."; // Error message for invalid credentials
        header("Location: login.php"); // Redirect back to login page
        exit;
    } else {
        // Successful login, store user information in session
        $_SESSION['user'] = $loggedUser;
        header("Location: index.php"); // Redirect to the homepage upon successful login
        exit;
    }
}
?>

<?php include '../includes/header.php'; ?>

<main>
    <h2>Login</h2>

    <!-- Display error message if login failed -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error"><?= $_SESSION['error_message'] ?></p>
        <?php unset($_SESSION['error_message']); ?> <!-- Clear the error message after displaying -->
    <?php endif; ?>

    <!-- Login form -->
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Log in</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
