<?php

namespace App\Controllers;

use App\Models\TransaksiModel;

class Booking extends BaseController
{
    public function cek()
    {
        // 1. Ambil ID_USER dari session login, jika tidak ada/kosong, paksa ke ID 1 untuk testing
        $idUser = session()->get('id_user') ?? session()->get('ID_USER') ?? session()->get('id') ?? 1;

        // 2. Ambil data dengan JOIN ke tabel gunung
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi');
        $builder->select('transaksi.*, gunung.NAMA_GUNUNG as nama_gunung'); 
        $builder->join('gunung', 'gunung.ID_GUNUNG = transaksi.ID_GUNUNG', 'left');
        
        // Kita kunci berdasarkan ID_USER agar riwayatnya sesuai dengan user yang aktif
        $builder->where('transaksi.ID_USER', $idUser);
        
        $builder->orderBy('transaksi.TGL_BOOKING', 'DESC');
        $daftarBookingRaw = $builder->get()->getResultArray();

        // 3. Mapping data dari HURUF KAPITAL database ke huruf kecil untuk kebutuhan View
        $daftarBooking = [];
        foreach ($daftarBookingRaw as $b) {
            $daftarBooking[] = [
                'id_transaksi'    => $b['ID_TRANSAKSI'] ?? '',
                'nama_gunung'     => $b['nama_gunung'] ?? 'Gunung Tidak Diketahui',
                'tanggal_booking' => $b['TGL_BOOKING'] ?? date('Y-m-d H:i:s'),
                'tanggal_mendaki' => $b['TGL_MENDAKI'] ?? '',
                'sesi'            => $b['SESI'] ?? '',
                'total_harga'     => $b['TOT_BAYAR'] ?? 0,
                'status_bayar'    => $b['STATUS_BAYAR'] ?? 'Belum Bayar',
                'barcode'         => $b['BARCODE'] ?? ''
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
        // 1. Ambil ID_USER langsung dari session login
        $idUser = session()->get('id_user') ?? session()->get('ID_USER') ?? session()->get('id');

        // Fallback jika belum login saat testing, pinjam user yang ada di database
        if (!$idUser) {
            $db = \Config\Database::connect();
            $userSatu = $db->table('user')->get()->getRowArray();
            
            if ($userSatu) {
                $idUser = $userSatu['ID_USER'];
            } else {
                return redirect()->to('login')->with('error', 'Silakan login terlebih dahulu.');
            }
        }

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