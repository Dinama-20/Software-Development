<?php
// Start the session to access session variables
session_start();

// Destroy the session to log out the user
session_destroy(); // Remove all session data

// Redirect the user to the login page or homepage
header("Location: login.php"); // Redirect to the login page
exit(); // Ensure no further code is executed
?>
