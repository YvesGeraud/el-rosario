<?php

namespace App\Middleware;

class ClienteMiddleware {
    /**
     * Verifica si el cliente está logueado. Si no, redirige al login.
     */
    public static function check() {
        if (!isset($_SESSION['cliente_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . URL_BASE . '/mi-cuenta/acceso');
            exit;
        }
    }
}
