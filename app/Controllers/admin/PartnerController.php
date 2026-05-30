<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PartnerAssignmentsModel;
use App\Models\PartnersModel;
use App\Models\TransactionsModel;

class PartnerController extends BaseController
{
    protected $partnerModel;
    protected $assignmentModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->partnerModel = new PartnersModel();
        $this->assignmentModel = new PartnerAssignmentsModel();
        $this->transactionModel = new TransactionsModel();
    }

   public function index()
{
    // 1. Ambil data transaksi dengan JOIN ke tabel 'gunung' 
    // agar kita bisa menampilkan nama gunung di dropdown
    $transactions = $this->transactionModel
        ->select('transaksi.*, gunung.NAMA_GUNUNG')
        ->join('gunung', 'gunung.ID_GUNUNG = transaksi.ID_GUNUNG', 'left')
        ->orderBy('transaksi.TGL_MENDAKI', 'DESC')
        ->findAll();

    // 2. Mapping untuk memastikan view tetap bisa membaca key 'id'
    $transactionsFormatted = array_map(function($trx) {
        $trx['id'] = $trx['ID_TRANSAKSI']; 
        return $trx;
    }, $transactions);

    return view('admin/partners/index', [
        'partners'     => $this->partnerModel->orderBy('status', 'ASC')->orderBy('tipe', 'ASC')->findAll(),
        'transactions' => $transactionsFormatted, // Data yang sudah memiliki NAMA_GUNUNG
        'assignments'  => $this->assignmentModel
            ->select('partner_assignments.*, partners.nama AS partner_name, partners.tipe AS partner_type, transaksi.ID_USER, transaksi.TGL_MENDAKI')
            ->join('partners', 'partners.id = partner_assignments.partner_id')
            ->join('transaksi', 'transaksi.ID_TRANSAKSI = partner_assignments.transaction_id')
            ->orderBy('partner_assignments.tgl_tugas', 'DESC')
            ->findAll(),
    ]);
}

    public function create()
    {
        return view('admin/partners/create');
    }

    public function store()
    {
        $this->partnerModel->insert([
            'nama' => $this->request->getPost('nama'),
            'tipe' => $this->request->getPost('tipe'),
            'kontak' => $this->request->getPost('kontak'),
            'status' => $this->request->getPost('status') ?? 'Tersedia',
        ]);

        return redirect()->to(base_url('admin/partners'))->with('success', 'Partner berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return view('admin/partners/edit', [
            'partner' => $this->partnerModel->find($id),
        ]);
    }

    public function update($id)
    {
        $this->partnerModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'tipe' => $this->request->getPost('tipe'),
            'kontak' => $this->request->getPost('kontak'),
            'status' => $this->request->getPost('status') ?? 'Tersedia',
        ]);

        return redirect()->to(base_url('admin/partners'))->with('success', 'Data partner diperbarui.');
    }

    public function delete($id)
    {
        $this->partnerModel->delete($id);
        return redirect()->to(base_url('admin/partners'))->with('success', 'Partner berhasil dihapus.');
    }

    public function assign()
    {
        if (! $this->request->is('post')) {
            return redirect()->to(base_url('admin/partners'));
        }

        $partnerId = (int) $this->request->getPost('partner_id');
        $transactionId = (int) $this->request->getPost('transaction_id');
        $tglTugas = $this->request->getPost('tgl_tugas') ?: date('Y-m-d');

        $partner = $this->partnerModel->find($partnerId);
        
        // Perbaikan: gunakan where ID_TRANSAKSI karena primary key sudah berubah
        $transaction = $this->transactionModel->where('ID_TRANSAKSI', $transactionId)->first();

        if (! $partner || ! $transaction) {
            return redirect()->to(base_url('admin/partners'))->with('error', 'Partner atau transaksi tidak ditemukan.');
        }

        // ... sisa fungsi assign sama ...
        
        $this->assignmentModel->insert([
            'transaction_id' => $transactionId,
            'partner_id'     => $partnerId,
            'tgl_tugas'      => $tglTugas,
        ]);

        $this->partnerModel->update($partnerId, ['status' => 'Bertugas']);

        return redirect()->to(base_url('admin/partners'))->with('success', 'Partner berhasil ditugaskan.');
    }
}
