<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tu Carrito</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main id="content">
    <h1 class="welcome-message">Tu Carrito</h1>
    <div id="cart-container">
        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <ul style="list-style: none; padding: 0;">
                <?php 
                $total = 0;
                foreach ($_SESSION['cart'] as $item): 
                    $total += $item['price'];
                ?>
                    <li class="cart-product">
                        <div>
                            <h3 style="margin: 0;"><?= htmlspecialchars($item['name']) ?></h3>
                            <p style="margin: 0.5rem 0;">Precio: <?= number_format($item['price'], 2) ?>€</p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="cart-total">Total: <?= number_format($total, 2) ?>€</p>
        <?php else: ?>
            <p style="text-align: center; font-size: 1.2rem;">Tu carrito está vacío.</p>
        <?php endif; ?>
    </div>

    <div id="cart-actions">
        <form method="post" action="clear_cart.php">
            <button type="submit" class="custom-btn">Vaciar Carrito</button>
        </form>
        <?php if (!empty($_SESSION['cart'])): ?>
            <button class="custom-btn" onclick="checkout()">Comprar</button>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
