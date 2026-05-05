<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno (safeLoad no lanza error si no existe .env)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Configurar errores (Whoops)
if ($_ENV['APP_DEBUG'] === 'true') {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler); // Usar PlainText por ahora si no hay navegador
    $whoops->register();
}

// Iniciar sesión
session_start();

// Enrutamiento básico
$router = new \App\Core\Router();

// Cargar rutas
require_once __DIR__ . '/../config/routes.php';

// Despachar
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Detectar la base de la URL (para que funcione en local /blancos/public o en raíz /)
$basePath = (strpos($_SERVER['REQUEST_URI'], '/blancos/public') === 0) ? '/blancos/public' : '';
define('URL_BASE', $basePath);

// Ajustar URI para el router
if ($basePath !== '' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
if ($uri === '') $uri = '/';

$router->dispatch($uri, $method);
