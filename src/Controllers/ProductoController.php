<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Producto;

class ProductoController {
    protected $productoModel;

    public function __construct() {
        $this->productoModel = new Producto();
    }

    /**
     * Listado general de productos
     */
    public function index() {
        $categoriaSlug = \App\Core\Request::get('categoria');
        $categoriaModel = new \App\Models\Categoria();
        
        $categorias = $categoriaModel->getAll();
        
        if ($categoriaSlug) {
            $productos = $this->productoModel->getByCategory($categoriaSlug);
            $categoriaActual = $categoriaModel->findBySlug($categoriaSlug);
        } else {
            $productos = $this->productoModel->getAllActive();
            $categoriaActual = null;
        }
        
        return View::render('catalogo', [
            'title' => 'Catálogo - Blancos El Rosario',
            'productos' => $productos,
            'categorias' => $categorias,
            'categoriaActual' => $categoriaActual
        ]);
    }

    /**
     * Detalle de un producto específico
     */
    public function show($slug) {
        $producto = $this->productoModel->findBySlug($slug);

        if (!$producto) {
            header("HTTP/1.0 404 Not Found");
            echo "Producto no encontrado";
            exit;
        }

        $id = $producto['id_ct_producto'];
        $imagenes = $this->productoModel->getImages($id);
        $variantes = $this->productoModel->getVariants($id);
        
        return View::render('producto_detalle', [
            'title' => $producto['nombre'] . ' - Blancos El Rosario',
            'producto' => $producto,
            'imagenes' => $imagenes,
            'variantes' => $variantes
        ]);
    }
}
