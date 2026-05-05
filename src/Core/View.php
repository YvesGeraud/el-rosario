<?php

namespace App\Core;

class View {
    public static function render($path, $data = [], $layout = 'public') {
        extract($data);
        
        ob_start();
        require_once dirname(__DIR__, 2) . "/views/pages/{$path}.php";
        $content = ob_get_clean();

        require_once dirname(__DIR__, 2) . "/views/layouts/{$layout}.php";
    }
}
