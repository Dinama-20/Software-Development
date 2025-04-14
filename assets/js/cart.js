document.addEventListener("DOMContentLoaded", () => {
    const cartContainer = document.getElementById("cart-container");
    const cartItems = document.getElementById("cart-items");
    const buyButton = document.getElementById("buy-btn");
    const clearCartButton = document.getElementById("clear-cart-btn");

    // Manejar la eliminación de productos del carrito
    cartContainer.addEventListener("click", (event) => {
        if (event.target.classList.contains("remove-btn")) {
            const index = event.target.getAttribute("data-index");
            fetch(`remove_from_cart.php?index=${index}`, { method: "GET" })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        event.target.parentElement.remove(); // Eliminar el producto del DOM
                        if (!cartItems.children.length) {
                            cartContainer.innerHTML = "<p>Your cart is empty.</p>";
                        }
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
                        location.reload(); // Recargar la página después de la compra
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
                    cartContainer.innerHTML = "<p>Your cart is empty.</p>";
                })
                .catch(error => console.error("Error clearing cart:", error));
        });
    }
});
