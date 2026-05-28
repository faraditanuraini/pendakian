<?php

namespace App\Controllers;

use App\Models\GunungModel;

class Destinasi extends BaseController
{
    public function index()
    {
        $gunungModel = new GunungModel();
        
        // Tangkap keyword pencarian dari query string (GET)
        $search = $this->request->getGet('search');

        if (!empty($search)) {
            // Lakukan pencarian menggunakan query builder dengan operator LIKE
            $data['daftar_gunung'] = $gunungModel->like('NAMA_GUNUNG', $search)
                                                 ->orLike('LOKASI', $search)
                                                 ->findAll();
        } else {
            // Mengambil semua data gunung dari database jika keyword kosong
            $data['daftar_gunung'] = $gunungModel->findAll();
        }
        
        $data['search'] = $search;

        return view('destinasi_view', $data);
    }
}