<?php
// Include the configuration file for database connection
require_once __DIR__ . '/../models/database.php';
require_once __DIR__ . '/../models/User.php';

use Models\Database;
use Models\User;

// Start the session to manage user data
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user'])) {
    header("Location: index.php");  // Redirect to the main page if the user is already logged in
    exit();
}

// Registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate empty fields
    if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
        $error = "Please fill in all fields."; // Error message for empty fields
    } else {
        // Get the database connection
        $db = (new Database())->getConnection();
        $user = new User($db);

        // Check if the username is already taken
        if (!$user->isUsernameAvailable($username)) {
            $error = "The username is already taken. Please choose another one."; // Error message for taken username
        } else {
            // Hash the password before storing it in the database
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Create an array with user data
            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword
            ];

            // Try to register the user
            if ($user->register($userData)) {
                // Successful registration, redirect to the login page
                $_SESSION['success_message'] = "Registration successful! Please log in.";
                header("Location: login.php");
                exit();
            } else {
                // Registration failed
                $error = "This email is already registered."; // Error message for already registered email
            }
        }
    }
}
?>

<!-- Include external CSS and JavaScript files -->
<link rel="stylesheet" href="../assets/css/style.css">
<script src="../assets/js/script.js" defer></script>
<?php include '../includes/header.php'; ?>

<main>
    <h2>Register</h2>

    <!-- Show errors if there are any -->
    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <!-- Show success message if registration is successful -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success"><?= $_SESSION['success_message'] ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- Registration form -->
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

<script>
    // Client-side validation for the registration form
    document.querySelector("form").addEventListener("submit", function (e) {
        const fields = ["first_name", "last_name", "username", "email", "password"];
        let valid = true;

        fields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                valid = false;
                alert(`${field.replace("_", " ")} is required.`); // Alert for missing fields
            }
        });

        if (!valid) e.preventDefault(); // Prevent form submission if validation fails
    });
</script>

<!-- Include the footer -->
<?php include '../includes/footer.php'; ?>
