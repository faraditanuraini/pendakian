<?php

namespace App\Models;

use CodeIgniter\Model;

class MountainsModel extends Model
{
    protected $table      = 'mountains';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama',
        'status_jalur',
        'kuota_harian',
        'sisa_kuota',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
