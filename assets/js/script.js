function login(event) {
    event.preventDefault();
    const email = document.getElementById("login-email").value.trim();
    const user = JSON.parse(localStorage.getItem("user"));

    if (user && user.email === email) {
        alert("Login successful");
        localStorage.setItem("isLoggedIn", "true");
        window.location.href = "index.html";
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
    window.location.href = "index.html";
}

function addToCart(productName, productPrice) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const product = {
        id: Date.now(), 
        name: productName,
        price: productPrice
    };

    cart.push(product);
    localStorage.setItem('cart', JSON.stringify(cart));
    alert(`${productName} added to cart`);
}

function displayCart() {
    const cartContainer = document.getElementById('cart-container');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    cartContainer.innerHTML = cart.length
        ? ""
        : "<p>Your cart is empty</p>";

    let totalPrice = 0;

    cart.forEach(product => {
        const productElement = document.createElement('div');
        productElement.classList.add('cart-product');
        productElement.innerHTML = `
            <p><strong>${product.name}</strong></p>
            <p>Price: $${product.price.toFixed(2)}</p>
            <button class="custom-btn" onclick="removeFromCart(${product.id})">Remove</button>
        `;
        cartContainer.appendChild(productElement);
        totalPrice += product.price;
    });

    if (cart.length > 0) {
        const totalElement = document.createElement('div');
        totalElement.classList.add('cart-total');
        totalElement.innerHTML = `<p>Total: $${totalPrice.toFixed(2)}</p>`;
        cartContainer.appendChild(totalElement);
    }
}

function removeFromCart(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(product => product.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    displayCart();
}

function clearCart() {
    localStorage.removeItem('cart');
    displayCart();
    alert("Cart cleared");
}

function checkout() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (cart.length === 0) {
        alert("Your cart is empty. Add products before checking out.");
        return;
    }

    generarPDF();
    clearCart();
    alert("Checkout completed successfully! Your order details have been saved as a PDF.");
}

function generarPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const total = cart.reduce((sum, item) => sum + item.price, 0);
    const fechaHora = new Date().toLocaleString();

    doc.setFontSize(18);
    doc.text("Order Details", 20, 20);

    doc.setFontSize(12);
    let yOffset = 30;
    cart.forEach(product => {
        doc.text(`Product: ${product.name} - Price: $${product.price.toFixed(2)}`, 20, yOffset);
        yOffset += 10;
    });

    doc.text(`Total: $${total.toFixed(2)}`, 20, yOffset + 10);
    doc.text(`Date and Time: ${fechaHora}`, 20, yOffset + 20);

    doc.save("order-details.pdf");
}

function verifyLogin() {
    const user = JSON.parse(localStorage.getItem("user"));
    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

    console.log(user, isLoggedIn);  // Agrega este console.log para verificar

    if (user && isLoggedIn) {
        // Ocultar botones de login y register
        document.getElementById("login-btn").style.display = "none";
        document.getElementById("register-btn").style.display = "none";

        // Mostrar el menú de usuario
        const userMenu = document.getElementById("user-menu");
        const usernameBtn = document.createElement("button");
        usernameBtn.textContent = user.name;
        usernameBtn.onclick = showUserMenu;
        userMenu.appendChild(usernameBtn);
    }
}

function showUserMenu() {
    const menu = document.createElement("div");
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
    window.location.href = "index.html"; // Redirigir al index
}

window.onload = function () {
    verifyLogin();
    displayCart();
    initDarkMode();
};

function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    localStorage.setItem("darkMode", document.body.classList.contains("dark-mode") ? "true" : "false");
}

function initDarkMode() {
    if (localStorage.getItem("darkMode") === "true") {
        document.body.classList.add("dark-mode");
    }
}

function loadProductsFromDB() {
    fetch("backend/get-products.php")
        .then(response => response.json())
        .then(data => {
            localStorage.setItem("allProducts", JSON.stringify(data));
            displayProducts(data);
        })
        .catch(error => {
            console.error("Error loading products:", error);
        });
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
    let products = JSON.parse(localStorage.getItem("allProducts")) || [];
    const searchTerm = document.getElementById("searchInput").value.toLowerCase();
    const category = document.getElementById("filterCategory").value;
    const sortOrder = document.getElementById("sortPrice").value;

    // Search
    if (searchTerm) {
        products = products.filter(p => p.name.toLowerCase().includes(searchTerm));
    }

    // Filter
    if (category) {
        products = products.filter(p => p.category.toLowerCase() === category.toLowerCase());
    }

    // Sort
    if (sortOrder === "asc") {
        products.sort((a, b) => a.price - b.price);
    } else if (sortOrder === "desc") {
        products.sort((a, b) => b.price - a.price);
    }

    displayProducts(products);
}

function displayProducts(products) {
    const productsContainer = document.getElementById("products");
    if (!productsContainer) return;

    productsContainer.innerHTML = "";

    products.forEach(product => {
        const productDiv = document.createElement("div");
        productDiv.className = "product";
        productDiv.onclick = () => showModal(product.image);

        productDiv.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h2>${product.name}</h2>
            <p>Price: ${product.price}€</p>
            <button onclick="event.stopPropagation(); addToCart('${product.name}', ${product.price})">Add to Cart</button>
        `;
        productsContainer.appendChild(productDiv);
    });
}
