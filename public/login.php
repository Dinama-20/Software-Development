<?php 
session_start();
require_once '../vendor/autoload.php'; // Composer autoloader

use Models\User;

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    // Collect login credentials
    $credentials = [
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];

    // Call the login method from the User class
    $loggedUser = $user->login($credentials);

    if ($loggedUser) {
        // Store user information in session
        $_SESSION['user'] = $loggedUser;

        // Redirect to index or dashboard
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Invalid email or password.";
        header("Location: login.php");
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
