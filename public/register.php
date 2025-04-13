<?php 
session_start();
require_once '../vendor/autoload.php'; // Composer autoloader to automatically load classes

use Models\User;

// Registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    // Collect the registration data from the form
    $data = [
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];

    // Call the register method from the User class
    if ($user->register($data)) {
        $_SESSION['success_message'] = "User registered successfully!";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Registration failed. Please try again.";
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

    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
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
