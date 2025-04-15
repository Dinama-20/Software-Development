<?php
session_start();
require_once __DIR__ . '/../models/database.php';

header('Content-Type: application/json');

if (isset($_GET['product_id'])) {
    $db = (new \Models\Database())->getConnection();

    $stmt = $db->prepare("DELETE FROM cart WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $_GET['product_id']);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
exit;
