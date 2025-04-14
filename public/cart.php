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
        <ul id="cart-items">
            <!-- Los elementos del carrito se cargarán dinámicamente aquí -->
        </ul>
        <div id="cart-actions">
            <button class="cart-action-btn" data-action="buy">Buy</button>
            <button class="cart-action-btn" data-action="clear">Clear Cart</button>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/cart-actions.js" defer></script>
</body>
</html>
