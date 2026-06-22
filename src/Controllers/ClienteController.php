<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Middleware\ClienteMiddleware;

class ClienteController {
    protected $clienteModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
    }

    // ── Registro ────────────────────────────────────────────────

    public function showRegistro() {
        if (isset($_SESSION['cliente_id'])) {
            header('Location: ' . URL_BASE . '/mi-cuenta');
            exit;
        }
        return View::render('cliente/registro', [
            'title' => 'Crear Cuenta — Blancos El Rosario',
            'error' => $_SESSION['reg_error'] ?? null,
        ]);
    }

    public function registro() {
        unset($_SESSION['reg_error']);

        $nombre    = trim(Request::post('nombre'));
        $email     = trim(Request::post('email'));
        $password  = Request::post('password');
        $password2 = Request::post('password2');
        $telefono  = trim(Request::post('telefono'));

        // Validaciones
        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['reg_error'] = 'Por favor completa todos los campos obligatorios.';
            header('Location: ' . URL_BASE . '/registro');
            exit;
        }

        if ($password !== $password2) {
            $_SESSION['reg_error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . URL_BASE . '/registro');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['reg_error'] = 'La contraseña debe tener al menos 6 caracteres.';
            header('Location: ' . URL_BASE . '/registro');
            exit;
        }

        if ($this->clienteModel->emailExists($email)) {
            $_SESSION['reg_error'] = 'Ese correo electrónico ya está registrado.';
            header('Location: ' . URL_BASE . '/registro');
            exit;
        }

        $id = $this->clienteModel->create([
            'nombre'   => $nombre,
            'email'    => $email,
            'password' => $password,
            'telefono' => $telefono ?: null,
        ]);

        // Auto-login tras registro
        $_SESSION['cliente_id']     = $id;
        $_SESSION['cliente_nombre'] = $nombre;
        $_SESSION['cliente_email']  = $email;

        $redirect = $_SESSION['redirect_after_login'] ?? (URL_BASE . '/mi-cuenta');
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $redirect);
        exit;
    }

    // ── Login ───────────────────────────────────────────────────

    public function showLogin() {
        if (isset($_SESSION['cliente_id'])) {
            header('Location: ' . URL_BASE . '/mi-cuenta');
            exit;
        }
        return View::render('cliente/login', [
            'title' => 'Iniciar Sesión — Blancos El Rosario',
            'error' => $_SESSION['login_cliente_error'] ?? null,
        ]);
    }

    public function login() {
        unset($_SESSION['login_cliente_error']);

        $email    = trim(Request::post('email'));
        $password = Request::post('password');

        $cliente = $this->clienteModel->findByEmail($email);

        if ($cliente && password_verify($password, $cliente['password'])) {
            $_SESSION['cliente_id']     = $cliente['id_ct_cliente'];
            $_SESSION['cliente_nombre'] = $cliente['nombre'];
            $_SESSION['cliente_email']  = $cliente['email'];

            $redirect = $_SESSION['redirect_after_login'] ?? (URL_BASE . '/mi-cuenta');
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        }

        $_SESSION['login_cliente_error'] = 'Correo o contraseña incorrectos.';
        header('Location: ' . URL_BASE . '/login');
        exit;
    }

    // ── Logout ──────────────────────────────────────────────────

    public function logout() {
        unset($_SESSION['cliente_id'], $_SESSION['cliente_nombre'], $_SESSION['cliente_email']);
        header('Location: ' . URL_BASE . '/');
        exit;
    }

    // ── Perfil / Mi Cuenta ──────────────────────────────────────

    public function miCuenta() {
        ClienteMiddleware::check();

        $pedidoModel = new Pedido();
        $pedidos = $pedidoModel->getByCliente($_SESSION['cliente_id']);

        return View::render('cliente/perfil', [
            'title'   => 'Mi Cuenta — Blancos El Rosario',
            'cliente' => $this->clienteModel->findById($_SESSION['cliente_id']),
            'pedidos' => $pedidos,
            'success' => $_SESSION['perfil_success'] ?? null,
        ]);
    }

    public function updatePerfil() {
        ClienteMiddleware::check();
        unset($_SESSION['perfil_success']);

        $this->clienteModel->update($_SESSION['cliente_id'], [
            'nombre'           => trim(Request::post('nombre')),
            'telefono'         => trim(Request::post('telefono')),
            'direccion_defecto'=> trim(Request::post('direccion_defecto')),
        ]);

        $_SESSION['cliente_nombre']    = trim(Request::post('nombre'));
        $_SESSION['perfil_success']    = 'Datos actualizados correctamente.';
        header('Location: ' . URL_BASE . '/mi-cuenta');
        exit;
    }
}
