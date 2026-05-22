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
}