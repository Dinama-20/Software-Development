<?php
// Start the session to access session variables
session_start();

// Check if the cart exists in the session and clear it
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']); // Remove the cart from the session
}

// Redirect the user to the shopping cart page or homepage
header("Location: cart.php"); // Redirect to the cart page
exit(); // Ensure no further code is executed
?>
