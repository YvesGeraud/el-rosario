<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Contacto;
use App\Middleware\AuthMiddleware;

class AdminMensajeController {
    protected $contactoModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->contactoModel = new Contacto();
    }

    /**
     * Listado de mensajes
     */
    public function index() {
        $mensajes = $this->contactoModel->getAll();
        return View::render('admin/mensajes/index', [
            'title' => 'Mensajes de Contacto',
            'mensajes' => $mensajes
        ], 'admin');
    }

    /**
     * Marcar como leído
     */
    public function read($id) {
        $this->contactoModel->markAsRead($id);
        header('Location: /blancos/public/admin/mensajes');
        exit;
    }

    /**
     * Eliminar mensaje
     */
    public function delete($id) {
        $this->contactoModel->delete($id);
        header('Location: /blancos/public/admin/mensajes');
        exit;
    }
}
