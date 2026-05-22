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
$routes->get('cek-booking', 'Booking::cek');
$routes->get('gunung/detail/(:num)', 'Gunung::detail/$1');
// Jalur untuk halaman login dan logout
$routes->get('login', 'Auth::login');
$routes->post('auth/proses', 'Auth::proses');
$routes->get('logout', 'Auth::logout');
// Route khusus untuk Admin
$routes->group('admin', ['filter' => 'adminFilter'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // Rute Kelola Gunung
    $routes->get('gunung', 'Admin\Gunung::index');
    $routes->get('gunung/tambah', 'Admin\Gunung::tambah');
    $routes->post('gunung/simpan', 'Admin\Gunung::simpan');
    $routes->get('gunung/edit/(:num)', 'Admin\Gunung::edit/$1');
    $routes->post('gunung/update/(:num)', 'Admin\Gunung::update/$1');
    $routes->get('gunung/hapus/(:num)', 'Admin\Gunung::hapus/$1');
});

// Tambahkan route untuk proses simpan transaksi sewa alat
$routes->post('transaksi/simpan', 'Booking::simpan');
$routes->get('cek-booking', 'Booking::cek');
