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
                <button onclick="window.location.href='logout.php'">Log Out</button>
            <?php else: ?>
                <button onclick="window.location.href='login.php'">Login</button>
                <button onclick="window.location.href='register.php'">Register</button>
            <?php endif; ?>
        </div>
        <button onclick="window.location.href='cart.php'">
            <img src="assets/images/cart.png" alt="Cart">
        </button>
        <button id="dark-mode-toggle" onclick="toggleDarkMode()">Toggle Dark Mode</button>
    </nav>
</header>
