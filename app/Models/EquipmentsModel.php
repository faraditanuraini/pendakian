<?php

namespace App\Models;

use CodeIgniter\Model;

class EquipmentsModel extends Model
{
    protected $table      = 'equipments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_alat',
        'total_stok',
        'stok_tersedia',
        'kondisi',
        'harga_sewa',
        'gambar',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
