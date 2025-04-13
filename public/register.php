<?php
session_start();
require_once '../vendor/autoload.php'; // Composer autoloader

use Models\User;

// Registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    // Collect user input (First Name, Last Name, Email, Password, and optionally Username)
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if a username is provided or generate one automatically
    $username = isset($_POST['username']) && !empty($_POST['username']) ? $_POST['username'] : strtolower($firstName . '.' . $lastName);

    // Ensure username is unique
    if (!$user->isUsernameAvailable($username)) {
        $_SESSION['error_message'] = "The username is already taken. Please choose another one.";
        header("Location: register.php");
        exit;
    }

    // Create a new user object
    $userData = [
        'username' => $username,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT) // Password hashing for security
    ];

    // Register the user (assuming there's a register method in the User class)
    if ($user->register($userData)) {
        // Redirect user to login page after successful registration
        $_SESSION['success_message'] = "Registration successful! Please log in.";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['error_message'] = "An error occurred during registration. Please try again.";
        header("Location: register.php");
        exit;
    }
}
?>

<?php include '../includes/header.php'; ?>

<main>
    <h2>Register</h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error"><?= $_SESSION['error_message'] ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success"><?= $_SESSION['success_message'] ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="username">Username (Optional)</label>
            <input type="text" id="username" name="username" placeholder="Leave blank for auto-generated username">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Register</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
