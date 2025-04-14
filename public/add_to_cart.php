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

        // Check if the product already exists in the cart
        foreach ($_SESSION['cart'] as &$cartItem) {
            if ($cartItem['name'] === $product['name']) {
                $cartItem['quantity']++;
                echo json_encode(['success' => true, 'message' => 'Product quantity updated']);
                exit;
            }
        }

        // If the product is not found, add it as a new item
        $product['quantity'] = 1;
        $_SESSION['cart'][] = $product;

        echo json_encode(['success' => true, 'message' => 'Product added to cart']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid product data']);
exit;
