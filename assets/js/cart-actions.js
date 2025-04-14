document.addEventListener("DOMContentLoaded", () => {
    const cartContainer = document.getElementById("cart-container");
    const cartItems = document.getElementById("cart-items");
    const cartActions = document.getElementById("cart-actions");
    const buyButton = document.getElementById("buy-btn");
    const clearCartButton = document.getElementById("clear-cart-btn");

    // Cargar los elementos del carrito dinámicamente
    function loadCart() {
        fetch("get_cart.php")
            .then(response => response.json())
            .then(cart => {
                cartItems.innerHTML = ""; // Limpiar el contenido actual
                if (cart.length > 0) {
                    cart.forEach((item, index) => {
                        const listItem = document.createElement("li");
                        listItem.innerHTML = `
                            <strong>${item.name}</strong> - €${item.price.toFixed(2)}
                            <button class="remove-btn" data-index="${index}">Remove</button>
                        `;
                        cartItems.appendChild(listItem);
                    });
                    cartActions.style.display = "flex"; // Mostrar los botones
                } else {
                    cartContainer.innerHTML = "<p>Your cart is empty.</p>";
                }
            })
            .catch(error => console.error("Error loading cart:", error));
    }

    // Manejar la eliminación de productos del carrito
    cartContainer.addEventListener("click", (event) => {
        if (event.target.classList.contains("remove-btn")) {
            const index = event.target.getAttribute("data-index");
            fetch(`remove_from_cart.php?index=${index}`, { method: "GET" })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCart(); // Recargar el carrito
                    } else {
                        alert("Failed to remove item from cart.");
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    });

    // Manejar la acción de "Buy"
    if (buyButton) {
        buyButton.addEventListener("click", () => {
            fetch("generate_pdf.php")
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Order completed! A PDF has been generated.");
                        window.open(data.pdfPath, '_blank'); // Abre el PDF en una nueva pestaña
                        loadCart(); // Recargar el carrito después de la compra
                    } else {
                        alert(data.message || "Failed to complete the order.");
                    }
                })
                .catch(error => console.error("Error during checkout:", error));
        });
    }

    // Manejar la acción de "Clear Cart"
    if (clearCartButton) {
        clearCartButton.addEventListener("click", () => {
            fetch("clear_cart.php", { method: "POST" })
                .then(() => {
                    alert("Cart cleared!");
                    loadCart(); // Recargar el carrito después de vaciarlo
                })
                .catch(error => console.error("Error clearing cart:", error));
        });
    }

    // Cargar el carrito al cargar la página
    loadCart();
});
