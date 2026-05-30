<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table = 'transaksi'; // Ubah dari 'transactions' menjadi 'keuangan'
protected $primaryKey = 'ID_TRANSAKSI';
protected $allowedFields = [
    'ID_USER', 'ID_GUNUNG', 'TGL_BOOKING', 'TGL_MENDAKI', 'SESI', 
    'TOT_BAYAR', 'STATUS_BAYAR', 'BARCODE', 'STATUS_KEHADIRAN' // 👈 Tambahkan ini
];

    protected $useTimestamps = true;
}
