<?php 
require_once '../vendor/autoload.php'; // Include the Composer autoloader to automatically load classes

use App\Models\User;  // Use the correct namespace for the User class

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
        echo "Registration successful!";
        // Redirect to the login page or the homepage after successful registration
        header("Location: login.php");
        exit;
    } else {
        echo "Registration failed. Please try again."; // Error message if registration fails
    }
}
?>

<?php include '../includes/header.php'; ?>

<main>
    <h2>Register</h2>
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
