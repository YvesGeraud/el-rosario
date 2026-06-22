<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Cliente {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Crear un nuevo cliente
     */
    public function create($data) {
        $sql = "INSERT INTO ct_cliente (nombre, email, password, telefono)
                VALUES (:nombre, :email, :password, :telefono)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre'   => $data['nombre'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'telefono' => $data['telefono'] ?? null,
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Buscar cliente por email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM ct_cliente WHERE email = :email AND estado = 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Buscar cliente por ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ct_cliente WHERE id_ct_cliente = :id AND estado = 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Verificar si el email ya está registrado
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id_ct_cliente FROM ct_cliente WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return (bool) $stmt->fetch();
    }

    /**
     * Actualizar datos del perfil
     */
    public function update($id, $data) {
        $sql = "UPDATE ct_cliente SET nombre = :nombre, telefono = :telefono, 
                direccion_defecto = :direccion WHERE id_ct_cliente = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'        => $id,
            'nombre'    => $data['nombre'],
            'telefono'  => $data['telefono'] ?? null,
            'direccion' => $data['direccion_defecto'] ?? null,
        ]);
    }

    /**
     * Cambiar contraseña
     */
    public function updatePassword($id, $newPassword) {
        $stmt = $this->db->prepare("UPDATE ct_cliente SET password = :password WHERE id_ct_cliente = :id");
        return $stmt->execute([
            'id'       => $id,
            'password' => password_hash($newPassword, PASSWORD_BCRYPT),
        ]);
    }
}
