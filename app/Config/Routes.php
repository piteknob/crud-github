<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('product', 'Product::index');
$routes->get('index', 'Product::index');
$routes->post('product', 'Product::insert');
$routes->post('product', 'Product::insertObject');
$routes->get('product', 'Product::search');
$routes->post('product/(:num)', 'Product::update/$1');
$routes->delete('product/(:num)', 'Product::delete/$1');
$routes->post('product', 'Product::reduce');
$routes->post('product', 'Product::add');
$routes->post('product', 'Product::move');
$routes->post('product', 'Product::softDeleteCategory');
$routes->post('product', 'Product::restoreCategory');
$routes->post('product', 'Product::softDeleteUnit');
$routes->post('product', 'Product::restoreUnit');
$routes->post('register', 'User::register');
$routes->post('login', 'User::login');
$routes->get('token', 'User::showToken');
$routes->setAutoRoute(true);
