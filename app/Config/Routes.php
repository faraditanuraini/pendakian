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
$routes->get('sewa-alat/daftar-alat', 'SewaAlat::daftarAlat');
$routes->post('sewa-alat/proses-bayar', 'SewaAlat::prosesBayar');
$routes->get('sewa-alat/sukses/(:any)', 'SewaAlat::sukses/$1');
$routes->get('porter-guide', 'PorterGuide::index');
$routes->get('porter-guide/(:num)', 'PorterGuide::index/$1');

$routes->get('ojek', 'Ojek::index');
$routes->get('ojek/(:num)', 'Ojek::index/$1');

$routes->post('ojek/proses-pesan', 'Ojek::prosesPesan');
$routes->get('ojek/sukses/(:any)', 'Ojek::sukses/$1');

$routes->get('cek-booking', 'Booking::cek');
$routes->post('booking/simpan', 'Booking::simpan');
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
    $routes->get('gunung', 'Admin\MountainController::index');
    $routes->get('gunung/create', 'Admin\MountainController::create');
    $routes->post('gunung/store', 'Admin\MountainController::store');
    $routes->get('gunung/edit/(:num)', 'Admin\MountainController::edit/$1');
    $routes->post('gunung/update/(:num)', 'Admin\MountainController::update/$1');
    $routes->get('gunung/delete/(:num)', 'Admin\MountainController::delete/$1');
    $routes->get('gunung/(:num)/routes', 'Admin\MountainController::routes/$1');
    $routes->get('gunung/(:num)/routes/create', 'Admin\MountainController::routeCreate/$1');
    $routes->post('gunung/(:num)/routes/store', 'Admin\MountainController::routeStore/$1');
    $routes->get('gunung/(:num)/routes/edit/(:num)', 'Admin\MountainController::routeEdit/$1/$2');
    $routes->post('gunung/(:num)/routes/update/(:num)', 'Admin\MountainController::routeUpdate/$1/$2');
    $routes->get('gunung/(:num)/routes/delete/(:num)', 'Admin\MountainController::routeDelete/$1/$2');
    $routes->post('gunung/toggle-status', 'Admin\MountainController::toggleStatus');
    $routes->post('gunung/updateStatus', 'Admin\MountainController::toggleStatus');

    $routes->get('mountains', 'Admin\MountainController::index');
    $routes->get('mountains/create', 'Admin\MountainController::create');
    $routes->post('mountains/store', 'Admin\MountainController::store');
    $routes->get('mountains/edit/(:num)', 'Admin\MountainController::edit/$1');
    $routes->post('mountains/update/(:num)', 'Admin\MountainController::update/$1');
    $routes->get('mountains/delete/(:num)', 'Admin\MountainController::delete/$1');
    $routes->get('mountains/(:num)/routes', 'Admin\MountainController::routes/$1');
    $routes->get('mountains/(:num)/routes/create', 'Admin\MountainController::routeCreate/$1');
    $routes->post('mountains/(:num)/routes/store', 'Admin\MountainController::routeStore/$1');
    $routes->get('mountains/(:num)/routes/edit/(:num)', 'Admin\MountainController::routeEdit/$1/$2');
    $routes->post('mountains/(:num)/routes/update/(:num)', 'Admin\MountainController::routeUpdate/$1/$2');
    $routes->get('mountains/(:num)/routes/delete/(:num)', 'Admin\MountainController::routeDelete/$1/$2');
    $routes->post('mountains/toggle-status', 'Admin\MountainController::toggleStatus');
    $routes->post('mountains/updateStatus', 'Admin\MountainController::toggleStatus');

    $routes->get('transaksi', 'Admin\TransactionController::index');
    $routes->get('transaksi/detail/(:num)', 'Admin\TransactionController::detail/$1');
    $routes->post('transaksi/check-in/(:num)', 'Admin\TransactionController::checkIn/$1');
    $routes->post('transaksi/check-out/(:num)', 'Admin\TransactionController::checkOut/$1');

    $routes->get('finance', 'Admin\FinanceController::index', ['filter' => 'financeFilter']);
    $routes->get('finance/export', 'Admin\FinanceController::exportReport', ['filter' => 'financeFilter']);

    $routes->get('transactions', 'Admin\TransactionController::index');
    $routes->get('transactions/detail/(:num)', 'Admin\TransactionController::detail/$1');
    $routes->post('transactions/check-in/(:num)', 'Admin\TransactionController::checkIn/$1');
    $routes->post('transactions/check-out/(:num)', 'Admin\TransactionController::checkOut/$1');

    $routes->get('partners', 'Admin\PartnerController::index');
    $routes->get('partners/create', 'Admin\PartnerController::create');
    $routes->post('partners/store', 'Admin\PartnerController::store');
    $routes->get('partners/edit/(:num)', 'Admin\PartnerController::edit/$1');
    $routes->post('partners/update/(:num)', 'Admin\PartnerController::update/$1');
    $routes->get('partners/delete/(:num)', 'Admin\PartnerController::delete/$1');
    $routes->post('partners/assign', 'Admin\PartnerController::assign');

    $routes->get('equipments', 'Admin\EquipmentController::index');
    $routes->get('equipments/create', 'Admin\EquipmentController::create');
    $routes->post('equipments/store', 'Admin\EquipmentController::store');
    $routes->get('equipments/edit/(:num)', 'Admin\EquipmentController::edit/$1');
    $routes->post('equipments/update/(:num)', 'Admin\EquipmentController::update/$1');
    $routes->get('equipments/delete/(:num)', 'Admin\EquipmentController::delete/$1');
    $routes->post('equipments/return/(:num)', 'Admin\EquipmentController::completeRental/$1');
});
