<?php
// Inicia la sesión y carga el autoload de Composer
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

// Rutas simples (puedes agregar más rutas según sea necesario)
$requestUri = $_SERVER['REQUEST_URI'];
if ($requestUri === '/register') {
    $controller = new \App\Controllers\UserController();
    $controller->register();
} elseif ($requestUri === '/login') {
    $controller = new \App\Controllers\UserController();
    $controller->login();
} else {
    // Default to home page or error page
    echo 'Page not found';
}
