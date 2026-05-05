<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;
use App\Models\Usuario;

class AuthController {
    protected $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Mostrar el formulario de login
     */
    public function showLogin() {
        // Si ya está logueado, mandarlo al dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /blancos/public/admin/dashboard');
            exit;
        }

        return View::render('admin/login', [
            'title' => 'Login Administrativo - El Rosario',
            'error' => $_SESSION['login_error'] ?? null
        ], 'public'); // Usamos el layout público para el login
    }

    /**
     * Procesar el intento de login
     */
    public function login() {
        unset($_SESSION['login_error']);
        
        $email = Request::post('email');
        $password = Request::post('password');

        $user = $this->usuarioModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Login exitoso
            $_SESSION['user_id'] = $user['id_ct_usuario'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_rol'] = $user['rol'];
            
            header('Location: /blancos/public/admin/dashboard');
            exit;
        }

        // Error de login
        $_SESSION['login_error'] = 'Credenciales incorrectas o cuenta inactiva.';
        header('Location: /blancos/public/admin/login');
        exit;
    }

    /**
     * Cerrar sesión
     */
    public function logout() {
        session_destroy();
        header('Location: /blancos/public/admin/login');
        exit;
    }
}
