<?php

namespace App\Controllers;

use App\Models\GunungModel;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home');
    }
    public function tiket(): string
    {
        return view('tiket');
    }

    public function sewaAlat(): string
    {
        $gunungModel = new GunungModel();
        $data['daftar_gunung'] = $gunungModel->findAll();
        return view('sewa_alat', $data);
    }
}
