<?php

namespace App\Models;

use CodeIgniter\Model;

class PeralatanModel extends Model
{
    protected $table            = 'peralatan';
    protected $primaryKey       = 'ID_PERALATAN';
    protected $returnType       = 'array';
    protected $allowedFields    = ['NAMA_ALAT', 'HARGA_SEWA', 'STOK', 'GAMBAR'];
}
