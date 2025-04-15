<?php
// Start the session to access the cart
session_start();

// Check if the index is provided and the item exists in the cart
if (isset($_GET['index']) && isset($_SESSION['cart'][$_GET['index']])) {
    unset($_SESSION['cart'][$_GET['index']]); // Remove the item from the cart
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the cart
    echo json_encode(['success' => true]); // Return success response
} else {
    echo json_encode(['success' => false]); // Return failure response
}
exit; // Ensure no further code is executed
