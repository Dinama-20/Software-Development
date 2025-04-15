// Handles user login by validating email against stored user data
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

// Registers a new user and stores their data in localStorage
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

// Adds a product to the cart by sending a POST request to the server
function addToCart(productId) {
    const product = products.find(p => p.id === productId); // Busca el producto por ID en la lista de productos

    if (!product) {
        alert("Product not found.");
        return;
    }

    fetch('add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: product.id,
            name: product.name,
            price: product.price
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart!');
        } else {
            alert(data.message || 'Failed to add product to cart.');
        }
    })
    .catch(error => console.error("Error:", error));
}

// Displays the cart contents by fetching data from the server
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

// Removes an item from the cart by sending its index to the server
function removeFromCart(index) {
    fetch(`remove_from_cart.php?index=${index}`)
        .then(() => displayCart())
        .catch(error => console.error("Error removing item:", error));
}

// Clears the entire cart by sending a POST request to the server
function clearCart() {
    fetch("clear_cart.php", { method: "POST" })
        .then(() => {
            displayCart(); // Updates the cart view
            alert("Cart cleared");
        })
        .catch(error => console.error("Error clearing cart:", error));
}

// Handles the checkout process and generates a PDF of the order
function checkout() {
    console.log("Checkout process started...");
    fetch("get_cart.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to fetch cart data");
            }
            return response.json();
        })
        .then(cart => {
            console.log("Cart data fetched:", cart);
            if (cart.length === 0) {
                alert("Your cart is empty. Add products before checking out.");
                return;
            }

            // Redirects to the PHP file that generates the PDF
            window.location.href = "generate_pdf.php";

            // Clears the cart after purchase
            fetch("clear_cart.php", { method: "POST" })
                .then(() => {
                    console.log("Cart cleared successfully.");
                    displayCart(); // Updates the cart view
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

// Generates a PDF document with cart details
function generarPDF(cart) {
    console.log("Generating PDF...");
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
    console.log("PDF generated successfully.");
}

// Verifies if the user is logged in and updates the UI accordingly
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

// Shows a dropdown menu with user options
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

// Logs out the user by clearing localStorage and redirecting to the homepage
function logout() {
    localStorage.removeItem("user");
    localStorage.removeItem("isLoggedIn");
    window.location.href = "index.php";
}

// Toggles dark mode by adding or removing a class from the body
function toggleDarkMode() {
    const body = document.body;
    body.classList.toggle('dark-mode');
    const isDarkMode = body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
}

// Initializes dark mode based on the user's saved preference
function initDarkMode() {
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
    }
}

// Initializes the page on load
window.onload = function () {
    initDarkMode();
    verifyLogin();
    displayCart();
    loadProductsFromDB();
};

// Loads product data from a simulated database or local storage
function loadProductsFromDB() {
    const products = [
        { id: 1, name: 'Aquarius Nurburgring', price: 240, category: 'smartwatch', image: 'assets/images/duward-watch1.png', details: 'assets/images/characteristics1.png' },
        { id: 2, name: 'Aquastar Race', price: 189, category: 'smartwatch', image: 'assets/images/duward-watch2.png', details: 'assets/images/characteristics2.png' },
        { id: 3, name: 'Smartwatch Style', price: 98.90, category: 'smartwatch', image: 'assets/images/duward-watch3.png', details: 'assets/images/characteristics3.png' },
        { id: 4, name: 'Lady Woman', price: 89, category: 'woman', image: 'assets/images/duward-watch4.png', details: 'assets/images/characteristics4.png' },
        { id: 5, name: 'Lady Babaye', price: 95, category: 'woman', image: 'assets/images/duward-watch5.png', details: 'assets/images/characteristics5.png' },
        { id: 6, name: 'Junior Divka', price: 39.90, category: 'junior', image: 'assets/images/duward-watch6.png', details: 'assets/images/characteristics6.png' },
        { id: 7, name: 'Junior Dreng', price: 49.90, category: 'junior', image: 'assets/images/duward-watch7.png', details: 'assets/images/characteristics7.png' }
    ];

    localStorage.setItem('allProducts', JSON.stringify(products));
    displayProducts(products); // Displays the products on the page
}

// Filters products by category
function filterByCategory(category) {
    const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
    return allProducts.filter(p => p.category === category);
}

// Searches products by name
function searchByName(term) {
    const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
    return allProducts.filter(p => p.name.toLowerCase().includes(term.toLowerCase()));
}

// Sorts products by price in ascending or descending order
function sortByPrice(order = "asc") {
    const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
    return allProducts.sort((a, b) => order === "asc" ? a.price - b.price : b.price - a.price);
}

// Applies filters and sorting to the product list
function applyFilters() {
    let products = JSON.parse(localStorage.getItem('allProducts')) || [];
    const searchTerm = document.getElementById("searchInput").value.toLowerCase();
    const category = document.getElementById("filterCategory").value.toLowerCase(); // Ensure lowercase comparison
    const sortOrder = document.getElementById("sortPrice").value;

    if (searchTerm) {
        products = products.filter(p => p.name.toLowerCase().includes(searchTerm));
    }

    if (category) {
        products = products.filter(p => p.category.toLowerCase() === category); // Ensure exact match for category
    }

    if (sortOrder === "asc") {
        products.sort((a, b) => a.price - b.price);
    } else if (sortOrder === "desc") {
        products.sort((a, b) => b.price - a.price);
    }

    displayProducts(products); // Displays the filtered products
}

// Displays the list of products on the page
function displayProducts(products) {
    const productsContainer = document.getElementById("products");
    if (!productsContainer) return;

    productsContainer.innerHTML = ""; // Clears the container

    products.forEach(product => {
        const productDiv = document.createElement("div");
        productDiv.className = "product";
        productDiv.innerHTML = `
            <img src="${product.image}" alt="${product.name}" onclick="showModal('${product.details}')">
            <h2>${product.name}</h2>
            <p>Price: ${product.price}€</p>
            <button onclick="addToCart(${product.id})">Add to Cart</button>
        `;
        productsContainer.appendChild(productDiv);
    });
}

// Shows a modal with product details
function showModal(detailsImage) {
    const modalOverlay = document.getElementById("modalOverlay");
    const modalImage = document.getElementById("modalImage");

    if (modalOverlay && modalImage) {
        modalImage.src = detailsImage; // Loads the characteristics image
        modalOverlay.style.display = "flex"; // Shows the modal
    }
}

// Closes the modal
function closeModal() {
    const modalOverlay = document.getElementById("modalOverlay");
    if (modalOverlay) {
        modalOverlay.style.display = "none"; // Hides the modal
    }
}

// Toggles dropdown menus
function toggleDropdown(event) {
    event.stopPropagation();
    const dropdown = event.target.closest('.dropdown');
    dropdown.classList.toggle('show');
}

// Closes dropdowns when clicking outside
window.onclick = function () {
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => dropdown.classList.remove('show'));
};

// Adds confirmation dialogs for cart actions
document.addEventListener('DOMContentLoaded', () => {
    const clearCartButton = document.querySelector('button[type="submit"][value="clear_cart"]');
    const buyButton = document.querySelector('form[action="generate_pdf.php"] button');

    if (clearCartButton) {
        clearCartButton.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to clear the cart?')) {
                e.preventDefault();
            }
        });
    }

    if (buyButton) {
        buyButton.addEventListener('click', () => {
            alert('Your purchase is being processed. A PDF will be generated.');
        });
    }
});