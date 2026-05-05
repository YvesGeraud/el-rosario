<?php

namespace App\Middleware;

class AuthMiddleware {
    /**
     * Verifica si el usuario está logueado, si no, redirige al login.
     */
    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /blancos/public/admin/login');
            exit;
        }
    }
}
