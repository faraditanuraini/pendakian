<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GunungModel;
use App\Models\TransactionsModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $mountainModel = new GunungModel();
        $transactionModel = new TransactionsModel();
        $hariIni = date('Y-m-d');

        // 1. Hitung total gunung terdaftar
        $totalGunung = $mountainModel->countAllResults();

        // 2. Hitung pendaki aktif hari ini: jika tersedia kolom 'jumlah_anggota' gunakan SUM,
        //    jika tidak, fallback ke jumlah transaksi berstatus pembayaran Lunas/Sudah Bayar
        $pendakiAktif = 0;
        // Pastikan koneksi DB mentah untuk cek keberadaan kolom/tabel
        $db = \Config\Database::connect();
        // Jika kolom jumlah_anggota tersedia, jumlahkan kolom tersebut untuk transaksi aktif
        try {
            $hasJumlahKolom = false;
            $fields = $db->getFieldData($transactionModel->getTable());
            foreach ($fields as $f) {
                if (strtolower($f->name) === 'jumlah_anggota') {
                    $hasJumlahKolom = true; break;
                }
            }

            if ($hasJumlahKolom) {
                $row = $transactionModel
                    ->selectSum('jumlah_anggota')
                    ->whereIn('STATUS_KEHADIRAN', ['Di Atas Gunung', 'Naik', 'Naik Gunung', 'Naik'])
                    ->where('TGL_MENDAKI', $hariIni)
                    ->get()->getRow();
                $pendakiAktif = (int) ($row->jumlah_anggota ?? 0);
            } else {
                $pendakiAktif = $transactionModel
                    ->whereIn('STATUS_BAYAR', ['Lunas', 'Sudah Bayar'])
                    ->where('TGL_MENDAKI', $hariIni)
                    ->countAllResults();
            }
        } catch (\Exception $e) {
            // Fallback aman
            $pendakiAktif = $transactionModel
                ->whereIn('STATUS_BAYAR', ['Lunas', 'Sudah Bayar'])
                ->where('TGL_MENDAKI', $hariIni)
                ->countAllResults();
        }

        // 3. Hitung booking hari ini
        $bookingHariIni = $transactionModel
            ->where('TGL_MENDAKI', $hariIni)
            ->countAllResults();

        // 4. Hitung total pendapatan (Gunakan TOT_BAYAR)
        $queryPendapatan = $transactionModel
            ->selectSum('TOT_BAYAR')
            ->whereIn('STATUS_BAYAR', ['Lunas', 'Sudah Bayar'])
            ->get()
            ->getRow();

        $totalPendapatan = (int) ($queryPendapatan->TOT_BAYAR ?? 0);

        // 5. Ambil daftar gunung (NAMA_GUNUNG adalah nama kolom yang benar)
        $daftarGunung = $mountainModel
            ->orderBy('NAMA_GUNUNG', 'ASC')
            ->findAll();

        // 6. Ambil transaksi terbaru (Gunakan ID_TRANSAKSI dan TGL_BOOKING)
        $recentTransactions = $transactionModel
            ->orderBy('TGL_BOOKING', 'DESC')
            ->limit(6)
            ->findAll();

        $recentTransactions = array_map(function ($trx) {
            return [
                'id'      => $trx['ID_TRANSAKSI'] ?? '', // Pakai ID_TRANSAKSI
                'user'    => 'Pendaki',
                'gunung'  => '-',
                'layanan' => 'Tiket',
                'total'   => (int) ($trx['TOT_BAYAR'] ?? 0),    // Pakai TOT_BAYAR
                'status'  => $trx['STATUS_BAYAR'] ?? 'Pending', // Pakai STATUS_BAYAR
            ];
        }, $recentTransactions);

        // 7. Parsing data ke View
        $data = [
            'total_gunung'        => $totalGunung,
            'pendaki_aktif'       => $pendakiAktif,
            'booking_hari_ini'    => $bookingHariIni,
            'total_pendapatan'    => $totalPendapatan,
            'daftar_gunung'       => $daftarGunung,
            'gunung'              => $daftarGunung,
            'recent_transactions' => $recentTransactions,
        ];

        return view('admin/dashboard', $data);
    }
}