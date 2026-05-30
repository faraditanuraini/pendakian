<?php

namespace App\Models;

use CodeIgniter\Model;

class MountainRoutesModel extends Model
{
    protected $table      = 'mountain_routes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'mountain_id',
        'route_name',
        'difficulty',
        'distance_km',
        'duration',
        'status_open',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
