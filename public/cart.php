<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
                        <strong><?= htmlspecialchars($item['name']) ?></strong> - <?= number_format($item['price'], 2) ?> euros
                        <button class="remove-btn" data-index="<?= $index ?>">Remove</button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div id="cart-actions">
                <button id="buy-btn" class="cart-action-btn">Buy</button>
                <button id="clear-cart-btn" class="cart-action-btn">Clear Cart</button>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/cart-actions.js" defer></script>
</body>
</html>
