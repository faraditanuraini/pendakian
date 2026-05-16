<?php

namespace App\Models;

use CodeIgniter\Model;

class GunungModel extends Model
{
    protected $table      = 'gunung';
    // Sesuaikan dengan PK di screenshotmu (pakai huruf besar)
    protected $primaryKey = 'ID_GUNUNG'; 
    
    // Tambahkan kolom lain yang ada di screenshot supaya bisa di-query/di-input nanti
    protected $allowedFields = [
        'NAMA_GUNUNG', 
        'GAMBAR', 
        'GAMBAR_2',
        'LOKASI', 
        'KAPASITAS_MAX', 
        'CUACA', 
        'HARGA_TIKET', 
        'KATEGORI'
    ];
}