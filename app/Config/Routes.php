<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Customer Catalog & Standard Checkout routes
$routes->get('/', 'Checkout::index');
$routes->get('proses-beli/(:any)', 'Checkout::process/$1');
$routes->post('checkout/payRfid', 'Checkout::payRfid');
$routes->post('checkout/payQris', 'Checkout::payQris');
$routes->get('checkout/checkStatus/(:any)', 'Checkout::checkStatus/$1');
$routes->get('checkout/konfirmasi/(:any)', 'Checkout::konfirmasi/$1');

// POS (Point of Sale) Cashier Dashboard routes
$routes->get('pos', 'POS::index');
$routes->post('pos/checkout', 'POS::checkout');
$routes->get('pos/check-card/(:any)', 'POS::checkCard/$1');
$routes->get('pos/poll-status/(:any)', 'POS::pollStatus/$1');

// Admin Panel routes
$routes->get('admin/login', 'Admin::login');
$routes->post('admin/login', 'Admin::login');
$routes->get('admin/logout', 'Admin::logout');
$routes->get('admin', 'Admin::index');
$routes->post('admin/store', 'Admin::store');
$routes->get('admin/edit/(:num)', 'Admin::edit/$1');
$routes->get('admin/delete/(:num)', 'Admin::delete/$1');