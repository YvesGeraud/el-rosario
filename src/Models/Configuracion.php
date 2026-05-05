<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Configuracion {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Obtener todas las configuraciones en un solo array
     */
    public function getAll() {
        $sql = "SELECT clave, valor FROM ct_configuracion";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        return $results;
    }

    /**
     * Obtener el detalle completo (para el admin)
     */
    public function getFullDetails() {
        $sql = "SELECT * FROM ct_configuracion";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Actualizar una configuración
     */
    public function update($clave, $valor) {
        $sql = "UPDATE ct_configuracion SET valor = :valor WHERE clave = :clave";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'clave' => $clave,
            'valor' => $valor
        ]);
    }
}
