<?php

namespace App\Controllers;

use App\Models\GunungModel;
use App\Models\TransaksiModel;

class Home extends BaseController
{
    public function index()
    {
        $riwayatTransaksi = [];
        $riwayatPesanan = [];

        // Ambil data transaksi hanya jika user sudah login
        if (session()->get('isLoggedIn')) {
            $idUserSkg = session()->get('id_user') ?? session()->get('ID_USER') ?? session()->get('id'); 

            if ($idUserSkg) {
                $db = \Config\Database::connect();
                $builder = $db->table('transaksi');
                $builder->select('transaksi.*, gunung.NAMA_GUNUNG as nama_gunung');
                $builder->join('gunung', 'gunung.ID_GUNUNG = transaksi.ID_GUNUNG', 'left');
                $builder->where('transaksi.ID_USER', $idUserSkg);
                $builder->orderBy('transaksi.ID_TRANSAKSI', 'DESC'); 
                $builder->limit(3); // Batasi query SQL langsung maksimal 3 data terbaru
                
                $riwayatTransaksiRaw = $builder->get()->getResultArray();

                // Mapping data dari HURUF KAPITAL DB ke huruf kecil
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

                $transaksiModel = new TransaksiModel();
                $riwayatPesanan = $transaksiModel->getBookingByUser($idUserSkg);
            }
        }

        // Menyiapkan data untuk dikirim ke view
        $data['riwayat_transaksi'] = $riwayatTransaksi;
        $data['riwayat_pesanan'] = $riwayatPesanan;

        // Contoh Data Promo yang dilemparkan dari Controller ke View
        $data['data_promo'] = [
            [
                'judul' => 'Diskon Alat Outdoor 20%',
                'gambar' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800',
                'tombol' => 'AMBIL',
                'warna_button' => 'bg-orange-500 text-white',
                'gradient' => 'from-black/90 via-black/20 to-transparent'
            ],
            [
                'judul' => 'Open Trip Prau Merapi',
                'gambar' => 'https://images.unsplash.com/photo-1501555088652-021faa106b9b?w=800',
                'tombol' => 'CEK JADWAL',
                'warna_button' => 'bg-white text-forest',
                'gradient' => 'from-forest/90 via-forest/20 to-transparent'
            ]
        ];

        // Kirim data ke View halaman utama user (home.php)
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