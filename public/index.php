<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oñate Watch and Jewelry</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userMenu = document.getElementById('user-menu');
            const firstName = localStorage.getItem('userFirstName');
            const lastName = localStorage.getItem('userLastName');
            const email = localStorage.getItem('userEmail');

            if (firstName && lastName && email) {
                userMenu.innerHTML = `
                    <p>Welcome, ${firstName} ${lastName}</p>
                    <button onclick="logout()">Logout</button>
                `;
            }
        });

        function logout() {
            localStorage.removeItem('userFirstName');
            localStorage.removeItem('userLastName');
            localStorage.removeItem('userEmail');
            location.reload();
        }

        function showModal(imageSrc) {
            document.getElementById("modalImage").src = imageSrc;
            document.getElementById("modalOverlay").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("modalOverlay").style.display = "none";
        }
    </script>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main id="content">
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
            <div class="product" onclick="showModal('assets/images/characteristics1.png')">
                <img src="assets/images/duward-watch1.png" alt="Aquarius Nurburgring">
                <h2>Aquarius Nurburgring</h2>
                <p>Price: 240€</p>
                <button onclick="addToCart('Aquarius Nurburgring', 240.00)">Add to Cart</button>
            </div>

            <div class="product" onclick="showModal('assets/images/characteristics2.png')">
                <img src="assets/images/duward-watch2.png" alt="Aquastar Race">
                <h2>Aquastar Race</h2>
                <p>Price: 189€</p>
                <button onclick="addToCart('Aquastar Race', 189.00)">Add to Cart</button>
            </div>

            <div class="product" onclick="showModal('assets/images/characteristics3.png')">
                <img src="assets/images/duward-watch3.png" alt="Smartwatch Style">
                <h2>Smartwatch Style</h2>
                <p>Price: 98.90€</p>
                <button onclick="addToCart('Smartwatch Style', 98.90)">Add to Cart</button>
            </div>

            <div class="product" onclick="showModal('assets/images/characteristics4.png')">
                <img src="assets/images/duward-watch4.png" alt="Lady Woman">
                <h2>Lady Woman</h2>
                <p>Price: 89€</p>
                <button onclick="addToCart('Lady Woman', 89.00)">Add to Cart</button>
            </div>

            <div class="product" onclick="showModal('assets/images/characteristics5.png')">
                <img src="assets/images/duward-watch5.png" alt="Lady Babaye">
                <h2>Lady Babaye</h2>
                <p>Price: 95€</p>
                <button onclick="addToCart('Lady Babaye', 95.00)">Add to Cart</button>
            </div>

            <div class="product" onclick="showModal('assets/images/characteristics6.png')">
                <img src="assets/images/duward-watch6.png" alt="Junior Divka">
                <h2>Junior Divka</h2>
                <p>Price: $39.90</p>
                <button onclick="addToCart('Junior Divka', 39.90)">Add to Cart</button>
            </div>

            <div class="product" onclick="showModal('assets/images/characteristics7.png')">
                <img src="assets/images/duward-watch7.png" alt="Junior Dreng">
                <h2>Junior Dreng</h2>
                <p>Price: $49.90</p>
                <button onclick="addToCart('Junior Dreng', 49.90)">Add to Cart</button>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <div id="modalOverlay" class="modal-overlay">
        <div class="modal-content">
            <img id="modalImage" src="" alt="Características del producto">
            <button class="close-button" onclick="closeModal()">X</button>
        </div>
    </div>
</body>
</html>
