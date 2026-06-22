<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;
use App\Models\Pedido;
use App\Middleware\AuthMiddleware;

class AdminPedidoController {
    protected $pedidoModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->pedidoModel = new Pedido();
    }

    /**
     * Lista de pedidos con filtro por estado
     */
    public function index() {
        $estado  = Request::get('estado') ?: null;
        $pedidos = $this->pedidoModel->getAll($estado);

        $conteos = [
            'todos'      => $this->pedidoModel->countByEstado(),
            'pendiente'  => $this->pedidoModel->countByEstado('pendiente'),
            'confirmado' => $this->pedidoModel->countByEstado('confirmado'),
            'enviado'    => $this->pedidoModel->countByEstado('enviado'),
            'entregado'  => $this->pedidoModel->countByEstado('entregado'),
            'cancelado'  => $this->pedidoModel->countByEstado('cancelado'),
        ];

        return View::render('admin/pedidos/index', [
            'title'         => 'Pedidos — Admin El Rosario',
            'pedidos'       => $pedidos,
            'estadoActual'  => $estado,
            'conteos'       => $conteos,
            'success'       => $_SESSION['pedido_success'] ?? null,
        ], 'admin');
    }

    /**
     * Detalle de un pedido
     */
    public function detalle($id) {
        $pedido = $this->pedidoModel->findById($id);

        if (!$pedido) {
            header('Location: ' . URL_BASE . '/admin/pedidos');
            exit;
        }

        $items = $this->pedidoModel->getItems($id);

        return View::render('admin/pedidos/detalle', [
            'title'  => 'Pedido ' . $pedido['folio'] . ' — Admin El Rosario',
            'pedido' => $pedido,
            'items'  => $items,
            'success'=> $_SESSION['pedido_success'] ?? null,
        ], 'admin');
    }

    /**
     * Cambiar estado de un pedido
     */
    public function cambiarEstado($id) {
        $estado = Request::post('estado');
        $estadosValidos = ['pendiente', 'confirmado', 'enviado', 'entregado', 'cancelado'];

        if (in_array($estado, $estadosValidos)) {
            $this->pedidoModel->actualizarEstado($id, $estado);
            $_SESSION['pedido_success'] = 'Estado actualizado correctamente.';
        }

        header('Location: ' . URL_BASE . '/admin/pedidos/' . $id);
        exit;
    }
}
