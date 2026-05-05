<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Usuario {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Buscar un usuario por su email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM ct_usuarios WHERE email = :email AND estado = 1 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * Actualizar la fecha de último acceso (opcional)
     */
    public function updateLastLogin($id) {
        $sql = "UPDATE ct_usuarios SET fecha_mod = CURRENT_TIMESTAMP WHERE id_ct_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}
