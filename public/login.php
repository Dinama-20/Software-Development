<?php
// Include necessary classes
require_once '../models/Database.php';
require_once '../models/User.php';

// Start the session to store user data
session_start();

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
        // Set session variables to store user information
        $_SESSION['user'] = [
            'id' => $authenticatedUser['id'],
            'username' => $authenticatedUser['username'],
            'first_name' => $authenticatedUser['first_name'],
            'last_name' => $authenticatedUser['last_name']
        ];
        
        // Redirect to the homepage (index.php) after successful login
        header("Location: index.php");
        exit; // Ensure no further code is executed after redirect
    } else {
        // Login failed
        $errorMessage = "Invalid username or password!";
    }
}
?>

<!-- Include external CSS and JavaScript files -->
<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/script.js" defer></script>

<!-- Include the header file -->
<?php include '../includes/header.php'; ?>

    <!-- Login form container -->
    <div class="login-container">
        <h2>Login</h2>
        <!-- Display error message if login fails -->
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <!-- Login form -->
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
        <!-- Link to the registration page -->
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

<!-- Include the footer file -->
<?php include '../includes/footer.php'; ?>
