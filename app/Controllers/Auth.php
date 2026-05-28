<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

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

        // Menampilkan halaman login (app/Views/auth/login.php)
        return view('auth/login');
    }

    public function proses()
    {
        $session = session();
        
        $usernameInput = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($usernameInput) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Username/Email dan Password wajib diisi!');
        }

        // Cari user berdasarkan EMAIL atau NAMA_LENGKAP untuk fleksibilitas tinggi
        $user = $this->userModel->where('EMAIL', $usernameInput)
                                ->orWhere('NAMA_LENGKAP', $usernameInput)
                                ->first();

        if ($user) {
            $passwordVerified = false;

            // Fallback checking: Cek hash password (password_verify) ATAU pencocokan plain text langsung
            if (password_verify($password, $user['PASSWORD'])) {
                $passwordVerified = true;
            } elseif ($password === $user['PASSWORD']) {
                $passwordVerified = true;
            }

            if ($passwordVerified) {
                // Simpan detail user & role ke session untuk pembatasan hak akses
                $session->set([
                    'id_user'    => $user['ID_USER'],
                    'ID_USER'    => $user['ID_USER'], // support case-sensitivity
                    'username'   => $user['NAMA_LENGKAP'],
                    'email'      => $user['EMAIL'],
                    'role'       => $user['ROLE'], // 'admin' atau 'user'
                    'isLoggedIn' => true
                ]);

                // Arahkan ke dashboard jika admin
                if ($user['ROLE'] === 'admin') {
                    return redirect()->to(base_url('admin/dashboard'))->with('success', 'Selamat datang di Dashboard Admin!');
                }

                // Cek apakah ada redirect URL tersimpan di session (misal setelah booking diblokir login)
                $redirectUrl = $session->get('redirect_url');
                if ($redirectUrl) {
                    $session->remove('redirect_url'); // Bersihkan session
                    return redirect()->to($redirectUrl);
                }

                return redirect()->to(base_url('/'))->with('success', 'Selamat datang kembali, ' . $user['NAMA_LENGKAP'] . '!');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Username/Email atau Password salah!');
    }

    public function register()
    {
        // Jika sudah login, langsung arahkan ke beranda
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('/'));
        }

        // Tampilkan view register baru
        return view('auth/register');
    }

    public function prosesRegister()
    {
        $namaLengkap = $this->request->getPost('nama_lengkap');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validasi input
        if (empty($namaLengkap) || empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Semua kolom registrasi wajib diisi!');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->withInput()->with('error', 'Password minimal harus terdiri dari 6 karakter!');
        }

        // Cek apakah email sudah terdaftar sebelumnya
        $existing = $this->userModel->where('EMAIL', $email)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Email tersebut sudah terdaftar! Gunakan email lain.');
        }

        // Ambil ID berikutnya secara otomatis
        $nextId = $this->userModel->getNextId();

        // Siapkan data user baru
        $dataNewUser = [
            'ID_USER'      => $nextId,
            'NAMA_LENGKAP' => $namaLengkap,
            'EMAIL'        => $email,
            'PASSWORD'     => password_hash($password, PASSWORD_DEFAULT), // Otomatis enkripsi password
            'NO_WA'        => 0, // Nilai default integer
            'BAHASA'       => 'Indonesia',
            'ROLE'         => 'user' // Default role sebagai user biasa (pembeli)
        ];

        // Simpan ke database secara real-time
        if ($this->userModel->insert($dataNewUser)) {
            // Auto login setelah berhasil mendaftar
            session()->set([
                'id_user'    => $nextId,
                'ID_USER'    => $nextId,
                'username'   => $namaLengkap,
                'email'      => $email,
                'role'       => 'user',
                'isLoggedIn' => true
            ]);

            return redirect()->to(base_url('/'))->with('success', 'Registrasi sukses! Selamat bergabung di Pendaki.id.');
        }

        return redirect()->back()->withInput()->with('error', 'Registrasi gagal, terjadi kesalahan basis data.');
    }

    public function logout()
    {
        // Menghapus seluruh session login
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Anda telah berhasil keluar.');
    }
}