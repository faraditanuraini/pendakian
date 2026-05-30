<?php

namespace App\Models;

use CodeIgniter\Model;

class GunungModel extends Model
{
    protected $table      = 'gunung';
    protected $primaryKey = 'ID_GUNUNG';
    protected $allowedFields = [
        'NAMA_GUNUNG',
        'GAMBAR',
        'GAMBAR_2',
        'LOKASI',
        'KAPASITAS_MAX',
        'CUACA',
        'HARGA_TIKET',
        'KATEGORI',
        'STATUS_JALUR',
        'KUOTA_HARIAN',
        'SISA_KUOTA'
    ];
}