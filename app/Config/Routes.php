<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('destinasi', 'Destinasi::index');
$routes->get('tiket', 'Tiket::index');
$routes->get('sewa-alat', 'Home::sewaAlat');
$routes->get('porter-guide', 'PorterGuide::index');
$routes->get('porter-guide/(:num)', 'PorterGuide::index/$1');
// Tambahkan kode ini di bawah rute porter-guide
$routes->get('ojek', 'Ojek::index');
$routes->get('ojek/(:num)', 'Ojek::index/$1');
$routes->get('cek-booking', 'Booking::cek');
$routes->get('gunung/detail/(:num)', 'Gunung::detail/$1');




