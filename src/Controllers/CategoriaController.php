<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;
use App\Models\Categoria;
use App\Middleware\AuthMiddleware;

class CategoriaController {
    protected $categoriaModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->categoriaModel = new Categoria();
    }

    /**
     * Listado de categorías
     */
    public function index() {
        $categorias = $this->categoriaModel->getAll();
        return View::render('admin/categorias/index', [
            'title' => 'Gestión de Categorías',
            'categorias' => $categorias
        ], 'admin');
    }

    /**
     * Mostrar formulario de creación
     */
    public function create() {
        return View::render('admin/categorias/form', [
            'title' => 'Nueva Categoría',
            'categoria' => null
        ], 'admin');
    }

    /**
     * Guardar nueva categoría
     */
    public function store() {
        Request::validateCsrf();
        $nombre = Request::post('nombre');
        $data = [
            'nombre' => $nombre,
            'slug' => $this->createSlug($nombre),
            'descripcion' => Request::post('descripcion'),
            'estado' => Request::post('estado') ?? 1
        ];

        if ($this->categoriaModel->create($data)) {
            header('Location: ' . URL_BASE . '/admin/categorias');
            exit;
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id) {
        // Buscar categoría por ID (necesitamos un método find en el modelo)
        // Por simplicidad, usaremos una consulta directa aquí o actualizaremos el modelo
        $sql = "SELECT * FROM ct_categoria WHERE id_ct_categoria = :id";
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $categoria = $stmt->fetch();

        if (!$categoria) {
            header('Location: ' . URL_BASE . '/admin/categorias');
            exit;
        }

        return View::render('admin/categorias/form', [
            'title' => 'Editar Categoría: ' . $categoria['nombre'],
            'categoria' => $categoria
        ], 'admin');
    }

    /**
     * Actualizar categoría
     */
    public function update($id) {
        Request::validateCsrf();
        $nombre = Request::post('nombre');
        $data = [
            'nombre' => $nombre,
            'slug' => $this->createSlug($nombre),
            'descripcion' => Request::post('descripcion'),
            'estado' => Request::post('estado') ?? 0
        ];

        if ($this->categoriaModel->update($id, $data)) {
            header('Location: ' . URL_BASE . '/admin/categorias');
            exit;
        }
    }

    /**
     * Eliminar categoría (Desactivar)
     */
    public function delete($id) {
        $this->categoriaModel->delete($id);
        header('Location: ' . URL_BASE . '/admin/categorias');
        exit;
    }

    /**
     * Generar un slug a partir de un texto
     */
    private function createSlug($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text);
    }
}
