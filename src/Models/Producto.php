<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Producto {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Obtener todos los productos activos con su imagen principal
     */
    public function getAllActive($limit = 12) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre, i.ruta as imagen_principal 
                FROM ct_producto p
                LEFT JOIN ct_categoria c ON p.id_ct_categoria = c.id_ct_categoria
                LEFT JOIN dt_imagen_producto i ON p.id_ct_producto = i.id_ct_producto AND i.es_principal = 1
                WHERE p.estado = 1
                ORDER BY p.fecha_in DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Obtener productos marcados como destacados
     */
    public function getFeatured($limit = 5) {
        $sql = "SELECT p.*, i.ruta as imagen_principal 
                FROM ct_producto p
                LEFT JOIN dt_imagen_producto i ON p.id_ct_producto = i.id_ct_producto AND i.es_principal = 1
                WHERE p.estado = 1 AND p.destacado = 1
                ORDER BY p.fecha_in DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Obtener productos por categoría
     */
    public function getByCategory($categorySlug) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre, i.ruta as imagen_principal 
                FROM ct_producto p
                JOIN ct_categoria c ON p.id_ct_categoria = c.id_ct_categoria
                LEFT JOIN dt_imagen_producto i ON p.id_ct_producto = i.id_ct_producto AND i.es_principal = 1
                WHERE p.estado = 1 AND c.slug = :slug
                ORDER BY p.fecha_in DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $categorySlug);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Obtener un producto por su slug
     */
    public function findBySlug($slug) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM ct_producto p
                LEFT JOIN ct_categoria c ON p.id_ct_categoria = c.id_ct_categoria
                WHERE p.slug = :slug AND p.estado = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /**
     * Obtener un producto por su ID
     */
    public function findById($id) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
                FROM ct_producto p
                LEFT JOIN ct_categoria c ON p.id_ct_categoria = c.id_ct_categoria
                WHERE p.id_ct_producto = :id AND p.estado = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Obtener todas las imágenes de un producto
     */
    public function getImages($id) {
        $sql = "SELECT * FROM dt_imagen_producto WHERE id_ct_producto = :id ORDER BY orden ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener todas las variantes activas de un producto
     */
    public function getVariants($id) {
        $sql = "SELECT * FROM dt_variantes_producto WHERE id_ct_producto = :id AND estado = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Crear un producto (Inicia transacción)
     */
    public function create($data) {
        $sql = "INSERT INTO ct_producto (id_ct_categoria, nombre, slug, descripcion, precio_base, destacado, estado) 
                VALUES (:id_cat, :nombre, :slug, :descripcion, :precio, :destacado, :estado)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_cat' => $data['id_ct_categoria'],
            'nombre' => $data['nombre'],
            'slug' => $data['slug'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio_base'],
            'destacado' => $data['destacado'] ?? 0,
            'estado' => $data['estado'] ?? 1
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Actualizar producto
     */
    public function update($id, $data) {
        $sql = "UPDATE ct_producto 
                SET id_ct_categoria = :id_cat, nombre = :nombre, slug = :slug, 
                    descripcion = :descripcion, precio_base = :precio, 
                    destacado = :destacado, estado = :estado 
                WHERE id_ct_producto = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'id_cat' => $data['id_ct_categoria'],
            'nombre' => $data['nombre'],
            'slug' => $data['slug'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio_base'],
            'destacado' => $data['destacado'],
            'estado' => $data['estado']
        ]);
    }

    /**
     * Gestión de Imágenes
     */
    public function addImage($id_producto, $ruta, $es_principal = 0, $orden = 0) {
        $sql = "INSERT INTO dt_imagen_producto (id_ct_producto, ruta, es_principal, orden) 
                VALUES (:id, :ruta, :principal, :orden)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id_producto,
            'ruta' => $ruta,
            'principal' => $es_principal,
            'orden' => $orden
        ]);
    }

    public function clearImages($id_producto) {
        $sql = "DELETE FROM dt_imagen_producto WHERE id_ct_producto = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id_producto]);
    }

    /**
     * Gestión de Variantes
     */
    public function addVariant($id_producto, $data) {
        $sql = "INSERT INTO dt_variantes_producto (id_ct_producto, nombre, tipo, valor, precio_extra, stock) 
                VALUES (:id, :nombre, :tipo, :valor, :precio, :stock)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id_producto,
            'nombre' => $data['nombre'],
            'tipo' => $data['tipo'],
            'valor' => $data['valor'],
            'precio' => $data['precio_extra'] ?? 0,
            'stock' => $data['stock'] ?? 0
        ]);
    }

    public function clearVariants($id_producto) {
        $sql = "DELETE FROM dt_variantes_producto WHERE id_ct_producto = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id_producto]);
    }

    public function delete($id) {
        $sql = "UPDATE ct_producto SET estado = 0 WHERE id_ct_producto = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
