<?php
session_start();

if (isset($_GET['index']) && isset($_SESSION['cart'][$_GET['index']])) {
    unset($_SESSION['cart'][$_GET['index']]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindexar el carrito
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
exit;
