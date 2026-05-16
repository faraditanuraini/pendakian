<?php

namespace App\Controllers;

use App\Models\GunungModel;

class Destinasi extends BaseController
{
    public function index()
    {
        $gunungModel = new GunungModel();
        
        // Mengambil semua data gunung dari database
        $data['daftar_gunung'] = $gunungModel->findAll();

        return view('destinasi_view', $data);
    }
}