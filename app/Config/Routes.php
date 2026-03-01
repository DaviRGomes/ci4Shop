<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::store');
$routes->get('/logout', 'AuthController::logout');

// Home (protegido)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/home', 'HomeController::index');
    $routes->get('/products', 'HomeController::products');
    
    // Perfil
    $routes->get('/profile/password', 'AuthController::changePasswordForm');
    $routes->post('/profile/password', 'AuthController::changePassword');

    // Pedidos
    $routes->post('/orders/create', 'OrderController::create');
    $routes->get('/orders', 'OrderController::index');
    $routes->get('/orders/(:num)', 'OrderController::show/$1');
    $routes->post('/orders/(:num)/cancel', 'OrderController::cancel/$1');
});
