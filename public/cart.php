<?php
include 'header.php';

// Simulación de productos en el carrito (esto debería venir de la sesión o base de datos)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'clear_cart') {
            $_SESSION['cart'] = [];
            header('Location: cart.php');
            exit;
        } elseif ($_POST['action'] === 'remove_item' && isset($_POST['product_id'])) {
            $productId = $_POST['product_id'];
            unset($_SESSION['cart'][$productId]);
            header('Location: cart.php');
            exit;
        } elseif ($_POST['action'] === 'buy') {
            // Aquí se generaría el PDF (puedes usar una librería como FPDF o TCPDF)
            // Por simplicidad, solo redirigimos a una página de confirmación
            header('Location: confirmation.php');
            exit;
        }
    }
}
?>

<div class="cart-container">
    <h1>Your Cart</h1>
    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $id => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['price']) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="remove_item">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form method="POST">
            <input type="hidden" name="action" value="clear_cart">
            <button type="submit">Clear Cart</button>
        </form>
        <form method="POST">
            <input type="hidden" name="action" value="buy">
            <button type="submit">Buy</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
