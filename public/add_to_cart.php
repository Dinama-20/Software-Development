<?php
session_start();
require_once __DIR__ . '/../models/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = json_decode(file_get_contents('php://input'), true);

    if ($product && isset($product['id'])) {
        $db = (new \Models\Database())->getConnection();

        // Verificar si el producto ya está en el carrito
        $stmt = $db->prepare("SELECT id FROM cart WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product['id']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Incrementar la cantidad si ya existe
            $stmt = $db->prepare("UPDATE cart SET quantity = quantity + 1 WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $product['id']);
        } else {
            // Insertar un nuevo producto en el carrito
            $stmt = $db->prepare("INSERT INTO cart (product_id, quantity) VALUES (:product_id, 1)");
            $stmt->bindParam(':product_id', $product['id']);
        }

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product added to cart']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product to cart']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product data']);
    }
}
exit;
