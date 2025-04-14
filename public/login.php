<?php
// Include necessary classes
require_once '../models/Database.php';
require_once '../models/User.php';

// Create a new database connection
$database = new Models\Database();
$db = $database->getConnection();

// Create a User instance, passing the database connection
$user = new Models\User($db);

// Handle login when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user credentials from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the authenticate method from the User class
    $authenticatedUser = $user->authenticate($username, $password);

    // Check if the credentials are correct
    if ($authenticatedUser) {
        // Login was successful
        // You can redirect the user to the main page or display a message
        echo "Login successful!";
        // Example of redirection:
        // header("Location: index.php"); // Redirect to the main page
        // exit;
    } else {
        // Login failed
        echo "Invalid username or password!";
    }
}
?>

<?php include '../includes/header.php'; ?>

    <!-- Login form -->
    <div class="login-container">
        <h2>Login</h2>
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
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <?php include '../includes/footer.php'; ?>
