<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table      = 'transaksi';
    protected $primaryKey = 'id_transaksi'; 
    protected $allowedFields = ['id_user', 'id_gunung', 'tanggal_booking', 'total_harga', 'status'];

    // Fungsi untuk join agar kita tahu nama gunung yang dibooking
    public function getBookingByUser($id_user)
    {
        return $this->db->table($this->table)
            ->join('gunung', 'gunung.id_gunung = transaksi.id_gunung')
            ->where('id_user', $id_user)
            ->get()->getResultArray();
    }
}