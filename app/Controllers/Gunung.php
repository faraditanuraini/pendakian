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
        // Trigger login jika pengguna belum masuk
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login?redirect=' . urlencode(current_url())))
                             ->with('info', 'Silakan masuk / daftar untuk melanjutkan pemesanan tiket.');
        }

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