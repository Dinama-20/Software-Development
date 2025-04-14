<?php
require_once __DIR__ . '/../models/database.php';
require_once __DIR__ . '/../models/User.php';

use Models\Database;
use Models\User;

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$db = (new Database())->getConnection();
$userModel = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($email) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $userId = $_SESSION['user']['id'];

        $updateSuccess = $userModel->updateUser($userId, $username, $email, $hashedPassword);

        if ($updateSuccess) {
            $_SESSION['user']['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Failed to update profile.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<form method="POST" action="update_user.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['user']['username']) ?>" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Update</button>
</form>
