<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table            = 'transaksi';
    protected $primaryKey       = 'ID_TRANSAKSI'; 
    protected $allowedFields    = ['ID_USER', 'ID_GUNUNG', 'TGL_BOOKING', 'TGL_MENDAKI', 'SESI', 'TOT_BAYAR', 'STATUS_BAYAR', 'BARCODE'];

    // Fungsi untuk join agar tahu nama gunung yang dibooking
    public function getBookingByUser($id_user)
    {
        return $this->db->table($this->table)
            ->join('gunung', 'gunung.ID_GUNUNG = transaksi.ID_GUNUNG') // Sesuaikan huruf besar ID_GUNUNG
            ->where('transaksi.ID_USER', $id_user)
            ->orderBy('transaksi.TGL_BOOKING', 'DESC')
            ->get()->getResultArray();
    }
}