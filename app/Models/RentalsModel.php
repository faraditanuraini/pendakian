<?php

namespace App\Models;

use CodeIgniter\Model;

class RentalsModel extends Model
{
    protected $table      = 'rentals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_id',
        'equipment_id',
        'jumlah',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
