<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'ID_USER';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['ID_USER', 'NAMA_LENGKAP', 'EMAIL', 'PASSWORD', 'NO_WA', 'BAHASA', 'ROLE'];

    /**
     * Menghitung ID_USER berikutnya secara numerik
     */
    public function getNextId()
    {
        $builder = $this->db->table($this->table);
        $builder->selectMax('ID_USER');
        $query = $builder->get();
        $row = $query->getRow();
        
        if ($row && !empty($row->ID_USER)) {
            return (string) ((int) $row->ID_USER + 1);
        }
        
        return '1';
    }
}
