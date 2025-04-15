<?php
include '../includes/header.php';

// Retrieve the cart from the session or initialize it as an empty array
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Handle POST requests for cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'clear_cart') {
            // Clear the cart by resetting the session variable
            $_SESSION['cart'] = [];
            header('Location: cart.php'); // Reload the page after clearing the cart
            exit;
        } elseif ($_POST['action'] === 'remove_item' && isset($_POST['product_id'])) {
            // Remove a specific item from the cart
            $productId = $_POST['product_id'];
            unset($_SESSION['cart'][$productId]);
            header('Location: cart.php'); // Reload the page after removing the item
            exit;
        }
    }
}
?>

<div class="cart-container">
    <h1>Your Cart</h1>
    <?php if (empty($cart)): ?>
        <!-- Display a message if the cart is empty -->
        <p>Your cart is empty.</p>
    <?php else: ?>
        <!-- Display the cart items in a table -->
        <table class="cart-table">
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
                        <td>€<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <!-- Form to remove an item from the cart -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="remove_item">
                                <input type="hidden" name="product_id" value="<?= $id ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-actions">
            <!-- Form to clear the entire cart -->
            <form method="POST">
                <input type="hidden" name="action" value="clear_cart">
                <button type="submit" class="cart-action-btn">Clear Cart</button>
            </form>
            <!-- Button to proceed to purchase -->
            <form method="GET" action="generate_pdf.php">
                <button type="submit" class="cart-action-btn">Buy</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
