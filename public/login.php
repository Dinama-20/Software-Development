<?php 
session_start();
require_once '../vendor/autoload.php'; // Include the Composer autoloader

use Models\User;

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    // Collect the credentials from the form
    $credentials = [
        'username' => $_POST['username'],
        'password' => $_POST['password']
    ];

    // Call the login method from the User class
    if ($user->login($credentials)) {
        $_SESSION['login_success'] = "Login successful!";
        header("Location: index.php");
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<main>
    <h2>Login</h2>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success"><?= $_SESSION['success_message']; ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <p class="error"><?= $error_message; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
