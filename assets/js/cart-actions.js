document.addEventListener("DOMContentLoaded", () => {
    const removeButtons = document.querySelectorAll(".remove-btn");
    const buyButton = document.getElementById("buy-btn");
    const clearCartButton = document.getElementById("clear-cart-btn");

    // Asegurarse de que los botones existan en el DOM
    if (!buyButton || !clearCartButton) {
        console.error("Buy or Clear Cart buttons are missing from the DOM.");
        return;
    }

    // Manejar la eliminación de productos del carrito
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

    // Manejar la acción de "Buy"
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

    // Manejar la acción de "Clear Cart"
    clearCartButton.addEventListener("click", () => {
        fetch("clear_cart.php", { method: "POST" })
            .then(() => {
                alert("Cart cleared!");
                location.reload(); // Recargar la página después de vaciar el carrito
            })
            .catch(error => console.error("Error clearing cart:", error));
    });
});
