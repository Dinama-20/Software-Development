<?php 
require_once 'vendor/autoload.php'; // Include the Composer autoloader to automatically load classes

use App\Models\User;  // Use the correct namespace for the User class

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
        echo "Login successful!";
        // Redirect to the homepage or a protected area after successful login
        header("Location: index.php");
        exit;
    } else {
        echo "Invalid username or password."; // Error message if credentials are incorrect
    }
}
?>

<?php include '../includes/header.php'; ?>

<main>
    <h2>Login</h2>
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
