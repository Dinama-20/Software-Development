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
    $userId = $_SESSION['user']['id'];

    if ($userModel->isUsernameTaken($username, $userId)) {
        $error = "This username is not available.";
    } elseif ($userModel->isEmailTaken($email, $userId)) {
        $error = "This email is not available.";
    } elseif (!empty($password) && password_verify($password, $_SESSION['user']['password'])) {
        $error = "This is the same password.";
    } else {
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $_SESSION['user']['password'];
        $updateSuccess = $userModel->updateUser($userId, $username, $email, $hashedPassword);

        if ($updateSuccess) {
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['password'] = $hashedPassword;
            $success = "Profile updated successfully.";
        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<?php if (isset($error)): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if (isset($success)): ?>
    <p class="message success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>




    <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['user']['username']) ?>" required>    <label for="username">Username:</label><form method="POST" action="update_user.php" class="user-settings-form">    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter new password">
    <button type="submit">Update</button>
</form>
