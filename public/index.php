<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Configurar errores
if (($_ENV['APP_DEBUG'] ?? 'false') === 'true') {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler);
    $whoops->register();
}

// Iniciar sesión
session_start();

// Enrutamiento básico
$router = new \App\Core\Router();

// Cargar rutas
require_once __DIR__ . '/../config/routes.php';

// Ruta base en el servidor calculada dinámicamente
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($basePath === '/' || $basePath === '\\') {
    $basePath = '';
}
define('URL_BASE', $basePath);

// Obtener URI real
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Quitar la ruta base de la URI antes de pasarla al router
if (!empty($basePath) && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

if ($uri === '' || $uri === false) {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);