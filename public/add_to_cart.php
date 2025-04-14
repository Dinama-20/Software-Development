<?php
session_start();

// Set the response content type to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON payload from the request body
    $product = json_decode(file_get_contents('php://input'), true);

    // Validate the product data
    if ($product && isset($product['name'], $product['price'])) {
        // Initialize the cart in the session if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add the product to the cart
        $_SESSION['cart'][] = $product;

        // Send a success response
        echo json_encode(['success' => true, 'message' => 'Product added to cart']);
        exit;
    }
}

// Send an error response if the product data is invalid
echo json_encode(['success' => false, 'message' => 'Invalid product data']);
exit;
