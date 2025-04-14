<?php
// Include the database model and user model
require_once __DIR__ . '/../models/database.php';
require_once __DIR__ . '/../models/User.php';

use Models\Database;
use Models\User;

// Start the session to manage user authentication
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Establish a database connection
$db = (new Database())->getConnection();
$userModel = new User($db);

// Check if the update form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $userId = $_SESSION['user']['id']; // Get the logged-in user's ID

    // Validate user input and check for conflicts
    if ($userModel->isUsernameTaken($username, $userId)) {
        $error = "This username is not available."; // Error message for username conflict
    } elseif ($userModel->isEmailTaken($email, $userId)) {
        $error = "This email is not available."; // Error message for email conflict
    } elseif (!empty($password) && password_verify($password, $_SESSION['user']['password'])) {
        $error = "This is the same password."; // Error message for same password
    } else {
        // Hash the new password if provided, otherwise use the existing password
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $_SESSION['user']['password'];
        $updateSuccess = $userModel->updateUser($userId, $username, $email, $hashedPassword);

        // Check if the update was successful
        if ($updateSuccess) {
            // Update session data with new user information
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['password'] = $hashedPassword;
            $success = "Profile updated successfully."; // Success message
        } else {
            $error = "Failed to update profile."; // Error message for update failure
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<style>
    /* Prevent dark mode from changing label text color */
    .no-dark-mode {
        color: black !important;
    }
</style>

<main id="content">
    <h2>Update Profile</h2>
    <?php if (isset($error)): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p class="message success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <!-- HTML form for updating user information -->
    <form method="POST" action="update_user.php" class="user-settings-form">
        <label for="username" class="no-dark-mode">Username:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['user']['username']) ?>" required>
        <label for="email" class="no-dark-mode">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" required>
        <label for="password" class="no-dark-mode">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter new password">
        <button type="submit">Update</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
