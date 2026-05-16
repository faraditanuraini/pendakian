<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Gunung extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Memanggil database
        $this->db = \Config\Database::connect();
    }

    public function detail($id)
    {
        // Mengambil data gunung berdasarkan ID yang diklik
        $query = $this->db->table('gunung')->getWhere(['ID_GUNUNG' => $id]);
        $data['gunung'] = $query->getRowArray();

        // Jika data tidak ditemukan, tampilkan error 404
        if (!$data['gunung']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Gunung dengan ID $id tidak ditemukan.");
        }

        // Menampilkan view detail (kita akan buat file view-nya di langkah berikutnya)
        return view('detail_gunung_view', $data);
    }
}