<?php

/** @var \App\Core\Router $router */




$router->get('/', 'HomeController@index');
$router->get('/productos', 'ProductoController@index');
$router->get('/producto/{slug}', 'ProductoController@show');
$router->get('/contacto', 'HomeController@contacto');
$router->post('/contacto', 'HomeController@storeContact');

// Rutas de Administración
$router->get('/admin/login', 'AuthController@showLogin');
$router->post('/admin/login', 'AuthController@login');
$router->get('/admin/logout', 'AuthController@logout');
$router->get('/admin/dashboard', 'AdminController@dashboard');

// Categorías
$router->get('/admin/categorias', 'CategoriaController@index');
$router->get('/admin/categorias/crear', 'CategoriaController@create');
$router->post('/admin/categorias/crear', 'CategoriaController@store');
$router->get('/admin/categorias/editar/{id}', 'CategoriaController@edit');
$router->post('/admin/categorias/editar/{id}', 'CategoriaController@update');
$router->get('/admin/categorias/eliminar/{id}', 'CategoriaController@delete');

// Productos
$router->get('/admin/productos', 'AdminProductoController@index');
$router->get('/admin/productos/crear', 'AdminProductoController@create');
$router->post('/admin/productos/crear', 'AdminProductoController@store');
$router->get('/admin/productos/editar/{id}', 'AdminProductoController@edit');
$router->post('/admin/productos/editar/{id}', 'AdminProductoController@update');
$router->get('/admin/productos/eliminar/{id}', 'AdminProductoController@delete');

// Mensajes
$router->get('/admin/mensajes', 'AdminMensajeController@index');
$router->get('/admin/mensajes/leido/{id}', 'AdminMensajeController@read');
$router->get('/admin/mensajes/eliminar/{id}', 'AdminMensajeController@delete');

// Configuración General
$router->get('/admin/configuracion', 'AdminConfigController@index');
$router->post('/admin/configuracion', 'AdminConfigController@update');

// ── E-Commerce: Clientes ────────────────────────────────────────────────────
$router->get('/registro',  'ClienteController@showRegistro');
$router->post('/registro', 'ClienteController@registro');
$router->get('/login',     'ClienteController@showLogin');
$router->post('/login',    'ClienteController@login');
$router->get('/logout-cliente', 'ClienteController@logout');
$router->get('/mi-cuenta', 'ClienteController@miCuenta');
$router->post('/mi-cuenta/actualizar', 'ClienteController@updatePerfil');

// ── E-Commerce: Carrito ─────────────────────────────────────────────────────
$router->get('/carrito',           'CarritoController@index');
$router->post('/carrito/agregar',  'CarritoController@agregar');
$router->post('/carrito/actualizar', 'CarritoController@actualizar');
$router->post('/carrito/eliminar', 'CarritoController@eliminar');

// ── E-Commerce: Checkout y Pedidos ──────────────────────────────────────────
$router->get('/checkout',  'PedidoController@showCheckout');
$router->post('/checkout', 'PedidoController@procesarCheckout');
$router->get('/pedido/{folio}', 'PedidoController@confirmacion');

// ── Stripe ─────────────────────────────────────────────────────────────────
$router->post('/stripe/create-payment-intent', 'StripeController@createPaymentIntent');
$router->post('/stripe/confirmar-pago',        'StripeController@confirmarPago');


// ── Admin: Pedidos ──────────────────────────────────────────────────────────
$router->get('/admin/pedidos',                      'AdminPedidoController@index');
$router->get('/admin/pedidos/{id}',                 'AdminPedidoController@detalle');
$router->post('/admin/pedidos/{id}/estado',         'AdminPedidoController@cambiarEstado');

