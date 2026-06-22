<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;
use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\Configuracion;
use App\Middleware\ClienteMiddleware;

class PedidoController {

    private function getConfig() {
        return (new Configuracion())->getAll();
    }

    // ── Mostrar checkout ────────────────────────────────────────

    public function showCheckout() {
        ClienteMiddleware::check();

        $items = Carrito::getItems();
        if (empty($items)) {
            header('Location: ' . URL_BASE . '/carrito');
            exit;
        }

        $config   = $this->getConfig();
        $subtotal = Carrito::subtotal();
        $envio    = Carrito::costoEnvio($config, $subtotal);
        $total    = $subtotal + $envio;

        // Pre-llenar con datos del cliente
        $cliente = null;
        if (isset($_SESSION['cliente_id'])) {
            $clienteModel = new \App\Models\Cliente();
            $cliente = $clienteModel->findById($_SESSION['cliente_id']);
        }

        return View::render('checkout', [
            'title'    => 'Finalizar Pedido — Blancos El Rosario',
            'items'    => $items,
            'subtotal' => $subtotal,
            'envio'    => $envio,
            'total'    => $total,
            'config'   => $config,
            'cliente'  => $cliente,
            'error'    => $_SESSION['checkout_error'] ?? null,
        ]);
    }

    // ── Procesar pedido ─────────────────────────────────────────

    public function procesarCheckout() {
        ClienteMiddleware::check();
        unset($_SESSION['checkout_error']);

        $items = Carrito::getItems();
        if (empty($items)) {
            header('Location: ' . URL_BASE . '/carrito');
            exit;
        }

        $nombre    = trim(Request::post('nombre_cliente'));
        $email     = trim(Request::post('email_cliente'));
        $telefono  = trim(Request::post('telefono_cliente'));
        $direccion = trim(Request::post('direccion_entrega'));
        $municipio = trim(Request::post('municipio'));
        $estado_e  = trim(Request::post('estado_entrega'));
        $cp        = trim(Request::post('cp'));
        $metodo    = Request::post('metodo_pago');
        $notas     = trim(Request::post('notas'));

        if (empty($nombre) || empty($direccion) || empty($municipio) || empty($metodo)) {
            $_SESSION['checkout_error'] = 'Por favor completa todos los campos obligatorios.';
            header('Location: ' . URL_BASE . '/checkout');
            exit;
        }

        $config   = $this->getConfig();
        $subtotal = Carrito::subtotal();
        $envio    = Carrito::costoEnvio($config, $subtotal);
        $total    = $subtotal + $envio;

        $pedidoModel = new Pedido();
        $result = $pedidoModel->crear([
            'id_ct_cliente'   => $_SESSION['cliente_id'] ?? null,
            'nombre_cliente'  => $nombre,
            'email_cliente'   => $email,
            'telefono_cliente'=> $telefono,
            'direccion_entrega'=> $direccion,
            'municipio'       => $municipio,
            'estado_entrega'  => $estado_e,
            'cp'              => $cp,
            'metodo_pago'     => $metodo,
            'subtotal'        => $subtotal,
            'costo_envio'     => $envio,
            'total'           => $total,
            'notas'           => $notas,
        ], $items);

        // Vaciar carrito
        Carrito::vaciar();

        header('Location: ' . URL_BASE . '/pedido/' . $result['folio']);
        exit;
    }

    // ── Confirmación ────────────────────────────────────────────

    public function confirmacion($folio) {
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->findByFolio($folio);

        if (!$pedido) {
            header('HTTP/1.0 404 Not Found');
            echo '404 - Pedido no encontrado';
            exit;
        }

        $items  = $pedidoModel->getItems($pedido['id_ct_pedido']);
        $config = $this->getConfig();

        return View::render('pedido_confirmado', [
            'title'  => 'Pedido Confirmado — ' . $folio,
            'pedido' => $pedido,
            'items'  => $items,
            'config' => $config,
        ]);
    }
}
