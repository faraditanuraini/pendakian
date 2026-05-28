<?php

namespace App\Controllers;

use App\Models\GunungModel;

class Ojek extends BaseController
{
    public function index($id = null)
    {
        $gunungModel = new GunungModel();
        
        $data = [
            'daftar_gunung' => $gunungModel->findAll()
        ];

        // Jika user sudah memilih gunung, ambil data spesifik gunung tersebut
        if ($id != null) {
            $data['gunung'] = $gunungModel->find($id);
        }

        return view('ojek', $data);
    }
}