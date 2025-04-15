<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/database.php';

$db = (new \Models\Database())->getConnection();

$stmt = $db->prepare("
    SELECT p.id, p.name, p.price, p.image, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
");
$stmt->execute();
$cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($cart);
exit;
