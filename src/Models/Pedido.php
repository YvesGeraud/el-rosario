<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Pedido {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Generar el siguiente folio disponible (ER-YYYY-NNN)
     */
    public function generarFolio() {
        $anio = date('Y');
        $sql  = "SELECT COUNT(*) FROM ct_pedido WHERE YEAR(fecha_in) = :anio";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['anio' => $anio]);
        $count = (int)$stmt->fetchColumn();
        return 'ER-' . $anio . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Crear un pedido completo (cabecera + detalle)
     */
    public function crear($datos, $items) {
        $folio = $this->generarFolio();

        // Cabecera
        $sql = "INSERT INTO ct_pedido 
                    (folio, id_ct_cliente, nombre_cliente, email_cliente, telefono_cliente,
                     direccion_entrega, municipio, estado_entrega, cp,
                     metodo_pago, stripe_intent_id, subtotal, costo_envio, total, notas)
                VALUES
                    (:folio, :cliente, :nombre, :email, :telefono,
                     :direccion, :municipio, :estado_ent, :cp,
                     :metodo, :stripe_id, :subtotal, :envio, :total, :notas)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'folio'      => $folio,
            'cliente'    => $datos['id_ct_cliente'] ?? null,
            'nombre'     => $datos['nombre_cliente'],
            'email'      => $datos['email_cliente'] ?? null,
            'telefono'   => $datos['telefono_cliente'] ?? null,
            'direccion'  => $datos['direccion_entrega'],
            'municipio'  => $datos['municipio'] ?? null,
            'estado_ent' => $datos['estado_entrega'] ?? null,
            'cp'         => $datos['cp'] ?? null,
            'metodo'     => $datos['metodo_pago'],
            'subtotal'   => $datos['subtotal'],
            'envio'      => $datos['costo_envio'],
            'total'      => $datos['total'],
            'notas'      => $datos['notas'] ?? null,
            'stripe_id'  => $datos['stripe_intent_id'] ?? null,
        ]);

        $id_pedido = $this->db->lastInsertId();

        // Detalle (ítems del carrito)
        $sqlItem = "INSERT INTO dt_pedido_producto
                        (id_ct_pedido, id_ct_producto, nombre_producto, variante_descripcion,
                         precio_unitario, cantidad, subtotal)
                    VALUES (:pedido, :producto, :nombre, :variante, :precio, :cantidad, :subtotal)";
        $stmtItem = $this->db->prepare($sqlItem);

        foreach ($items as $item) {
            $stmtItem->execute([
                'pedido'   => $id_pedido,
                'producto' => $item['id_producto'],
                'nombre'   => $item['nombre'],
                'variante' => $item['variante_desc'] ?? null,
                'precio'   => $item['precio'],
                'cantidad' => $item['cantidad'],
                'subtotal' => $item['precio'] * $item['cantidad'],
            ]);
        }

        return ['id' => $id_pedido, 'folio' => $folio];
    }

    /**
     * Obtener pedido por folio (para confirmación pública)
     */
    public function findByFolio($folio) {
        $stmt = $this->db->prepare("SELECT * FROM ct_pedido WHERE folio = :folio");
        $stmt->execute(['folio' => $folio]);
        return $stmt->fetch();
    }

    /**
     * Obtener pedido por ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ct_pedido WHERE id_ct_pedido = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener ítems de un pedido
     */
    public function getItems($id_pedido) {
        $stmt = $this->db->prepare(
            "SELECT * FROM dt_pedido_producto WHERE id_ct_pedido = :id"
        );
        $stmt->execute(['id' => $id_pedido]);
        return $stmt->fetchAll();
    }

    /**
     * Listar pedidos (admin) con filtros opcionales
     */
    public function getAll($estado = null, $limit = 50, $offset = 0) {
        $where = $estado ? "WHERE p.estado = :estado" : "";
        $sql   = "SELECT p.*, 
                         (SELECT COUNT(*) FROM dt_pedido_producto WHERE id_ct_pedido = p.id_ct_pedido) AS total_items
                  FROM ct_pedido p
                  {$where}
                  ORDER BY p.fecha_in DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        if ($estado) $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Contar pedidos por estado
     */
    public function countByEstado($estado = null) {
        $where = $estado ? "WHERE estado = :estado" : "";
        $sql   = "SELECT COUNT(*) FROM ct_pedido {$where}";
        $stmt  = $this->db->prepare($sql);
        if ($estado) $stmt->bindValue(':estado', $estado);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Actualizar el estado de un pedido
     */
    public function actualizarEstado($id, $estado) {
        $stmt = $this->db->prepare(
            "UPDATE ct_pedido SET estado = :estado WHERE id_ct_pedido = :id"
        );
        return $stmt->execute(['id' => $id, 'estado' => $estado]);
    }

    /**
     * Historial de pedidos de un cliente
     */
    public function getByCliente($id_cliente) {
        $stmt = $this->db->prepare(
            "SELECT * FROM ct_pedido WHERE id_ct_cliente = :id ORDER BY fecha_in DESC"
        );
        $stmt->execute(['id' => $id_cliente]);
        return $stmt->fetchAll();
    }

    /**
     * Totales del mes actual (para dashboard admin)
     */
    public function totalMes() {
        $stmt = $this->db->query(
            "SELECT COALESCE(SUM(total), 0) FROM ct_pedido 
             WHERE MONTH(fecha_in) = MONTH(NOW()) AND YEAR(fecha_in) = YEAR(NOW())
             AND estado NOT IN ('cancelado')"
        );
        return (float)$stmt->fetchColumn();
    }
}
