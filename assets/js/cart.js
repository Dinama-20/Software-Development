document.addEventListener("DOMContentLoaded", () => {
    const removeButtons = document.querySelectorAll(".remove-btn");
    const buyButton = document.getElementById("buy-btn");
    const clearCartButton = document.getElementById("clear-cart-btn");

    // Asignar evento para eliminar productos del carrito
    removeButtons.forEach(button => {
        button.addEventListener("click", () => {
            const index = button.getAttribute("data-index");
            fetch(`remove_from_cart.php?index=${index}`, { method: "GET" })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Recargar la página para actualizar el carrito
                    } else {
                        alert("Failed to remove item from cart.");
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    });

    // Asignar evento para el botón "Buy"
    if (buyButton) {
        buyButton.addEventListener("click", () => {
            fetch("generate_pdf.php")
                .then(() => {
                    alert("Order completed! A PDF has been generated.");
                    location.reload(); // Recargar la página después de la compra
                })
                .catch(error => console.error("Error during checkout:", error));
        });
    }

    // Asignar evento para el botón "Clear Cart"
    if (clearCartButton) {
        clearCartButton.addEventListener("click", () => {
            fetch("clear_cart.php", { method: "POST" })
                .then(() => {
                    alert("Cart cleared!");
                    location.reload(); // Recargar la página después de vaciar el carrito
                })
                .catch(error => console.error("Error clearing cart:", error));
        });
    }
});
