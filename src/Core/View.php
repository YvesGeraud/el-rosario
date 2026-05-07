<?php

namespace App\Core;

use App\Models\Configuracion;

class View {
    public static function render($path, $data = [], $layout = 'public') {
        // Cargar configuración global
        $configModel = new Configuracion();
        $data['config'] = $configModel->getAll();

        extract($data);
        
        ob_start();
        require_once dirname(__DIR__, 2) . "/views/pages/{$path}.php";
        $content = ob_get_clean();

        require_once dirname(__DIR__, 2) . "/views/layouts/{$layout}.php";
    }
}
