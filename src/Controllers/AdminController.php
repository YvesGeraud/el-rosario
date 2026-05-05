<?php

namespace App\Controllers;

use App\Core\View;
use App\Middleware\AuthMiddleware;

class AdminController {
    public function __construct() {
        // Protegemos todas las rutas de este controlador
        AuthMiddleware::check();
    }

    public function dashboard() {
        return View::render('admin/dashboard', [
            'title' => 'Dashboard - Blancos El Rosario',
            'user' => $_SESSION['user_name']
        ], 'admin'); // Usamos el nuevo layout de admin que crearemos
    }
}
