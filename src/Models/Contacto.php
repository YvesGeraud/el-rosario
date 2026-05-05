<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Contacto {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Guardar un nuevo mensaje de contacto
     */
    public function create($data) {
        $sql = "INSERT INTO dt_contacto (id_ct_producto, nombre, email, telefono, mensaje) 
                VALUES (:id_prod, :nombre, :email, :telefono, :mensaje)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_prod' => $data['id_ct_producto'] ?? null,
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'mensaje' => $data['mensaje']
        ]);
    }

    /**
     * Obtener todos los mensajes para el admin
     */
    public function getAll() {
        $sql = "SELECT c.*, p.nombre as producto_nombre 
                FROM dt_contacto c 
                LEFT JOIN ct_producto p ON c.id_ct_producto = p.id_ct_producto
                ORDER BY c.fecha_in DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Marcar como leído
     */
    public function markAsRead($id) {
        $sql = "UPDATE dt_contacto SET leido = 1 WHERE id_dt_contacto = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM dt_contacto WHERE id_dt_contacto = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
