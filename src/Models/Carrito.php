<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Carrito {

    /**
     * Obtener el carrito actual de la sesión
     */
    public static function getItems() {
        return $_SESSION['carrito'] ?? [];
    }

    /**
     * Agregar un producto al carrito.
     * Si ya existe con la misma variante, incrementa la cantidad.
     */
    public static function agregar($id_producto, $nombre, $precio, $imagen, $variante_id = null, $variante_desc = null, $cantidad = 1) {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Clave única: producto + variante
        $key = $id_producto . '_' . ($variante_id ?? '0');

        if (isset($_SESSION['carrito'][$key])) {
            $_SESSION['carrito'][$key]['cantidad'] += (int)$cantidad;
        } else {
            $_SESSION['carrito'][$key] = [
                'key'           => $key,
                'id_producto'   => $id_producto,
                'nombre'        => $nombre,
                'precio'        => (float)$precio,
                'imagen'        => $imagen,
                'variante_id'   => $variante_id,
                'variante_desc' => $variante_desc,
                'cantidad'      => (int)$cantidad,
            ];
        }
    }

    /**
     * Actualizar la cantidad de un ítem
     */
    public static function actualizar($key, $cantidad) {
        if (isset($_SESSION['carrito'][$key])) {
            $cantidad = (int)$cantidad;
            if ($cantidad <= 0) {
                self::eliminar($key);
            } else {
                $_SESSION['carrito'][$key]['cantidad'] = $cantidad;
            }
        }
    }

    /**
     * Eliminar un ítem del carrito
     */
    public static function eliminar($key) {
        unset($_SESSION['carrito'][$key]);
    }

    /**
     * Vaciar el carrito completo
     */
    public static function vaciar() {
        $_SESSION['carrito'] = [];
    }

    /**
     * Contar total de ítems (suma de cantidades)
     */
    public static function countItems() {
        $items = self::getItems();
        return array_sum(array_column($items, 'cantidad'));
    }

    /**
     * Calcular subtotal del carrito
     */
    public static function subtotal() {
        $items = self::getItems();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    /**
     * Calcular costo de envío según la configuración
     */
    public static function costoEnvio($config, $subtotal) {
        $umbralGratis = (float)($config['envio_gratis_desde'] ?? 0);
        $costoNacional = (float)($config['envio_costo_nacional'] ?? 150);

        if ($umbralGratis > 0 && $subtotal >= $umbralGratis) {
            return 0;
        }
        return $costoNacional;
    }

    /**
     * Calcular total (subtotal + envío)
     */
    public static function total($config) {
        $subtotal = self::subtotal();
        $envio = self::costoEnvio($config, $subtotal);
        return $subtotal + $envio;
    }
}
