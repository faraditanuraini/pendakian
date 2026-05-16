<?php

namespace App\Controllers;

use App\Models\GunungModel;

class Tiket extends BaseController
{
    public function index()
    {
        $model = new GunungModel();
        $data['daftar_gunung'] = $model->findAll();
        
        return view('tiket', $data);
    }
}
