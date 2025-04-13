<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <main id="content">
        <h1>Your Cart</h1>
        <div id="cart-container"></div>
        <div id="cart-actions">
            <button class="custom-btn" onclick="clearCart()">Clear Cart</button>
            <button class="custom-btn" onclick="checkout()">Buy</button>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>

    <script>
        window.onload = function() {
            displayCart(); 
        };
    </script>
</body>
</html>
