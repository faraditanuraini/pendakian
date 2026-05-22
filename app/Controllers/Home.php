<?php

namespace App\Controllers;

use App\Models\GunungModel;
use App\Models\TransaksiModel;

class Home extends BaseController
{
    public function index()
    {
        // 1. Pastikan user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // 2. Ambil ID_USER pembeli dari session aktif (fallback ke ID 1 jika testing tanpa login)
        $idUserSkg = session()->get('id_user') ?? session()->get('ID_USER') ?? session()->get('id') ?? 1; 

        // 3. Ambil SEMUA data transaksi (tanpa limit 1) untuk dipajang di komponen "Aktivitas Terakhir" Home
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi');
        $builder->select('transaksi.*, gunung.NAMA_GUNUNG as nama_gunung');
        $builder->join('gunung', 'gunung.ID_GUNUNG = transaksi.ID_GUNUNG', 'left');
        $builder->where('transaksi.ID_USER', $idUserSkg);
        // Diurutkan berdasarkan ID_TRANSAKSI atau TGL_BOOKING paling baru
        $builder->orderBy('transaksi.ID_TRANSAKSI', 'DESC'); 
        
        // . get()->getResultArray() digunakan untuk mengambil semua baris data berupa array multidimensi
        $riwayatTransaksiRaw = $builder->get()->getResultArray();

        // 4. Mapping data dari HURUF KAPITAL DB ke huruf kecil menggunakan foreach loop
        $riwayatTransaksi = [];
        if (!empty($riwayatTransaksiRaw)) {
            foreach ($riwayatTransaksiRaw as $row) {
                $riwayatTransaksi[] = [
                    'id_transaksi'    => $row['ID_TRANSAKSI'] ?? '',
                    'nama_gunung'     => $row['nama_gunung'] ?? 'Gunung Tidak Diketahui',
                    'tanggal_booking' => $row['TGL_BOOKING'] ?? '',
                    'tanggal_mendaki' => $row['TGL_MENDAKI'] ?? '',
                    'sesi'            => $row['SESI'] ?? '',
                    'total_harga'     => $row['TOT_BAYAR'] ?? 0,
                    'status_bayar'    => $row['STATUS_BAYAR'] ?? 'Belum Bayar'
                ];
            }
        }

        // 5. Masukkan ke dalam array data kiriman view (Dipakai oleh foreach di view home)
        $data['riwayat_transaksi'] = $riwayatTransaksi;

        // Jika kamu masih butuh data riwayat lama dari model, tetap kita sertakan di sini
        $transaksiModel = new TransaksiModel();
        $data['riwayat_pesanan'] = $transaksiModel->getBookingByUser($idUserSkg);

        // 6. Kirim data ke View halaman utama user (menunjuk ke view bernama 'home.php')
        return view('home', $data); 
    }

    public function tiket(): string
    {
        // Jika halaman tiket juga butuh proteksi login, bisa kamu tambahkan logic session di sini nanti
        return view('tiket');
    }

    public function sewaAlat(): string
    {
        $gunungModel = new GunungModel();
        $data['daftar_gunung'] = $gunungModel->findAll();
        return view('sewa_alat', $data);
    }
}