<?php

namespace App\Controllers;

use App\Core\View;

class HomeController {
    public function index() {
        $productoModel = new \App\Models\Producto();
        $destacados = $productoModel->getFeatured();

        return View::render('home', [
            'title' => 'Inicio - Blancos El Rosario',
            'message' => 'Bienvenidos a nuestra tienda de blancos',
            'destacados' => $destacados
        ]);
    }

    public function contacto() {
        return View::render('contacto', [
            'title' => 'Contacto - Blancos El Rosario'
        ]);
    }

    public function storeContact() {
        $contactoModel = new \App\Models\Contacto();
        
        $data = [
            'id_ct_producto' => \App\Core\Request::post('id_ct_producto'),
            'nombre' => \App\Core\Request::post('nombre'),
            'email' => \App\Core\Request::post('email'),
            'telefono' => \App\Core\Request::post('telefono'),
            'mensaje' => \App\Core\Request::post('mensaje')
        ];

        if ($contactoModel->create($data)) {
            $_SESSION['contact_success'] = '¡Gracias! Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.';
        } else {
            $_SESSION['contact_error'] = 'Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.';
        }

        header('Location: /blancos/public/contacto');
        exit;
    }
}
