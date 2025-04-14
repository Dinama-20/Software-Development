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
                        <button class="remove-btn" onclick="removeFromCart(<?= $index ?>)">Remove</button>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div id="cart-actions">
                <button onclick="checkout()">Buy</button>
                <button onclick="clearCart()">Clear Cart</button>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <script>
        function removeFromCart(index) {
            fetch(`remove_from_cart.php?index=${index}`, { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Recarga la página para actualizar el carrito
                    } else {
                        alert('Failed to remove item from cart.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function checkout() {
            // Implement checkout functionality here
            alert('Proceeding to checkout...');
        }

        function clearCart() {
            fetch('clear_cart.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Recarga la página para actualizar el carrito
                    } else {
                        alert('Failed to clear cart.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
