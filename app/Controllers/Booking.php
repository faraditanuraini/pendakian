<?php

namespace App\Controllers;

use App\Models\TransaksiModel;

class Booking extends BaseController
{
    public function cek()
    {
        $session = session();
        $model = new TransaksiModel();
        
        // Contoh id_user dari session (pastikan user sudah login)
        $id_user = $session->get('id_user') ?? 1; 
        
        $data['booking_list'] = $model->getBookingByUser($id_user);

        return view('cek_booking_view', $data);
    }
}