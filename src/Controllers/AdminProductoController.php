<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Middleware\AuthMiddleware;

class AdminProductoController {
    protected $productoModel;
    protected $categoriaModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->productoModel = new Producto();
        $this->categoriaModel = new Categoria();
    }

    /**
     * Listado de productos
     */
    public function index() {
        $productos = $this->productoModel->getAllActive(100);
        return View::render('admin/productos/index', [
            'title' => 'Gestión de Productos',
            'productos' => $productos
        ], 'admin');
    }

    /**
     * Mostrar formulario de creación
     */
    public function create() {
        $categorias = $this->categoriaModel->getAll();
        return View::render('admin/productos/form', [
            'title' => 'Nuevo Producto',
            'producto' => null,
            'categorias' => $categorias,
            'imagenes' => [],
            'variantes' => []
        ], 'admin');
    }

    /**
     * Guardar nuevo producto
     */
    public function store() {
        $nombre = Request::post('nombre');
        $data = [
            'id_ct_categoria' => Request::post('id_ct_categoria'),
            'nombre' => $nombre,
            'slug' => $this->createSlug($nombre),
            'descripcion' => Request::post('descripcion'),
            'precio_base' => Request::post('precio_base'),
            'destacado' => Request::post('destacado') ? 1 : 0,
            'estado' => Request::post('estado') ?? 1
        ];

        $id = $this->productoModel->create($data);

        if ($id) {
            $this->handleImages($id);
            $this->handleVariants($id);
            header('Location: ' . URL_BASE . '/admin/productos');
            exit;
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id) {
        $producto = $this->productoModel->findById($id);
        if (!$producto) {
            header('Location: ' . URL_BASE . '/admin/productos');
            exit;
        }

        $categorias = $this->categoriaModel->getAll();
        $imagenes = $this->productoModel->getImages($id);
        $variantes = $this->productoModel->getVariants($id);

        return View::render('admin/productos/form', [
            'title' => 'Editar Producto: ' . $producto['nombre'],
            'producto' => $producto,
            'categorias' => $categorias,
            'imagenes' => $imagenes,
            'variantes' => $variantes
        ], 'admin');
    }

    /**
     * Actualizar producto
     */
    public function update($id) {
        $nombre = Request::post('nombre');
        $data = [
            'id_ct_categoria' => Request::post('id_ct_categoria'),
            'nombre' => $nombre,
            'slug' => $this->createSlug($nombre),
            'descripcion' => Request::post('descripcion'),
            'precio_base' => Request::post('precio_base'),
            'destacado' => Request::post('destacado') ? 1 : 0,
            'estado' => Request::post('estado') ?? 0
        ];

        if ($this->productoModel->update($id, $data)) {
            $this->handleImages($id);
            $this->handleVariants($id);
            header('Location: ' . URL_BASE . '/admin/productos');
            exit;
        }
    }

    /**
     * Eliminar (Desactivar)
     */
    public function delete($id) {
        $this->productoModel->delete($id);
        header('Location: ' . URL_BASE . '/admin/productos');
        exit;
    }

    /**
     * Procesar Subida de Imágenes
     */
    private function handleImages($id_producto) {
        if (!isset($_FILES['imagenes']) || empty($_FILES['imagenes']['name'][0])) return;

        $uploadDir = __DIR__ . '/../../public/uploads/productos/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        // Si se suben nuevas, podríamos querer limpiar las anteriores o solo añadir
        // Por ahora solo añadiremos. La lógica de borrar imágenes específicas iría aquí.

        foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['imagenes']['error'][$key] === UPLOAD_ERR_OK) {
                $name = time() . '_' . basename($_FILES['imagenes']['name'][$key]);
                $targetPath = $uploadDir . $name;
                $dbPath = '' . URL_BASE . '/uploads/productos/' . $name;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $es_principal = ($key === 0 && count($this->productoModel->getImages($id_producto)) === 0) ? 1 : 0;
                    $this->productoModel->addImage($id_producto, $dbPath, $es_principal, $key);
                }
            }
        }
    }

    /**
     * Procesar Variantes
     */
    private function handleVariants($id_producto) {
        $this->productoModel->clearVariants($id_producto);
        
        $variantes = $_POST['variantes'] ?? [];
        foreach ($variantes as $v) {
            if (!empty($v['nombre']) && !empty($v['valor'])) {
                $this->productoModel->addVariant($id_producto, $v);
            }
        }
    }

    private function createSlug($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text);
    }
}
