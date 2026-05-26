<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function login()
    {
        // SINKRONISASI FILTER: Jika user atau admin sudah terlanjur login, arahkan sesuai role masing-masing
        if (session()->get('isLoggedIn')) {
            if (session()->get('role') === 'admin') {
                return redirect()->to(base_url('admin/dashboard'))->with('success', 'Anda sudah login sebagai Admin.');
            }
            return redirect()->to(base_url('/'));
        }
        
        // Simpan parameter redirect dari URL ke session jika ada
        $redirect = $this->request->getGet('redirect');
        if ($redirect) {
            session()->set('redirect_url', urldecode($redirect));
        }

        // Menampilkan halaman login baru (app/Views/auth/login.php)
        return view('auth/login');
    }

    public function proses()
    {
        $session = session();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if ($username === 'admin' && $password === 'adminforest') {
            $session->set([
                'username'   => $username,
                'role'       => 'admin',
                'isLoggedIn' => true
            ]);
            
            // SEKARANG DIARAHKAN LANGSUNG KE DASHBOARD ADMIN
            return redirect()->to(base_url('admin/dashboard'))->with('success', 'Selamat datang di Dashboard Admin!');
            
        } elseif ($username === 'fara' && $password === 'pembeli123') {
            $session->set([
                'username'   => $username,
                'role'       => 'pembeli',
                'isLoggedIn' => true
            ]);
            
            // Cek apakah ada redirect URL tersimpan di session
            $redirectUrl = $session->get('redirect_url');
            if ($redirectUrl) {
                $session->remove('redirect_url'); // Bersihkan session
                return redirect()->to($redirectUrl);
            }
            
            return redirect()->to(base_url('/'));
        } else {
            return redirect()->back()->with('error', 'Username atau Password salah!');
        }
    }

    public function logout()
    {
        // Menghapus seluruh session login
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}