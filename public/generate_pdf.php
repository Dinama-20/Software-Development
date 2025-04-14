<?php
session_start();
require_once '../vendor/setasign/fpdf/fpdf.php'; // Asegúrate de que esta ruta sea correcta

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'];
$total = array_reduce($cart, function ($sum, $item) {
    return $sum + $item['price'];
}, 0);

$pdf = new FPDF();
$pdf->AddPage();

// Configura la fuente predeterminada de FPDF (Arial)
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Order Details');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
foreach ($cart as $item) {
    $priceWithText = number_format($item['price'], 2) . ' euros'; // Cambia el formato del precio
    $pdf->Cell(0, 10, "Product: {$item['name']} - Price: {$priceWithText}", 0, 1);
}

$pdf->Ln(10);
$totalWithText = number_format($total, 2) . ' euros'; // Cambia el formato del total
$pdf->Cell(0, 10, "Total: {$totalWithText}", 0, 1);
$pdf->Cell(0, 10, "Date and Time: " . date('Y-m-d H:i:s'), 0, 1);

// Vacía el carrito después de generar el PDF
unset($_SESSION['cart']);

// Genera el PDF y envíalo al navegador
$pdf->Output('D', 'order-details.pdf');
exit;
