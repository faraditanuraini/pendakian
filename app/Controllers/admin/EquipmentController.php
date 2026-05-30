<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EquipmentsModel;
use App\Models\RentalsModel;
use App\Models\TransactionsModel;

class EquipmentController extends BaseController
{
    protected $equipmentModel;
    protected $rentalModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->equipmentModel = new EquipmentsModel();
        $this->rentalModel = new RentalsModel();
        $this->transactionModel = new TransactionsModel();
    }

    public function index()
{
        return view('admin/equipments/index', [
        'equipments' => $this->equipmentModel->orderBy('nama_alat', 'ASC')->findAll(),
        'activeRentals' => $this->rentalModel
            ->select('rentals.*, equipments.nama_alat, transaksi.TGL_MENDAKI as tgl_mendaki, transaksi.ID_TRANSAKSI as transaction_code, transaksi.ID_USER as id_user, user.NAMA_LENGKAP as nama_ketua')
            ->join('equipments', 'equipments.id = rentals.equipment_id')
            ->join('transaksi', 'transaksi.ID_TRANSAKSI = rentals.transaction_id', 'left')
            ->join('user', 'user.ID_USER = transaksi.ID_USER', 'left')
            ->where('rentals.status', 'Dipinjam')
            ->orderBy('rentals.tgl_kembali', 'ASC')
            ->findAll(),
    ]);
}

    public function create()
    {
        return view('admin/equipments/create');
    }

    public function store()
    {
        // 1. Ambil file gambar
        $file = $this->request->getFile('gambar');
        $namaGambar = 'default.jpg'; // Gambar default

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaGambar = $file->getRandomName();
            $file->move('uploads/equipments/', $namaGambar); // Pastikan folder ini ada!
        }

        $totalStok = (int) $this->request->getPost('total_stok');
        $stokTersedia = min($totalStok, (int) $this->request->getPost('stok_tersedia'));

        $this->equipmentModel->insert([
            'nama_alat'     => $this->request->getPost('nama_alat'),
            'total_stok'    => $totalStok,
            'stok_tersedia' => $stokTersedia,
            'harga_sewa'    => (int) $this->request->getPost('harga_sewa'), // Tambahkan ini
            'gambar'        => $namaGambar,                                 // Tambahkan ini
            'kondisi'       => $this->request->getPost('kondisi') ?? 'Baik',
        ]);

        return redirect()->to(base_url('admin/equipments'))->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return view('admin/equipments/edit', [
            'equipment' => $this->equipmentModel->find($id),
        ]);
    }

    public function update($id)
{
    //dd($this->request->getPost());
    $equipment = $this->equipmentModel->find($id);

    // 1. Validasi
    if (!$this->validate([
        'nama_alat' => 'required',
        'total_stok' => 'required|numeric',
        'harga_sewa' => 'required|numeric'
    ])) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }
    
    // 2. Handle Gambar
    $file = $this->request->getFile('gambar');
    $namaGambar = $equipment['gambar']; 

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $namaGambar = $file->getRandomName();
        $file->move(FCPATH . 'uploads/equipments/', $namaGambar);
        
        if ($equipment['gambar'] && file_exists(FCPATH . 'uploads/equipments/' . $equipment['gambar'])) {
            unlink(FCPATH . 'uploads/equipments/' . $equipment['gambar']);
        }
    }

    // 3. Siapkan Data (Ini kuncinya)
    $totalStok = (int) $this->request->getPost('total_stok');
    $data = [
        'nama_alat'     => $this->request->getPost('nama_alat'),
        'total_stok'    => $totalStok,
        'stok_tersedia' => min($totalStok, (int) $this->request->getPost('stok_tersedia')),
        'harga_sewa'    => (int) $this->request->getPost('harga_sewa'),
        'gambar'        => $namaGambar,
        'kondisi'       => $this->request->getPost('kondisi') ?? 'Baik',
    ];

    // 4. Lakukan Update Sekali Saja
    if ($this->equipmentModel->update($id, $data)) {
        return redirect()->to(base_url('admin/equipments'))->with('success', 'Data alat berhasil diperbarui.');
    } else {
        return redirect()->back()->with('error', 'Gagal memperbarui data ke database.');
    }
}

    public function delete($id)
    {
        $this->equipmentModel->delete($id);
        return redirect()->to(base_url('admin/equipments'))->with('success', 'Alat berhasil dihapus.');
    }

    public function completeRental($id = null)
    {
        if (! $this->request->is('post')) {
            return redirect()->to(base_url('admin/equipments'));
        }

        $rentalId = (int) $id;
        $condition = $this->request->getPost('kondisi') ?: 'Baik';

        $rental = $this->rentalModel->find($rentalId);
        if (! $rental || $rental['status'] !== 'Dipinjam') {
            return redirect()->to(base_url('admin/equipments'))->with('error', 'Sewa tidak ditemukan atau sudah selesai.');
        }

        $equipment = $this->equipmentModel->find($rental['equipment_id']);
        if (! $equipment) {
            return redirect()->to(base_url('admin/equipments'))->with('error', 'Data alat tidak ditemukan.');
        }

        $newStock = min(
            (int) $equipment['total_stok'], 
            (int) $equipment['stok_tersedia'] + (int) $rental['jumlah']
        );

        $this->equipmentModel->update($equipment['id'], [
            'stok_tersedia' => $newStock,
            'kondisi'       => $condition,
        ]);

        $this->rentalModel->update($rentalId, [
            'status'      => 'Kembali',
            'tgl_kembali' => date('Y-m-d'),
        ]);

        return redirect()->to(base_url('admin/equipments'))->with('success', 'Sewa berhasil diselesaikan dan stok alat diperbarui.');
    }
}
