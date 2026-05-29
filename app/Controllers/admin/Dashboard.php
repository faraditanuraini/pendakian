<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        // HAPUS ATAU KOMENTARI BLOK IF DI BAWAH INI:
        // if (session()->get('role') !== 'admin') {
        //     return redirect()->to(base_url('login'))->with('error', 'Akses ditolak! Anda bukan Admin.');
        // }

        // Koneksi ke database
        $db = \Config\Database::connect();

        // 1. Hitung Total Gunung secara dinamis
        $totalGunung = $db->table('gunung')->countAllResults();

        // 2. Hitung Booking Hari Ini (berdasarkan tanggal transaksi saat ini)
        $hariIni = date('Y-m-d');
        $bookingHariIni = $db->table('transaksi')
                             ->where('TGL_BOOKING', $hariIni)
                             ->countAllResults();

        // 3. Hitung Total Pendapatan dari semua akumulasi TOT_BAYAR
        $queryPendapatan = $db->table('transaksi')
                              ->selectSum('TOT_BAYAR')
                              ->get()
                              ->getRow();
        
        $totalPendapatan = $queryPendapatan->TOT_BAYAR ?? 0;

        // Bungkus data ke dalam array untuk dikirimkan ke view
        $data = [
            'total_gunung'     => $totalGunung,
            'booking_hari_ini' => $bookingHariIni,
            'total_pendapatan' => $totalPendapatan
        ];

        // Tampilkan halaman dashboard admin sambil mengirimkan data statistik
        return view('admin/dashboard', $data);
    }

    public function dashboard()
{
    $gunungModel = new \App\Models\GunungModel(); // Sesuaikan nama modelmu
    
    // Ambil semua data gunung dari database
    $data['daftar_gunung'] = $gunungModel->findAll(); 
    
    // Hitung total gunung terdaftar secara dinamis
    $data['total_gunung'] = count($data['daftar_gunung']); 
    
    // Data dummy pendukung lainnya
    $data['pendaki_aktif'] = 342;
    $data['booking_hari_ini'] = 48;
    $data['total_pendapatan'] = 28500000;
    $data['recent_transactions'] = [
        ['id' => 'TRX-001', 'user' => 'Faradita', 'gunung' => 'Gunung Welirang', 'layanan' => 'Tiket, Porter', 'total' => 1250000, 'status' => 'Lunas'],
        ['id' => 'TRX-002', 'user' => 'Bima', 'gunung' => 'Gunung Penanggungan', 'layanan' => 'Tiket, Sewa Alat', 'total' => 450000, 'status' => 'Pending'],
    ];

    return view('admin/dashboard', $data);
}
}