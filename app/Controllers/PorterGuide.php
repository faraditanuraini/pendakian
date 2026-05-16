<?php

namespace App\Controllers;

use App\Models\GunungModel;

class PorterGuide extends BaseController
{
    public function index($id = null)
    {
        $model = new GunungModel();
        $data['daftar_gunung'] = $model->where('KATEGORI !=', 'bukit')->findAll();

        if ($id) {
            $gunung = $model->find($id);
            if ($gunung) {
                $data['gunung'] = $gunung;
            }
        }

        return view('porter_guide', $data);
    }
}
