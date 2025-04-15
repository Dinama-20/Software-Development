<?php
session_start();
require_once __DIR__ . '/../models/database.php';

$db = (new \Models\Database())->getConnection();

$stmt = $db->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oñate Watch and Jewelry</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to the CSS file for styling -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> <!-- Include jsPDF library -->
    <script src="../assets/js/script.js" defer></script> <!-- Include custom JavaScript file -->
    <script>
        let products = <?= json_encode($products); ?>;

        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart!');
                } else {
                    alert(data.message || 'Failed to add product to cart.');
                }
            });
        }

        function displayProducts(products) {
            const productsContainer = document.getElementById("products");
            productsContainer.innerHTML = "";

            products.forEach(product => {
                const productDiv = document.createElement("div");
                productDiv.className = "product";
                productDiv.innerHTML = `
                    <img src="${product.image}" alt="${product.name}" class="product-image">
                    <h2>${product.name}</h2>
                    <p>Price: ${product.price}€</p>
                    <button onclick="addToCart(${product.id})">Add to Cart</button>
                `;
                productsContainer.appendChild(productDiv);
            });
        }

        window.onload = () => displayProducts(products);
    </script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main id="content">
    <?php if (isset($_SESSION['user'])): ?>
        <p class="welcome-message">
            Welcome back, <?= htmlspecialchars($_SESSION['user']['username']); ?>! <!-- Display welcome message for logged-in users -->
        </p>
    <?php else: ?>
        <p class="welcome-message">
            Welcome to Oñate Watch and Jewelry! Please <a href="login.php">log in</a> to access your account. <!-- Display message for guests -->
        </p>
    <?php endif; ?>

    <section id="product-controls">
        <input type="text" id="searchInput" placeholder="Search products by name"> <!-- Input field for searching products -->
        <select id="filterCategory"> <!-- Dropdown for filtering by category -->
            <option value="">All Categories</option>
            <option value="smartwatch">Smartwatch</option>
            <option value="woman">Woman</option>
            <option value="junior">Junior</option>
        </select>
        <select id="sortPrice"> <!-- Dropdown for sorting by price -->
            <option value="">Sort by Price</option>
            <option value="asc">Low to High</option>
            <option value="desc">High to Low</option>
        </select>
        <button onclick="applyFilters()">Apply</button> <!-- Button to apply filters -->
    </section>

    <section id="products">
        <h1>Available Products</h1>
        <!-- Products will be dynamically loaded here -->
    </section>

    <div id="modalOverlay" class="modal-overlay" style="display: none;"></div> <!-- Remove modal content -->
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
