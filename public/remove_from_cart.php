<?php
session_start();

if (isset($_GET['index']) && isset($_SESSION['cart'][$_GET['index']])) {
    if ($_SESSION['cart'][$_GET['index']]['quantity'] > 1) {
        // Reduce the quantity if more than 1
        $_SESSION['cart'][$_GET['index']]['quantity']--;
    } else {
        // Remove the product if quantity is 1
        unset($_SESSION['cart'][$_GET['index']]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the cart
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
exit;
