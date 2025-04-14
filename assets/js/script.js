function login(event) {
    event.preventDefault();
    const email = document.getElementById("login-email").value.trim();
    const user = JSON.parse(localStorage.getItem("user"));

    if (user && user.email === email) {
        alert("Login successful");
        localStorage.setItem("isLoggedIn", "true");
        window.location.href = "index.php";
    } else {
        alert("Invalid email or user not registered");
    }
}

function registerUser(event) {
    event.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!name || !email || !phone || !password) {
        alert("Please fill in all fields");
        return;
    }

    const user = { name, email, phone, password };
    localStorage.setItem("user", JSON.stringify(user));
    localStorage.setItem("isLoggedIn", "true");

    alert("User registered successfully");
    window.location.href = "index.php";
}

// Add a product to the cart
function addToCart(productName, price) {
    const product = { name: productName, price: price };
    fetch('../public/add_to_cart.php', { // Asegúrate de que la ruta sea correcta
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(product)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(`${productName} added to cart!`);
        } else {
            alert(data.message || "Failed to add product to cart.");
        }
    })
    .catch(error => console.error("Error:", error));
}

function displayCart() {
    const cartContainer = document.getElementById('cart-container');
    if (!cartContainer) return;
    fetch("get_cart.php")
        .then(response => response.json())
        .then(cart => {
            cartContainer.innerHTML = cart.length ? "" : "<p>Your cart is empty</p>";
            let totalPrice = 0;
            cart.forEach((product, index) => {
                const productElement = document.createElement('div');
                productElement.classList.add('cart-product');
                productElement.innerHTML = `
                    <p><strong>${product.name}</strong></p>
                    <p>Price: €${product.price.toFixed(2)}</p>
                    <button class="custom-btn" onclick="removeFromCart(${index})">Remove</button>
                `;
                cartContainer.appendChild(productElement);
                totalPrice += parseFloat(product.price);
            });
            if (cart.length > 0) {
                const totalElement = document.createElement('div');
                totalElement.classList.add('cart-total');
                totalElement.innerHTML = `<p>Total: €${totalPrice.toFixed(2)}</p>`;
                cartContainer.appendChild(totalElement);
            }
        });
}

function removeFromCart(index) {
    fetch(`remove_from_cart.php?index=${index}`)
        .then(() => displayCart())
        .catch(error => console.error("Error removing item:", error));
}

function clearCart() {
    fetch("clear_cart.php", { method: "POST" })
        .then(() => {
            displayCart(); // Actualiza la vista del carrito
            alert("Cart cleared");
        })
        .catch(error => console.error("Error clearing cart:", error));
}

// Checkout functionality to generate a PDF
function checkout() {
    console.log("Checkout process started..."); // Depuración

    fetch("get_cart.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch cart data");
            }
            return response.json();
        })
        .then(cart => {
            console.log("Cart data fetched:", cart); // Depuración
            if (cart.length === 0) {
                alert("Your cart is empty. Add products before checking out.");
                return;
            }

            // Redirige al archivo PHP que genera el PDF
            window.location.href = "generate_pdf.php";

            // Vacía el carrito después de la compra
            fetch("clear_cart.php", { method: "POST" })
                .then(() => {
                    console.log("Cart cleared successfully."); // Depuración
                    displayCart(); // Actualiza la vista del carrito
                })
                .catch(error => {
                    console.error("Error clearing cart:", error);
                    alert("Failed to clear the cart. Please try again.");
                });
        })
        .catch(error => {
            console.error("Error during checkout:", error);
            alert("Failed to complete the checkout process. Please try again.");
        });
}

function generarPDF(cart) {
    console.log("Generating PDF..."); // Depuración
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const total = cart.reduce((sum, item) => sum + item.price, 0);
    const fechaHora = new Date().toLocaleString();

    doc.setFontSize(18);
    doc.text("Order Details", 20, 20);

    doc.setFontSize(12);
    let yOffset = 30;
    cart.forEach(product => {
        doc.text(`Product: ${product.name} - Price: €${product.price.toFixed(2)}`, 20, yOffset);
        yOffset += 10;
    });

    doc.text(`Total: €${total.toFixed(2)}`, 20, yOffset + 10);
    doc.text(`Date and Time: ${fechaHora}`, 20, yOffset + 20);

    doc.save("order-details.pdf");
    console.log("PDF generated successfully."); // Depuración
}

function verifyLogin() {
    const user = JSON.parse(localStorage.getItem("user"));
    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

    if (user && isLoggedIn) {
        const loginBtn = document.getElementById("login-btn");
        const registerBtn = document.getElementById("register-btn");
        if (loginBtn) loginBtn.style.display = "none";
        if (registerBtn) registerBtn.style.display = "none";

        const userMenu = document.getElementById("user-menu");
        if (userMenu && !document.getElementById("user-menu-btn")) {
            const usernameBtn = document.createElement("button");
            usernameBtn.id = "user-menu-btn";
            usernameBtn.textContent = user.name;
            usernameBtn.onclick = showUserMenu;
            userMenu.appendChild(usernameBtn);
        }
    }
}

function showUserMenu() {
    const existing = document.getElementById("user-dropdown");
    if (existing) {
        existing.remove();
        return;
    }

    const menu = document.createElement("div");
    menu.id = "user-dropdown";
    menu.style.position = "absolute";
    menu.style.top = "50px";
    menu.style.right = "20px";
    menu.style.backgroundColor = "#fff";
    menu.style.border = "1px solid #ccc";
    menu.style.padding = "10px";
    menu.style.zIndex = "1000";

    const options = [
        { text: "My Profile", action: showProfile },
        { text: "Settings", action: showSettings },
        { text: "Logout", action: logout }
    ];

    options.forEach(option => {
        const button = document.createElement("button");
        button.textContent = option.text;
        button.onclick = option.action;
        menu.appendChild(button);
        menu.appendChild(document.createElement("br"));
    });

    document.body.appendChild(menu);
}

function logout() {
    localStorage.removeItem("user");
    localStorage.removeItem("isLoggedIn");
    window.location.href = "index.php";
}

// Toggle dark mode functionality
function toggleDarkMode() {
    const body = document.body;
    body.classList.toggle('dark-mode');
    const isDarkMode = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
    // El texto del botón no cambiará
}

// Initialize dark mode based on saved preference
function initDarkMode() {
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
    }
    // No se configura texto inicial del botón
}

// Initialize the page on load
window.onload = function () {
    initDarkMode();
    verifyLogin();
    displayCart();
    loadProductsFromDB();
};

function loadProductsFromDB() {
    // Simula la carga de productos desde una base de datos o archivo local
    const products = [
        { name: 'Aquarius Nurburgring', price: 240, category: 'smartwatch', image: 'assets/images/duward-watch1.png', details: 'assets/images/characteristics1.png' },
        { name: 'Aquastar Race', price: 189, category: 'smartwatch', image: 'assets/images/duward-watch2.png', details: 'assets/images/characteristics2.png' },
        { name: 'Smartwatch Style', price: 98.90, category: 'smartwatch', image: 'assets/images/duward-watch3.png', details: 'assets/images/characteristics3.png' },
        { name: 'Lady Woman', price: 89, category: 'woman', image: 'assets/images/duward-watch4.png', details: 'assets/images/characteristics4.png' },
        { name: 'Lady Babaye', price: 95, category: 'woman', image: 'assets/images/duward-watch5.png', details: 'assets/images/characteristics5.png' },
        { name: 'Junior Divka', price: 39.90, category: 'junior', image: 'assets/images/duward-watch6.png', details: 'assets/images/characteristics6.png' },
        { name: 'Junior Dreng', price: 49.90, category: 'junior', image: 'assets/images/duward-watch7.png', details: 'assets/images/characteristics7.png' }
    ];

    // Guarda los productos en el almacenamiento local
    localStorage.setItem('allProducts', JSON.stringify(products));
    displayProducts(products); // Muestra los productos al cargar
}

function filterByCategory(category) {
    const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
    return allProducts.filter(p => p.category === category);
}

function searchByName(term) {
    const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
    return allProducts.filter(p => p.name.toLowerCase().includes(term.toLowerCase()));
}

function sortByPrice(order = "asc") {
    const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
    return allProducts.sort((a, b) => order === "asc" ? a.price - b.price : b.price - a.price);
}

function applyFilters() {
    let products = JSON.parse(localStorage.getItem('allProducts')) || [];
    const searchTerm = document.getElementById("searchInput").value.toLowerCase();
    const category = document.getElementById("filterCategory").value;
    const sortOrder = document.getElementById("sortPrice").value;

    // Filtra por nombre
    if (searchTerm) {
        products = products.filter(p => p.name.toLowerCase().includes(searchTerm));
    }

    // Filtra por categoría
    if (category) {
        products = products.filter(p => p.category.toLowerCase() === category.toLowerCase());
    }

    // Ordena por precio
    if (sortOrder === "asc") {
        products.sort((a, b) => a.price - b.price);
    } else if (sortOrder === "desc") {
        products.sort((a, b) => b.price - a.price);
    }

    displayProducts(products); // Muestra los productos filtrados
}

function displayProducts(products) {
    const productsContainer = document.getElementById("products");
    if (!productsContainer) return;

    productsContainer.innerHTML = ""; // Limpia el contenedor

    products.forEach(product => {
        const productDiv = document.createElement("div");
        productDiv.className = "product";
        productDiv.innerHTML = `
            <img src="${product.image}" alt="${product.name}" onclick="showModal('${product.details}')">
            <h2>${product.name}</h2>
            <p>Price: ${product.price}€</p>
            <button onclick="addToCart('${product.name}', ${product.price})">Add to Cart</button>
        `;
        productsContainer.appendChild(productDiv);
    });
}

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

function toggleDropdown(event) {
    event.stopPropagation();
    const dropdown = event.target.closest('.dropdown');
    dropdown.classList.toggle('show');
}

window.onclick = function () {
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => dropdown.classList.remove('show'));
};

// Verificar si estamos en la página del carrito y delegar la funcionalidad al archivo cart.js
if (window.location.pathname.includes('cart.php')) {
    console.log('Cart page detected. Delegating functionality to cart.js.');
    // No duplicar funciones relacionadas con el carrito aquí
} else {
    // Funciones generales que no interfieren con el carrito
    console.log('General page detected. Running script.js functionality.');
}

document.addEventListener('DOMContentLoaded', () => {
    const clearCartButton = document.querySelector('button[type="submit"][value="clear_cart"]');
    const buyButton = document.querySelector('button[type="submit"][value="buy"]');

    if (clearCartButton) {
        clearCartButton.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to clear the cart?')) {
                e.preventDefault();
            }
        });
    }

    if (buyButton) {
        buyButton.addEventListener('click', (e) => {
            e.preventDefault(); // Evita el envío del formulario
            window.location.href = 'generate_pdf.php'; // Redirige a la generación del PDF
        });
    }
});