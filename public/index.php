<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oñate Watch and Jewelry</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Corrige la ruta del CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="../assets/js/script.js" defer></script> <!-- Asegúrate de incluir el script correctamente -->
    <script>
        let products = [
            {name: 'Aquarius Nurburgring', price: 240, category: 'smartwatch', image: 'assets/images/duward-watch1.png', details: 'assets/images/characteristics1.png'},
            {name: 'Aquastar Race', price: 189, category: 'smartwatch', image: 'assets/images/duward-watch2.png', details: 'assets/images/characteristics2.png'},
            {name: 'Smartwatch Style', price: 98.90, category: 'smartwatch', image: 'assets/images/duward-watch3.png', details: 'assets/images/characteristics3.png'},
            {name: 'Lady Woman', price: 89, category: 'woman', image: 'assets/images/duward-watch4.png', details: 'assets/images/characteristics4.png'},
            {name: 'Lady Babaye', price: 95, category: 'woman', image: 'assets/images/duward-watch5.png', details: 'assets/images/characteristics5.png'},
            {name: 'Junior Divka', price: 39.90, category: 'junior', image: 'assets/images/duward-watch6.png', details: 'assets/images/characteristics6.png'},
            {name: 'Junior Dreng', price: 49.90, category: 'junior', image: 'assets/images/duward-watch7.png', details: 'assets/images/characteristics7.png'}
        ];

        function showModal(detailsImage) {
            const modalOverlay = document.getElementById("modalOverlay");
            const modalImage = document.getElementById("modalImage");

            if (modalOverlay && modalImage) {
                modalImage.src = detailsImage; // Carga la imagen de las características
                modalOverlay.style.display = "flex"; // Muestra el modal
            }
        }

        function closeModal() {
            const modalOverlay = document.getElementById("modalOverlay");
            if (modalOverlay) {
                modalOverlay.style.display = "none"; // Oculta el modal
            }
        }

        function addToCart(productName, price) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `name=${encodeURIComponent(productName)}&price=${encodeURIComponent(price)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(`${productName} added to cart!`);
                } else {
                    alert('Error adding product to cart.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function applyFilters() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const selectedCategory = document.getElementById('filterCategory').value;
            const selectedPriceSort = document.getElementById('sortPrice').value;

            let filteredProducts = products.filter(product => {
                const matchesSearch = product.name.toLowerCase().includes(searchInput);
                const matchesCategory = selectedCategory ? product.category === selectedCategory : true;
                return matchesSearch && matchesCategory;
            });

            if (selectedPriceSort === 'asc') {
                filteredProducts.sort((a, b) => a.price - b.price);
            } else if (selectedPriceSort === 'desc') {
                filteredProducts.sort((a, b) => b.price - a.price);
            }

            displayProducts(filteredProducts);
        }

        function displayProducts(products) {
            const productsContainer = document.getElementById("products");
            if (!productsContainer) return;

            productsContainer.innerHTML = ""; // Limpia el contenedor

            products.forEach(product => {
                const productDiv = document.createElement("div");
                productDiv.className = "product";
                productDiv.innerHTML = `
                    <img src="${product.image}" alt="${product.name}" class="product-image">
                    <h2>${product.name}</h2>
                    <p>Price: ${product.price}€</p>
                    <div class="product-actions">
                        <button class="details-btn" onclick="showModal('${product.details}')">Details</button>
                        <button class="add-to-cart-btn" onclick="addToCart('${product.name}', ${product.price})">Add to Cart</button>
                    </div>
                `;
                productsContainer.appendChild(productDiv);
            });
        }

        window.onload = applyFilters;

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main id="content">
    <?php if (isset($_SESSION['user'])): ?>
        <p class="welcome-message">
            Welcome back, <?= htmlspecialchars($_SESSION['user']['username']); ?>!
        </p>
    <?php else: ?>
        <p class="welcome-message">
            Welcome to Oñate Watch and Jewelry! Please <a href="login.php">log in</a> to access your account.
        </p>
    <?php endif; ?>

    <section id="product-controls">
        <input type="text" id="searchInput" placeholder="Search products by name">
        <select id="filterCategory">
            <option value="">All Categories</option>
            <option value="smartwatch">Smartwatch</option>
            <option value="woman">Woman</option>
            <option value="junior">Junior</option>
        </select>
        <select id="sortPrice">
            <option value="">Sort by Price</option>
            <option value="asc">Low to High</option>
            <option value="desc">High to Low</option>
        </select>
        <button onclick="applyFilters()">Apply</button>
    </section>

    <section id="products">
        <h1>Available Products</h1>
        <!-- Los productos se cargarán dinámicamente aquí -->
    </section>
</main>

<?php include '../includes/footer.php'; ?>

<div id="modalOverlay" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <img id="modalImage" src="" alt="Product Details">
        <button class="close-button" onclick="closeModal()">X</button>
    </div>
</div>
</body>
</html>
