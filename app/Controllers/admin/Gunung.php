<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GunungModel;

class Gunung extends BaseController
{
    protected $gunungModel;

    public function __construct()
    {
        // Inisialisasi Model Gunung
        $this->gunungModel = new GunungModel();
    }

    // 1. TAMPILKAN DAFTAR GUNUNG
    public function index()
    {
        $data = [
            'title'         => 'Kelola Data Gunung',
            'daftar_gunung' => $this->gunungModel->findAll()
        ];

        return view('admin/gunung/index', $data);
    }

    // 2. FORM TAMBAH DATA
    public function tambah()
    {
        $data = [
            'title' => 'Tambah Destinasi Gunung'
        ];

        return view('admin/gunung/tambah', $data);
    }

    // 3. PROSES SIMPAN DATA BARU
    public function simpan()
    {
        // Validasi input form
        if (!$this->validate([
            'NAMA_GUNUNG'   => 'required',
            'LOKASI'        => 'required',
            'KAPASITAS_MAX' => 'required|numeric',
            'HARGA_TIKET'   => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Mohon isi semua kolom utama dengan benar!');
        }

        // Ambil data kiriman form
        $this->gunungModel->save([
            'NAMA_GUNUNG'   => $this->request->getPost('NAMA_GUNUNG'),
            'LOKASI'        => $this->request->getPost('LOKASI'),
            'KAPASITAS_MAX' => $this->request->getPost('KAPASITAS_MAX'),
            'CUACA'         => $this->request->getPost('CUACA'),
            'HARGA_TIKET'   => $this->request->getPost('HARGA_TIKET'),
            'KATEGORI'      => $this->request->getPost('KATEGORI'),
            'GAMBAR'        => 'default.jpg', // Berikan nilai default jika upload belum dihandle
            'GAMBAR_2'      => 'default2.jpg'
        ]);

        return redirect()->to(base_url('admin/gunung'))->with('success', 'Destinasi pendakian baru berhasil ditambahkan!');
    }

    // 4. FORM EDIT DATA
    public function edit($id)
    {
        $gunung = $this->gunungModel->find($id);

        if (!$gunung) {
            return redirect()->to(base_url('admin/gunung'))->with('error', 'Data destinasi tidak ditemukan!');
        }

        $data = [
            'title'  => 'Edit Destinasi ' . $gunung['NAMA_GUNUNG'],
            'gunung' => $gunung
        ];

        return view('admin/gunung/edit', $data);
    }

    // 5. PROSES UPDATE DATA
    public function update($id)
    {
        if (!$this->validate([
            'NAMA_GUNUNG'   => 'required',
            'LOKASI'        => 'required',
            'KAPASITAS_MAX' => 'required|numeric',
            'HARGA_TIKET'   => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui, periksa kembali inputan Anda.');
        }

        // Update data berdasarkan ID_GUNUNG
        $this->gunungModel->update($id, [
            'NAMA_GUNUNG'   => $this->request->getPost('NAMA_GUNUNG'),
            'LOKASI'        => $this->request->getPost('LOKASI'),
            'KAPASITAS_MAX' => $this->request->getPost('KAPASITAS_MAX'),
            'CUACA'         => $this->request->getPost('CUACA'),
            'HARGA_TIKET'   => $this->request->getPost('HARGA_TIKET'),
            'KATEGORI'      => $this->request->getPost('KATEGORI')
        ]);

        return redirect()->to(base_url('admin/gunung'))->with('success', 'Data destinasi sukses diperbarui!');
    }

    // 6. PROSES HAPUS DATA
    public function hapus($id)
    {
        $gunung = $this->gunungModel->find($id);
        
        if ($gunung) {
            $this->gunungModel->delete($id);
            return redirect()->to(base_url('admin/gunung'))->with('success', 'Destinasi ' . $gunung['NAMA_GUNUNG'] . ' telah dihapus.');
        }

        return redirect()->to(base_url('admin/gunung'))->with('error', 'Gagal menghapus, data tidak ditemukan.');
    }
}