<?php

namespace App\Core;

class Request {
    public static function get($key = null, $default = null) {
        if ($key === null) return $_GET;
        return $_GET[$key] ?? $default;
    }

    public static function post($key = null, $default = null) {
        if ($key === null) return $_POST;
        return $_POST[$key] ?? $default;
    }

    public static function file($key = null) {
        if ($key === null) return $_FILES;
        return $_FILES[$key] ?? null;
    }

    public static function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function csrfToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCsrf() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $token = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        if (empty($sessionToken) || empty($token) || !hash_equals($sessionToken, $token)) {
            header("HTTP/1.1 403 Forbidden");
            echo "Error: Token CSRF invalido o ausente.";
            exit;
        }
    }
}
