<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tu Carrito</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main id="content">
    <h1>Your Cart</h1>
    <div id="cart-container">
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li><?= htmlspecialchars($item['name']) ?> - <?= number_format($item['price'], 2) ?> euros</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <div id="cart-actions">
        <form method="post" action="clear_cart.php">
            <button type="submit" class="custom-btn">Clear Cart</button>
        </form>
        <button class="custom-btn" onclick="checkout()">Buy</button>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
