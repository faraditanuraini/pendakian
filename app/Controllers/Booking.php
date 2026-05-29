<?php

namespace App\Controllers;

use App\Models\TransaksiModel;

class Booking extends BaseController
{
    public function cek()
    {
        // Proteksi Halaman: Jika user BELUM login (session kosong), redirect paksa ke halaman login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman Cek Booking.');
        }

        // 1. Ambil ID_USER dari session login yang sedang aktif secara aman (tanpa fallback default 1)
        $idUser = session()->get('id_user') ?? session()->get('ID_USER') ?? session()->get('id');

        // 2. Ambil data dengan JOIN ke tabel gunung dan user
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi');
        $builder->select('transaksi.*, gunung.NAMA_GUNUNG as nama_gunung, user.NAMA_LENGKAP as nama_lengkap, user.NO_WA as no_wa'); 
        $builder->join('gunung', 'gunung.ID_GUNUNG = transaksi.ID_GUNUNG', 'left');
        $builder->join('user', 'user.ID_USER = transaksi.ID_USER', 'left');
        
        // Kita kunci berdasarkan ID_USER dari session agar riwayatnya sesuai dengan user yang aktif (Saringan Riwayat Pribadi)
        $builder->where('transaksi.ID_USER', $idUser);
        
        $builder->orderBy('transaksi.TGL_BOOKING', 'DESC');
        $daftarBookingRaw = $builder->get()->getResultArray();

        // 3. Mapping data dari HURUF KAPITAL database ke huruf kecil untuk kebutuhan View
        $daftarBooking = [];
        foreach ($daftarBookingRaw as $b) {
            $isPaid = in_array($b['STATUS_BAYAR'] ?? '', ['Sudah Bayar', 'Lunas']);
            $barcodeManifest = '';
            
            if ($isPaid) {
                $tglTurun = date('Y-m-d', strtotime(($b['TGL_MENDAKI'] ?? '') . ' + 2 days'));
                $trxData = [
                    'barcode'     => $b['BARCODE'] ?? '',
                    'nm_gunung'   => $b['nama_gunung'] ?? 'Gunung',
                    'nm_lengkap'  => $b['nama_lengkap'] ?? 'Pendaki',
                    'sesi'        => $b['SESI'] ?? 'Pos Utama',
                    'tgl_mendaki' => $b['TGL_MENDAKI'] ?? '',
                    'tgl_turun'   => $tglTurun,
                    'no_wa'       => $b['no_wa'] ?? 'Tidak Diketahui'
                ];
                $barcodeManifest = $this->susunTeksQRCode($trxData);
            } else {
                $barcodeManifest = $b['BARCODE'] ?? '';
            }

            $daftarBooking[] = [
                'id_transaksi'     => $b['ID_TRANSAKSI'] ?? '',
                'nama_gunung'      => $b['nama_gunung'] ?? 'Gunung Tidak Diketahui',
                'tanggal_booking'  => $b['TGL_BOOKING'] ?? date('Y-m-d H:i:s'),
                'tanggal_mendaki'  => $b['TGL_MENDAKI'] ?? '',
                'sesi'             => $b['SESI'] ?? '',
                'total_harga'      => $b['TOT_BAYAR'] ?? 0,
                'status_bayar'     => $b['STATUS_BAYAR'] ?? 'Belum Bayar',
                'barcode'          => $barcodeManifest,
                'barcode_official' => $b['BARCODE'] ?? '',
                'nama_lengkap'     => $b['nama_lengkap'] ?? 'Pendaki'
            ];
        }

        // 4. Cek apakah ada pemicu Flashdata untuk membuka modal QRIS secara otomatis
        $bukaModalId = session()->getFlashdata('buka_modal_id');
        $detailTransaksiSelected = null;

        if ($bukaModalId) {
            foreach ($daftarBooking as $item) {
                if ($item['id_transaksi'] == $bukaModalId) {
                    $detailTransaksiSelected = $item;
                    break;
                }
            }
        }

        // 5. Kirim data ke view cek_booking_view
        $data = [
            'daftar_booking'    => $daftarBooking,
            'booking'           => $daftarBooking,
            'buka_modal'        => !empty($detailTransaksiSelected),
            'transaksi_terbaru' => $detailTransaksiSelected
        ];

        return view('cek_booking_view', $data);
    }

    public function simpan()
    {
        // Proteksi Form: User harus login sebelum menyimpan pesanan baru
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu untuk melakukan transaksi booking.');
        }

        // 1. Ambil ID_USER langsung dari session login
        $idUser = session()->get('id_user') ?? session()->get('ID_USER') ?? session()->get('id');

        // 2. Tangkap data dari form view sewa alat
        $idGunung   = $this->request->getPost('id_gunung');
        $tglMendaki = $this->request->getPost('tgl_mendaki');
        $totBayar   = $this->request->getPost('tot_bayar');
        $sesi       = $this->request->getPost('sesi');

        // Validasi input dasar
        if (empty($idGunung) || empty($tglMendaki)) {
            return redirect()->back()->with('error', 'Data lokasi gunung atau tanggal mendaki tidak boleh kosong.');
        }

        // 3. Generate ID_TRANSAKSI unik langsung dari PHP (Solusi bypass Foreign Key Lock)
        $idTransaksiUnik = rand(100, 999) . substr(time(), -6); 

        // 4. Siapkan data untuk dimasukkan ke tabel transaksi
        $dataTransaksi = [
            'ID_TRANSAKSI' => $idTransaksiUnik,
            'ID_USER'      => $idUser,
            'ID_GUNUNG'    => $idGunung,
            'TGL_BOOKING'  => date('Y-m-d H:i:s'),
            'TGL_MENDAKI'  => $tglMendaki,
            'SESI'         => $sesi,
            'TOT_BAYAR'    => $totBayar,
            'STATUS_BAYAR' => 'Belum Bayar',
            'BARCODE'      => 'TRX-' . time() . '-' . rand(100, 999)
        ];

        // 5. Lakukan query insert ke tabel transaksi
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi');
        
        if ($builder->insert($dataTransaksi)) {
            // Simpan ID yang baru saja dibuat ke session flashdata agar bisa dibaca halaman cek-booking
            session()->setFlashdata('buka_modal_id', $idTransaksiUnik);

            // Redirect ke rute cek-booking sesuai file Routes.php kamu
            return redirect()->to('cek-booking')->with('success', 'Pemesanan alat berhasil disimpan!');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi, silakan coba lagi.');
        }
    }
}