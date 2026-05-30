<?php

namespace App\Models;

use CodeIgniter\Model;

class PartnersModel extends Model
{
    protected $table      = 'partners';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama',
        'tipe',
        'kontak',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
