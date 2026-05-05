<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;
use App\Models\Configuracion;
use App\Middleware\AuthMiddleware;

class AdminConfigController {
    protected $configModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->configModel = new Configuracion();
    }

    /**
     * Mostrar formulario de configuración
     */
    public function index() {
        $configuraciones = $this->configModel->getFullDetails();
        return View::render('admin/configuracion', [
            'title' => 'Configuración del Sitio',
            'configuraciones' => $configuraciones,
            'success' => $_SESSION['config_success'] ?? null
        ], 'admin');
    }

    /**
     * Guardar cambios
     */
    public function update() {
        unset($_SESSION['config_success']);
        
        $postData = $_POST['config'] ?? [];
        
        foreach ($postData as $clave => $valor) {
            $this->configModel->update($clave, $valor);
        }

        $_SESSION['config_success'] = 'Configuración actualizada correctamente.';
        header('Location: ' . URL_BASE . '/admin/configuracion');
        exit;
    }
}
