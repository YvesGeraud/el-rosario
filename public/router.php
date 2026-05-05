<?php
/**
 * router.php — PHP Built-in Server Router
 * Equivalente al .htaccess para el servidor de desarrollo de PHP.
 * Sirve archivos estáticos directamente y pasa todo lo demás a index.php.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$filePath = __DIR__ . $uri;

// Si el archivo físico existe (CSS, JS, imágenes, etc.), servírlo directamente
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    return false; // PHP built-in server lo sirve directamente
}

// De lo contrario, pasar a index.php (el router de la app)
require_once __DIR__ . '/index.php';
