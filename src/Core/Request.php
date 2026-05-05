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
}
