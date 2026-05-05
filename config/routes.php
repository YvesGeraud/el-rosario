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
