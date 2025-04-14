<?php
session_start();
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
        // Array of products with details such as name, price, category, and images
        let products = [
            { name: 'Aquarius Nurburgring', price: 240, category: 'men', image: '../assets/images/duward-watch1.png', detailsImage: '../assets/images/characteristics1.png' },
            { name: 'Aquastar Race', price: 189, category: 'men', image: '../assets/images/duward-watch2.png', detailsImage: '../assets/images/characteristics2.png' },
            { name: 'Smartwatch Style', price: 98.90, category: 'smartwatch', image: '../assets/images/duward-watch3.png', detailsImage: '../assets/images/characteristics3.png' },
            { name: 'Lady Woman', price: 89, category: 'woman', image: '../assets/images/duward-watch4.png', detailsImage: '../assets/images/characteristics4.png' },
            { name: 'Lady Babaye', price: 95, category: 'woman', image: '../assets/images/duward-watch5.png', detailsImage: '../assets/images/characteristics5.png' },
            { name: 'Junior Divka', price: 39.90, category: 'junior', image: '../assets/images/duward-watch6.png', detailsImage: '../assets/images/characteristics6.png' },
            { name: 'Junior Dreng', price: 49.90, category: 'junior', image: '../assets/images/duward-watch7.png', detailsImage: '../assets/images/characteristics7.png' }
        ];

        // Function to show a modal with product details image
        function showModal(detailsImage) {
            const modalOverlay = document.getElementById("modalOverlay");
            const modalImage = document.getElementById("modalImage");

            if (modalOverlay && modalImage) {
                modalImage.src = detailsImage; // Set the image source for the modal
                modalImage.alt = "Product Details"; // Add alt text for accessibility

                modalOverlay.style.display = "flex"; // Display the modal
            }
        }

        // Function to close the modal
        function closeModal() {
            const modalOverlay = document.getElementById("modalOverlay");
            if (modalOverlay) {
                modalOverlay.style.display = "none"; // Hide the modal
            }
        }

        // Function to add a product to the cart
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

        // Function to apply filters based on search input, category, and price sorting
        function applyFilters() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const selectedCategory = document.getElementById('filterCategory').value.toLowerCase(); // Ensure lowercase comparison
            const selectedPriceSort = document.getElementById('sortPrice').value;

            let filteredProducts = products.filter(product => {
                const matchesSearch = product.name.toLowerCase().includes(searchInput);
                const matchesCategory = selectedCategory ? product.category === selectedCategory : true; // Ensure exact match for category
                return matchesSearch && matchesCategory;
            });

            if (selectedPriceSort === 'asc') {
                filteredProducts.sort((a, b) => a.price - b.price);
            } else if (selectedPriceSort === 'desc') {
                filteredProducts.sort((a, b) => b.price - a.price);
            }

            displayProducts(filteredProducts);
        }

        // Function to display products dynamically on the webpage
        function displayProducts(products) {
            const productsContainer = document.getElementById("products");
            if (!productsContainer) return;

            productsContainer.innerHTML = ""; // Clear the container

            products.forEach(product => {
                const productDiv = document.createElement("div");
                productDiv.className = "product";
                productDiv.innerHTML = `
                    <img src="${product.image}" alt="${product.name}" class="product-image" loading="lazy" onclick="showModal('${product.detailsImage}')">
                    <h2>${product.name}</h2>
                    <p>Price: ${product.price}€</p>
                    <button class="add-to-cart-btn" onclick="addToCart('${product.name}', ${product.price})">Add to Cart</button>
                `;
                productsContainer.appendChild(productDiv);
            });
        }

        // Apply filters when the page loads
        window.onload = applyFilters;

        // Function to toggle dark mode for the webpage
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
            <option value="men">Men</option>
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

    <div id="modalOverlay" class="modal-overlay">
        <div class="modal-content">
            <img id="modalImage" src="" alt="Product Details"> <!-- Modal image for product details -->
            <button class="close-button" onclick="closeModal()">X</button> <!-- Button to close the modal -->
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
