<?php 
session_start();
require_once '../vendor/autoload.php'; // Include the Composer autoloader

use Models\User;

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    // Collect the credentials from the form
    $credentials = [
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];

    // Call the login method from the User class
    $userData = $user->login($credentials);

    if ($userData !== false) {
        $_SESSION['login_success'] = "Login successful!";
        $_SESSION['user'] = $userData;  // Store the user data (ID, username, etc.)
        header("Location: index.php");  // Redirect to the homepage
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<main>
    <h2>Login</h2>

    <?php if (isset($_SESSION['login_success'])): ?>
        <p class="success"><?= $_SESSION['login_success']; ?></p>
        <?php unset($_SESSION['login_success']); ?>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <p class="error"><?= $error_message; ?></p>
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
        <button type="submit">Login</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
