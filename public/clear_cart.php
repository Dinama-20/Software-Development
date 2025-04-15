<?php
session_start();
require_once __DIR__ . '/../models/database.php';

$db = (new \Models\Database())->getConnection();

// Vaciar la tabla del carrito
$stmt = $db->prepare("DELETE FROM cart");
$stmt->execute();

header("Location: cart.php");
exit;
?>
