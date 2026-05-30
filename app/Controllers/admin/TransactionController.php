<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GunungModel;
use App\Models\TransactionsModel;
use App\Models\PartnerAssignmentsModel;

class TransactionController extends BaseController
{
    protected $transactionModel;
    protected $mountainModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionsModel();
        $this->mountainModel = new GunungModel();
    }

    public function index()
    {
        $transactions = $this->transactionModel
    ->select('transaksi.*, gunung.NAMA_GUNUNG as mountain_name, gunung.HARGA_TIKET as mountain_price, user.NAMA_LENGKAP as nama_lengkap')
    ->join('gunung', 'gunung.ID_GUNUNG = transaksi.ID_GUNUNG', 'left') 
    ->join('user', 'user.ID_USER = transaksi.ID_USER', 'left')
    ->orderBy('transaksi.TGL_BOOKING', 'DESC')
    ->findAll();

        $db = \Config\Database::connect();

        $data = array_map(function ($trx) use ($db) {
            // Nama ketua: gunakan nama lengkap user, lalu fallback ke ID_USER
            $namaKetua = trim($trx['nama_lengkap'] ?? '');
            if (empty($namaKetua)) {
                $namaKetua = $trx['ID_USER'] ? ('User ID: ' . $trx['ID_USER']) : '-';
            }

            $jumlahAnggota = $this->calculateJumlahAnggota($db, $trx, $trx['mountain_price'] ?? null);

            return [
                'id' => $trx['ID_TRANSAKSI'],
                'nama_ketua' => $namaKetua,
                'gunung' => $trx['mountain_name'] ?? 'Tidak Diketahui',
                'tanggal_mendaki' => $trx['TGL_MENDAKI'] ?? '-',
                'jumlah_anggota' => $jumlahAnggota,
                'status_pembayaran' => $trx['STATUS_BAYAR'] ?? 'Belum Bayar',
                'status_kehadiran' => $trx['STATUS_KEHADIRAN'] ?? ($trx['status_kehadiran'] ?? 'Pending'),
            ];
        }, $transactions);

        return view('admin/transaksi/index', ['transactions' => $data]);
    }

    public function detail($id)
{
    // 1. Ambil data transaksi berdasarkan ID_TRANSAKSI, bukan 'id'
    $trx = $this->transactionModel->where('ID_TRANSAKSI', $id)->first();

    if (!$trx) {
        return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Transaksi tidak ditemukan']);
    }

    // 2. Ambil data gunung berdasarkan ID_GUNUNG, bukan 'mountain_id'
    $mountain = $this->mountainModel->find($trx['ID_GUNUNG']);
    
    // 3. Sesuaikan array payload dengan nama kolom di database
    // Ambil nama ketua dari field transaksi dulu, lalu gunakan tabel user bila kosong
    $db = \Config\Database::connect();
    $namaKetua = trim($trx['nama_lengkap'] ?? '');
    if (empty($namaKetua) && !empty($trx['ID_USER'])) {
        $user = $db->table('user')->where('ID_USER', $trx['ID_USER'])->get()->getRowArray();
        if ($user) {
            $namaKetua = trim($user['NAMA_LENGKAP'] ?? '');
        }
    }

    if (empty($namaKetua)) {
        $namaKetua = $trx['ID_USER'] ? ('User ID: ' . $trx['ID_USER']) : '-';
    }

    $mountainPrice = $mountain['HARGA_TIKET'] ?? null;
    $jumlahAnggota = $this->calculateJumlahAnggota($db, $trx, $mountainPrice);
    $memberDetails = [];
    $memberSource = 'fallback';

    if ($db->tableExists('anggota_pendaki')) {
        $memberDetails = $db->table('anggota_pendaki')
            ->where('transaction_id', $trx['ID_TRANSAKSI'])
            ->get()
            ->getResultArray();

        if (!empty($memberDetails)) {
            $memberSource = 'anggota_pendaki';
        }
    }

    $payload = [
        'id' => $trx['ID_TRANSAKSI'],
        'nama_ketua' => $namaKetua,
        'gunung' => $mountain['NAMA_GUNUNG'] ?? 'Tidak Diketahui',
        'tanggal_mendaki' => $trx['TGL_MENDAKI'] ?? '-',
        'status_pembayaran' => $trx['STATUS_BAYAR'] ?? 'Belum Bayar',
        'status_kehadiran' => $trx['STATUS_KEHADIRAN'] ?? ($trx['status_kehadiran'] ?? 'Pending'),
        'total_harga' => $trx['TOT_BAYAR'] ?? 0,
        'mountain_price' => $mountainPrice,
        'jumlah_anggota' => $jumlahAnggota,
        'member_source' => $memberSource,
        'members' => $memberDetails,
    ];

    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success' => true, 'data' => $payload]);
    }

    return view('admin/transaksi/detail', ['transaction' => $payload]);
}

    // 🛠️ PERBAIKAN: CheckIn & CheckOut harus update berdasarkan ID_TRANSAKSI
    public function checkIn($id)
    {
        $this->transactionModel->update($id, ['STATUS_KEHADIRAN' => 'Di Atas Gunung']); // Pastikan kolom ini ada
        return $this->response->setJSON(['success' => true, 'message' => 'Diperbarui']);
    }

    private function calculateJumlahAnggota($db, array $trx, $mountainPrice = null): int
    {
        $jumlahAnggota = 1;

        if ($db->tableExists('anggota_pendaki')) {
            $count = (int) $db->table('anggota_pendaki')
                ->where('transaction_id', $trx['ID_TRANSAKSI'])
                ->countAllResults();
            if ($count > 0) {
                return $count;
            }
        }

        if (!empty($mountainPrice) && !empty($trx['TOT_BAYAR']) && (empty($trx['BARCODE']) || !str_starts_with($trx['BARCODE'], 'SEWA-'))) {
            $calculatedCount = (int) round($trx['TOT_BAYAR'] / $mountainPrice);
            if ($calculatedCount > 0) {
                return $calculatedCount;
            }
        }

        return $jumlahAnggota;
    }

    public function checkOut($id)
    {
        $this->transactionModel->update($id, ['STATUS_KEHADIRAN' => 'Sudah Turun']);
        return $this->response->setJSON(['success' => true, 'message' => 'Diperbarui']);
    }
}