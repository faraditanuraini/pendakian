<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pastikan cek session-nya sesuai dengan yang dibuat di Controller Auth
        $allowedRoles = ['admin', 'Admin Utama', 'PetugasPos', 'PengecekAlat'];

        if (!session()->get('isLoggedIn') || ! in_array(session()->get('role'), $allowedRoles, true)) {
            return redirect()->to(base_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Dikosongkan
    }
}