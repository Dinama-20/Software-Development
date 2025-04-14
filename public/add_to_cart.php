<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = json_decode(file_get_contents('php://input'), true);

    if ($product && isset($product['id'], $product['name'], $product['price'])) {
        // Asegúrate de que el carrito esté inicializado
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][] = $product;

        echo json_encode(['success' => true]);
        exit;
    }
}

// Si algo falla:
echo json_encode(['success' => false, 'message' => 'Invalid product data']);
exit;
