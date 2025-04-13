<?php 
session_start();
require_once '../vendor/autoload.php'; // Composer autoloader

use Models\User;

// Check if user is already logged in
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    // Collect and sanitize login credentials
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Please enter a valid email address.";
        header("Location: login.php");
        exit;
    }

    // Create credentials array
    $credentials = [
        'email' => $email,
        'password' => $password
    ];

    // Call the login method from the User class
    $loggedUser = $user->login($credentials);

    // Debugging - Log login attempt
    if ($loggedUser === false) {
        $_SESSION['error_message'] = "Invalid email or password.";
        header("Location: login.php"); // Redirection if login fails
        exit;
    } else {
        // If login is successful
        $_SESSION['user'] = $loggedUser;
        header("Location: index.php"); // Redirect to home page
        exit;
    }
}
?>

<?php include '../includes/header.php'; ?>

<main>
    <h2>Login</h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error"><?= $_SESSION['error_message'] ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

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
