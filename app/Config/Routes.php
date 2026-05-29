<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rute Publik Utama
$routes->get('/', 'Home::index');
$routes->get('destinasi', 'Destinasi::index');
$routes->get('tiket', 'Tiket::index');
$routes->get('sewa-alat', 'SewaAlat::index');
$routes->post('sewa-alat/cari', 'SewaAlat::cari');
$routes->get('sewa-alat/cari', function() {
    return redirect()->to(base_url('sewa-alat'))->with('error', 'Sesi pencarian kedaluwarsa, silakan tentukan filter pencarian kembali.');
});
$routes->post('sewa-alat/proses-bayar', 'SewaAlat::prosesBayar');
$routes->get('sewa-alat/sukses/(:any)', 'SewaAlat::sukses/$1');
$routes->get('porter-guide', 'PorterGuide::index');
$routes->get('porter-guide/(:num)', 'PorterGuide::index/$1');
$routes->get('ojek', 'Ojek::index');
$routes->get('ojek/(:num)', 'Ojek::index/$1');
$routes->post('ojek/proses-pesan', 'Ojek::prosesPesan');
$routes->get('ojek/sukses/(:any)', 'Ojek::sukses/$1');
$routes->get('cek-booking', 'Booking::cek');
$routes->get('gunung/detail/(:num)', 'Gunung::detail/$1');

// Rute Alur Pembelian Tiket Masuk & Midtrans
$routes->post('tiket/proses_tahap1', 'Tiket::proses_tahap1');
$routes->get('tiket/biodata', 'Tiket::biodata');
$routes->post('tiket/proses_bayar', 'Tiket::proses_bayar');
$routes->get('tiket/success/(:any)', 'Tiket::success/$1');
$routes->get('tiket/getQRCodeRiwayat/(:any)', 'Tiket::getQRCodeRiwayat/$1');

// Rute Alur Porter & Guide & Midtrans
$routes->post('porter-guide/proses_bayar', 'PorterGuide::proses_bayar');
$routes->get('porter-guide/success/(:any)', 'PorterGuide::success/$1');

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
    $routes->get('gunung/tambah', 'Admin\Gunung::tambah');
    $routes->post('gunung/simpan', 'Admin\Gunung::simpan');
    $routes->get('gunung/edit/(:num)', 'Admin\Gunung::edit/$1');
    $routes->post('gunung/update/(:num)', 'Admin\Gunung::update/$1');
    $routes->get('gunung/hapus/(:num)', 'Admin\Gunung::hapus/$1');
});
