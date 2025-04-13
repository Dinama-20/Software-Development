<?php
echo '<link rel="stylesheet" href="assets/css/style.css">';
echo '<script src="assets/js/script.js" defer></script>';
?>

<header>
    <h1><a href="index.php" style="text-decoration: none; color: white;">Oñate Watch and Jewelry</a></h1>
    <nav>
        <div id="user-menu">
            <button onclick="window.location.href='login.php'">Login</button>
            <button onclick="window.location.href='register.php'">Register</button>
        </div>
        <button onclick="window.location.href='cart.php'">
            <img src="assets/images/cart.png" alt="Cart">
        </button>
        <button onclick="toggleDarkMode()">Toggle Dark Mode</button>
    </nav>
</header>
