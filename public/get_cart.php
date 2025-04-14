<?php
session_start(); // Start the session to access session variables
header('Content-Type: application/json'); // Set the content type to JSON

// Return the cart as a JSON response
if (isset($_SESSION['cart'])) {
    echo json_encode($_SESSION['cart']); // Encode the cart array as JSON and send it as a response
} else {
    echo json_encode([]); // Return an empty array if the cart is not set
}
exit; // Terminate the script
