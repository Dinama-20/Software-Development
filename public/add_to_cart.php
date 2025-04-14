<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = json_decode(file_get_contents('php://input'), true);

    if ($product && isset($product['name'], $product['price'])) {
        // Ensure the cart is initialized
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][] = $product;

        echo json_encode(['success' => true, 'message' => 'Product added to cart']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid product data']);
exit;
