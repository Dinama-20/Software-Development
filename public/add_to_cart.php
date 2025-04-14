<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = json_decode(file_get_contents('php://input'), true);

    if ($product && isset($product['name'], $product['price'])) {
        // Asegurar que el carrito esté inicializado
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Verificar si el producto ya existe en el carrito
        foreach ($_SESSION['cart'] as &$cartItem) {
            if ($cartItem['name'] === $product['name']) {
                $cartItem['quantity']++;
                echo json_encode(['success' => true, 'message' => 'Cantidad actualizada']);
                exit;
            }
        }

        // Si el producto no existe, agregarlo como un nuevo ítem
        $product['quantity'] = 1;
        $_SESSION['cart'][] = $product;

        echo json_encode(['success' => true, 'message' => 'Producto añadido al carrito']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Datos del producto inválidos']);
exit;
