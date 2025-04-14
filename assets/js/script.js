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

function addToCart(productName, price) {
    fetch('add_to_cart.php', {oductName, price: price };
        method: 'POST',php', {
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `name=${encodeURIComponent(productName)}&price=${encodeURIComponent(price)}`
    })  body: JSON.stringify(product)
    .then(res => res.json())
    .then(data => {s.json())
        if (data.success) {
            alert(`${productName} added to cart!`);
        } else {t(`${productName} added to cart!`);
            alert("Failed to add to cart");
        }   alert(data.message || "Failed to add to cart");
    })  }
    .catch(error => console.error("Error:", error));
}   .catch(error => console.error("Error:", error));
}
function displayCart() {
    const cartContainer = document.getElementById('cart-container');
    if (!cartContainer) return;ent.getElementById('cart-container');
    if (!cartContainer) return;
    fetch("get_cart.php")
        .then(response => response.json())
        .then(cart => {=> response.json())
            cartContainer.innerHTML = cart.length ? "" : "<p>Your cart is empty</p>";
            let totalPrice = 0;HTML = cart.length ? "" : "<p>Your cart is empty</p>";
            let totalPrice = 0;
            cart.forEach((product, index) => {
                const productElement = document.createElement('div');
                productElement.classList.add('cart-product');('div');
                productElement.innerHTML = `('cart-product');
                    <p><strong>${product.name}</strong></p>
                    <p>Price: $${product.price.toFixed(2)}</p>
                    <button class="custom-btn" onclick="removeFromCart(${index})">Remove</button>
                `;  <button class="custom-btn" onclick="removeFromCart(${index})">Remove</button>
                cartContainer.appendChild(productElement);
                totalPrice += parseFloat(product.price););
            }); totalPrice += parseFloat(product.price);
            });
            if (cart.length > 0) {
                const totalElement = document.createElement('div');
                totalElement.classList.add('cart-total');nt('div');
                totalElement.innerHTML = `<p>Total: $${totalPrice.toFixed(2)}</p>`;
                cartContainer.appendChild(totalElement);otalPrice.toFixed(2)}</p>`;
            }   cartContainer.appendChild(totalElement);
        }); }
}       });
}
function removeFromCart(index) {
    fetch(`remove_from_cart.php?index=${index}`)
        .then(() => displayCart())dex=${index}`)
        .catch(error => console.error("Error removing item:", error));
}       .catch(error => console.error("Error removing item:", error));
}
function clearCart() {
    fetch("clear_cart.php")
        .then(() => {.php")
            displayCart();
            alert("Cart cleared");
        })  alert("Cart cleared");
        .catch(error => console.error("Error clearing cart:", error));
}       .catch(error => console.error("Error clearing cart:", error));
}
function checkout() {
    fetch("get_cart.php")
        .then(response => response.json())
        .then(cart => {=> response.json())
            if (cart.length === 0) {
                alert("Your cart is empty. Add products before checking out.");
                return;Your cart is empty. Add products before checking out.");
            }   return;
            generarPDF(cart);
            clearCart();art);
            alert("Checkout completed successfully! Your order details have been saved as a PDF.");
        }) alert("Checkout completed successfully! Your order details have been saved as a PDF.");
        .catch(error => console.error("Error during checkout:", error));       });
}}

function generarPDF(cart) {
    const { jsPDF } = window.jspdf;.jspdf;
    const doc = new jsPDF();
    const total = cart.reduce((sum, item) => sum + item.price, 0); item.price, 0);
    const fechaHora = new Date().toLocaleString();    const fechaHora = new Date().toLocaleString();

    doc.setFontSize(18);
    doc.text("Order Details", 20, 20);    doc.text("Order Details", 20, 20);

    doc.setFontSize(12);2);
    let yOffset = 30;
    cart.forEach(product => {
        doc.text(`Product: ${product.name} - Price: $${product.price.toFixed(2)}`, 20, yOffset);uct: ${product.name} - Price: $${product.price.toFixed(2)}`, 20, yOffset);
        yOffset += 10; yOffset += 10;
    });    });

    doc.text(`Total: $${total.toFixed(2)}`, 20, yOffset + 10);
    doc.text(`Date and Time: ${fechaHora}`, 20, yOffset + 20);    doc.text(`Date and Time: ${fechaHora}`, 20, yOffset + 20);

    doc.save("order-details.pdf");   doc.save("order-details.pdf");
}}

function verifyLogin() {
    const user = JSON.parse(localStorage.getItem("user"));
    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

    if (user && isLoggedIn) {
        const loginBtn = document.getElementById("login-btn");
        const registerBtn = document.getElementById("register-btn");egister-btn");
        if (loginBtn) loginBtn.style.display = "none";
        if (registerBtn) registerBtn.style.display = "none";        if (registerBtn) registerBtn.style.display = "none";

        const userMenu = document.getElementById("user-menu");
        if (userMenu && !document.getElementById("user-menu-btn")) {) {
            const usernameBtn = document.createElement("button");teElement("button");
            usernameBtn.id = "user-menu-btn";
            usernameBtn.textContent = user.name;;
            usernameBtn.onclick = showUserMenu;;
            userMenu.appendChild(usernameBtn);   userMenu.appendChild(usernameBtn);
        }   }
    }   }
}}

function showUserMenu() {
    const existing = document.getElementById("user-dropdown");= document.getElementById("user-dropdown");
    if (existing) {
        existing.remove();g.remove();
        return;   return;
    }    }

    const menu = document.createElement("div");teElement("div");
    menu.id = "user-dropdown";
    menu.style.position = "absolute";bsolute";
    menu.style.top = "50px";
    menu.style.right = "20px";
    menu.style.backgroundColor = "#fff";
    menu.style.border = "1px solid #ccc";id #ccc";
    menu.style.padding = "10px";;
    menu.style.zIndex = "1000";    menu.style.zIndex = "1000";

    const options = [
        { text: "My Profile", action: showProfile },,
        { text: "Settings", action: showSettings },ttings },
        { text: "Logout", action: logout }  { text: "Logout", action: logout }
    ];    ];

    options.forEach(option => {
        const button = document.createElement("button");ment("button");
        button.textContent = option.text;t;
        button.onclick = option.action;ction;
        menu.appendChild(button);
        menu.appendChild(document.createElement("br")); menu.appendChild(document.createElement("br"));
    });    });

    document.body.appendChild(menu);   document.body.appendChild(menu);
}}

function logout() {
    localStorage.removeItem("user");
    localStorage.removeItem("isLoggedIn");");
    window.location.href = "index.php";   window.location.href = "index.php";
}}

window.onload = function () {nction () {
    verifyLogin();
    displayCart();
    initDarkMode();
    loadProductsFromDB();  loadProductsFromDB();
};};

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));   body.classList.toggle('dark-mode');
}    localStorage.setItem('darkMode', body.classList.contains('dark-mode'));

function initDarkMode() {
    if (localStorage.getItem("darkMode") === "true") {
        document.body.classList.add("dark-mode");f (localStorage.getItem('darkMode') === 'true') {
    }       document.body.classList.add('dark-mode');
}    }

function loadProductsFromDB() {
    fetch("backend/get-products.php")
        .then(response => response.json())products.php")
        .then(data => {
            localStorage.setItem("allProducts", JSON.stringify(data));
            displayProducts(data);  localStorage.setItem("allProducts", JSON.stringify(data));
        })ts(data);
        .catch(error => {
            console.error("Error loading products:", error);tch(error => {
        });           console.error("Error loading products:", error);
}        });

function filterByCategory(category) {
    const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
    return allProducts.filter(p => p.category === category);   const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
}    return allProducts.filter(p => p.category === category);

function searchByName(term) {
    const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
    return allProducts.filter(p => p.name.toLowerCase().includes(term.toLowerCase()));   const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
}    return allProducts.filter(p => p.name.toLowerCase().includes(term.toLowerCase()));

function sortByPrice(order = "asc") {
    const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
    return allProducts.sort((a, b) => order === "asc" ? a.price - b.price : b.price - a.price);   const allProducts = JSON.parse(localStorage.getItem("allProducts")) || [];
}    return allProducts.sort((a, b) => order === "asc" ? a.price - b.price : b.price - a.price);

function applyFilters() {
    let products = JSON.parse(localStorage.getItem("allProducts")) || [];
    const searchTerm = document.getElementById("searchInput").value.toLowerCase(); [];
    const category = document.getElementById("filterCategory").value;ue.toLowerCase();
    const sortOrder = document.getElementById("sortPrice").value;    const category = document.getElementById("filterCategory").value;
 document.getElementById("sortPrice").value;
    if (searchTerm) {
        products = products.filter(p => p.name.toLowerCase().includes(searchTerm));f (searchTerm) {
    }        products = products.filter(p => p.name.toLowerCase().includes(searchTerm));

    if (category) {
        products = products.filter(p => p.category.toLowerCase() === category.toLowerCase());f (category) {
    }        products = products.filter(p => p.category.toLowerCase() === category.toLowerCase());

    if (sortOrder === "asc") {
        products.sort((a, b) => a.price - b.price);
    } else if (sortOrder === "desc") {
        products.sort((a, b) => b.price - a.price); else if (sortOrder === "desc") {
    }        products.sort((a, b) => b.price - a.price);

    displayProducts(products);
}    displayProducts(products);

function displayProducts(products) {
    const productsContainer = document.getElementById("products");{
    if (!productsContainer) return;    const productsContainer = document.getElementById("products");

    productsContainer.innerHTML = "";
 "";
    products.forEach(product => {
        const productDiv = document.createElement("div");
        productDiv.className = "product";
        productDiv.onclick = () => showModal(product.image);        productDiv.className = "product";
=> showModal(product.image);
        productDiv.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h2>${product.name}</h2>="${product.name}">
            <p>Price: ${product.price}€</p>
            <button onclick="event.stopPropagation(); addToCart('${product.name}', ${product.price})">Add to Cart</button>  <p>Price: ${product.price}€</p>
        `;(); addToCart('${product.name}', ${product.price})">Add to Cart</button>
        productsContainer.appendChild(productDiv); `;
    });       productsContainer.appendChild(productDiv);
}    });

function showModal(imageUrl) {
    // Opcional: modal de productounction showModal(imageUrl) {
}    // Opcional: modal de producto

}
