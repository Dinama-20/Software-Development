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
                <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                    <li>
                        <?= htmlspecialchars($item['name']) ?> - <?= number_format($item['price'], 2) ?> euros
                        <button class="remove-btn" data-index="<?= $index ?>">Remove</button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div id="cart-actions">
                <button id="buy-btn">Buy</button>
                <button id="clear-cart-btn">Clear Cart</button>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <script src="../assets/js/cart.js" defer></script>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
