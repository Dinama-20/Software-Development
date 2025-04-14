<?php
session_start();
require_once __DIR__ . '/../vendor/setasign/fpdf/fpdf.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die('Cart is empty. Please add items to the cart before generating a PDF.');
}

if (!isset($_SESSION['user'])) {
    die('User is not logged in. Please log in to proceed.');
}

$cart = $_SESSION['cart'];
$user = $_SESSION['user']; // Fetch user data from the session
$total = array_reduce($cart, fn($sum, $item) => $sum + $item['price'], 0);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Order Details', 0, 1, 'C');
$pdf->Ln(10);

// Add user details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Customer: {$user['username']}", 0, 1);
$pdf->Cell(0, 10, "Email: {$user['email']}", 0, 1);
$pdf->Ln(10);

// Add cart details
foreach ($cart as $item) {
    $pdf->Cell(0, 10, "Product: {$item['name']} - Price: " . number_format($item['price'], 2) . " euros", 0, 1);
}

$pdf->Ln(10);
$pdf->Cell(0, 10, "Total: " . number_format($total, 2) . " euros", 0, 1);
$pdf->Cell(0, 10, "Date and Time: " . date('Y-m-d H:i:s'), 0, 1);

// Clear the cart after generating the PDF
unset($_SESSION['cart']);

// Send the PDF to the browser for download
$pdf->Output('D', 'order-details.pdf');
exit;
