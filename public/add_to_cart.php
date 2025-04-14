<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product = [
        'name' => $_POST['name'],
        'price' => $_POST['price']
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = $product;

    echo json_encode(['success' => true]);
}
