<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Categoria {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Obtener todas las categorías activas
     */
    public function getAll() {
        $sql = "SELECT * FROM ct_categoria WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Buscar una categoría por su slug
     */
    public function findBySlug($slug) {
        $sql = "SELECT * FROM ct_categoria WHERE slug = :slug AND estado = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Crear una nueva categoría
     */
    public function create($data) {
        $sql = "INSERT INTO ct_categoria (nombre, slug, descripcion, estado) 
                VALUES (:nombre, :slug, :descripcion, :estado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'slug' => $data['slug'],
            'descripcion' => $data['descripcion'],
            'estado' => $data['estado'] ?? 1
        ]);
    }

    /**
     * Actualizar una categoría existente
     */
    public function update($id, $data) {
        $sql = "UPDATE ct_categoria 
                SET nombre = :nombre, slug = :slug, descripcion = :descripcion, estado = :estado 
                WHERE id_ct_categoria = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nombre' => $data['nombre'],
            'slug' => $data['slug'],
            'descripcion' => $data['descripcion'],
            'estado' => $data['estado']
        ]);
    }

    /**
     * Eliminar (desactivar) una categoría
     */
    public function delete($id) {
        // En lugar de borrar físicamente, solemos cambiar el estado para no romper la integridad referencial
        $sql = "UPDATE ct_categoria SET estado = 0 WHERE id_ct_categoria = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
