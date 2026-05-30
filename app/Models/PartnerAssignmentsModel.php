<?php

namespace App\Models;

use CodeIgniter\Model;

class PartnerAssignmentsModel extends Model
{
    protected $table      = 'partner_assignments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_id',
        'partner_id',
        'tgl_tugas',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
