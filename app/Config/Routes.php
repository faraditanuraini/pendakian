<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rute Publik Utama
$routes->get('/', 'Home::index');
$routes->get('destinasi', 'Destinasi::index');
$routes->get('tiket', 'Tiket::index');
$routes->get('sewa-alat', 'Home::sewaAlat');
$routes->get('porter-guide', 'PorterGuide::index');
$routes->get('porter-guide/(:num)', 'PorterGuide::index/$1');

$routes->get('ojek', 'Ojek::index');
$routes->get('ojek/(:num)', 'Ojek::index/$1');

$routes->get('cek-booking', 'Booking::cek');
$routes->post('booking/simpan', 'Booking::simpan');
$routes->get('gunung/detail/(:num)', 'Gunung::detail/$1');

// Rute Autentikasi (Login & Register)
$routes->get('login', 'Auth::login');
$routes->post('auth/proses', 'Auth::proses');
$routes->get('logout', 'Auth::logout');

$routes->get('register', 'Auth::register');
$routes->post('auth/register', 'Auth::prosesRegister');


// Rute Area Admin (Diproteksi dengan Filter Admin)
$routes->group('admin', ['filter' => 'adminFilter'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // Kelola Destinasi Gunung
    $routes->get('gunung', 'Admin\Gunung::index');
    $routes->post('gunung/updateStatus', 'Admin\Gunung::updateStatus');
    $routes->get('gunung/tambah', 'Admin\Gunung::tambah');
    $routes->post('gunung/simpan', 'Admin\Gunung::simpan');
    $routes->get('gunung/edit/(:num)', 'Admin\Gunung::edit/$1');
    $routes->post('gunung/update/(:num)', 'Admin\Gunung::update/$1');
    $routes->get('gunung/hapus/(:num)', 'Admin\Gunung::hapus/$1');
    

});
