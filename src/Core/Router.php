<?php

namespace App\Core;

class Router {
    protected $routes = [];

    public function add($method, $uri, $controller) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    public function get($uri, $controller) {
        $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller) {
        $this->add('POST', $uri, $controller);
    }

    public function dispatch($uri, $method) {
        foreach ($this->routes as $route) {
            // Convertir {slug} a un grupo de captura regex ([^/]+)
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['uri']);
            $pattern = "#^" . $pattern . "$#";

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                // Filtrar solo los parámetros capturados por nombre
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->callAction($route['controller'], $params);
            }
        }

        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        exit;
    }

    protected function callAction($controller, $params = []) {
        list($controller, $action) = explode('@', $controller);
        $controller = "App\\Controllers\\{$controller}";
        $instance = new $controller;

        if (!method_exists($instance, $action)) {
            throw new \Exception("{$controller} does not respond to the {$action} action.");
        }

        // Pasar los parámetros al método del controlador
        return call_user_func_array([$instance, $action], $params);
    }
}
