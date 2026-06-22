<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;
use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Configuracion;

class CarritoController {

    private function getConfig() {
        return (new Configuracion())->getAll();
    }

    // ── Ver carrito ─────────────────────────────────────────────

    public function index() {
        $config   = $this->getConfig();
        $items    = Carrito::getItems();
        $subtotal = Carrito::subtotal();
        $envio    = Carrito::costoEnvio($config, $subtotal);
        $total    = $subtotal + $envio;

        return View::render('carrito', [
            'title'    => 'Mi Carrito — Blancos El Rosario',
            'items'    => $items,
            'subtotal' => $subtotal,
            'envio'    => $envio,
            'total'    => $total,
            'config'   => $config,
        ]);
    }

    // ── Agregar producto ────────────────────────────────────────

    public function agregar() {
        $id_producto  = (int) Request::post('id_producto');
        $variante_id  = Request::post('variante_id') ?: null;
        $cantidad     = max(1, (int) Request::post('cantidad'));

        if (!$id_producto) {
            $this->jsonError('Producto inválido.');
        }

        $productoModel = new Producto();
        $producto = $productoModel->findById($id_producto);

        if (!$producto) {
            $this->jsonError('Producto no encontrado.');
        }

        // Precio con variante
        $precio = (float) $producto['precio_base'];
        $variante_desc = null;

        if ($variante_id) {
            $variantes = $productoModel->getVariants($id_producto);
            foreach ($variantes as $v) {
                if ($v['id_dt_variantes_producto'] == $variante_id) {
                    $precio += (float) $v['precio_extra'];
                    $variante_desc = $v['tipo'] . ': ' . $v['valor'];
                    break;
                }
            }
        }

        // Imagen principal del producto
        $imagenes = $productoModel->getImages($id_producto);
        $imagen = '';
        foreach ($imagenes as $img) {
            if ($img['es_principal']) { $imagen = $img['ruta']; break; }
        }
        if (!$imagen && !empty($imagenes)) {
            $imagen = $imagenes[0]['ruta'];
        }

        Carrito::agregar(
            $id_producto,
            $producto['nombre'],
            $precio,
            $imagen,
            $variante_id,
            $variante_desc,
            $cantidad
        );

        header('Content-Type: application/json');
        echo json_encode([
            'success'     => true,
            'cart_count'  => Carrito::countItems(),
            'message'     => 'Producto agregado al carrito.',
        ]);
        exit;
    }

    // ── Actualizar cantidad ─────────────────────────────────────

    public function actualizar() {
        $key      = Request::post('key');
        $cantidad = (int) Request::post('cantidad');

        Carrito::actualizar($key, $cantidad);

        $config   = $this->getConfig();
        $subtotal = Carrito::subtotal();
        $envio    = Carrito::costoEnvio($config, $subtotal);

        header('Content-Type: application/json');
        echo json_encode([
            'success'       => true,
            'cart_count'    => Carrito::countItems(),
            'subtotal'      => number_format($subtotal, 2),
            'envio'         => number_format($envio, 2),
            'total'         => number_format($subtotal + $envio, 2),
        ]);
        exit;
    }

    // ── Eliminar ítem ───────────────────────────────────────────

    public function eliminar() {
        $key = Request::post('key');
        Carrito::eliminar($key);

        $config   = $this->getConfig();
        $subtotal = Carrito::subtotal();
        $envio    = Carrito::costoEnvio($config, $subtotal);

        header('Content-Type: application/json');
        echo json_encode([
            'success'    => true,
            'cart_count' => Carrito::countItems(),
            'subtotal'   => number_format($subtotal, 2),
            'envio'      => number_format($envio, 2),
            'total'      => number_format($subtotal + $envio, 2),
        ]);
        exit;
    }

    // ── Helpers ─────────────────────────────────────────────────

    private function jsonError($msg) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $msg]);
        exit;
    }
}
