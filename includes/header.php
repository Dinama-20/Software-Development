<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/script.js" defer></script>

<header>
    <h1><a href="index.php" style="text-decoration: none; color: white;">Oñate Watch and Jewelry</a></h1>
    <nav>
        <div id="user-menu">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="dropdown">
                    <button class="dropbtn" onclick="toggleDropdown(event)"><?= htmlspecialchars($_SESSION['user']['username']) ?></button>
                    <div class="dropdown-content">
                        <a href="update_user.php">Update Profile</a>
                        <a href="logout.php">Log Out</a>
                    </div>
                </div>
            <?php else: ?>
                <button onclick="window.location.href='login.php'">Login</button>
                <button onclick="window.location.href='register.php'">Register</button>
            <?php endif; ?>
        </div>
        <div class="dropdown">
            <button class="dropbtn" onclick="toggleDropdown(event)">Menu</button>
            <div class="dropdown-content">
                <a href="index.php">Home</a>
                <a href="cart.php">Cart</a>
                <a href="reparations.php">Repairs</a>
                <button id="dark-mode-toggle" onclick="toggleDarkMode()">Toggle Dark Mode</button>
            </div>
        </div>
    </nav>
</header>
