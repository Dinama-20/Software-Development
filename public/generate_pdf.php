<?php
session_start();
require_once __DIR__ . '/../vendor/setasign/fpdf/fpdf.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty.']);
    exit;
}

$cart = $_SESSION['cart'];
$total = array_reduce($cart, fn($sum, $item) => $sum + $item['price'], 0);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Order Details');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
foreach ($cart as $item) {
    $pdf->Cell(0, 10, "Product: {$item['name']} - Price: " . number_format($item['price'], 2) . " euros", 0, 1);
}

$pdf->Ln(10);
$pdf->Cell(0, 10, "Total: " . number_format($total, 2) . " euros", 0, 1);
$pdf->Cell(0, 10, "Date and Time: " . date('Y-m-d H:i:s'), 0, 1);

$pdfFilePath = __DIR__ . '/order-details.pdf';
$pdf->Output('F', $pdfFilePath); // Guarda el PDF en el servidor

echo json_encode(['success' => true, 'pdfPath' => 'order-details.pdf']);
exit;
