<?php
session_start();
unset($_SESSION['cart']);
header("Location: cart.php"); // Redirige de nuevo a la página del carrito
exit;
