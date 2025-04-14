<?php
session_start();
require_once '../vendor/autoload.php'; // Usa el autoloader de Composer

use FPDF;

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty.'); window.location.href = 'cart.php';</script>";
    exit;
}

$cart = $_SESSION['cart'];
$total = array_reduce($cart, function ($sum, $item) {
    return $sum + $item['price'];
}, 0);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Order Details');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
foreach ($cart as $item) {
    $pdf->Cell(0, 10, "Product: {$item['name']} - Price: €" . number_format($item['price'], 2), 0, 1);
}

$pdf->Ln(10);
$pdf->Cell(0, 10, "Total: €" . number_format($total, 2), 0, 1);
$pdf->Cell(0, 10, "Date and Time: " . date('Y-m-d H:i:s'), 0, 1);

// Vacía el carrito después de generar el PDF
unset($_SESSION['cart']);

// Genera el PDF y envíalo al navegador
$pdf->Output('D', 'order-details.pdf');
exit;
