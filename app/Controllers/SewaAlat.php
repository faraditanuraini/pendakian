<?php

namespace App\Controllers;

use App\Models\GunungModel;
use App\Models\EquipmentsModel;

class SewaAlat extends BaseController
{
    protected $MountainsModel;
    protected $EquipmentsModel;

    public function __construct()
    {
        $this->MountainsModel = new GunungModel();
        $this->EquipmentsModel = new EquipmentsModel();
    }

    /**
     * Tampilan Awal Form Pencarian Alat
     */
    public function index()
    {
        $data['daftar_gunung'] = $this->MountainsModel->findAll();
        return view('sewa_alat', $data);
    }

    /**
     * Memproses filter pencarian, menyimpannya ke session,
     * dan menampilkan halaman daftar barang yang tersedia.
     */
    public function cari()
    {
        // Tangkap input POST
        $idGunung    = $this->request->getPost('id_gunung');
        $tanggalSewa = $this->request->getPost('tanggal_sewa');
        $posMasuk    = $this->request->getPost('pos_masuk');
        $posKeluar   = $this->request->getPost('pos_keluar');

        // Validasi input
        if (empty($idGunung) || empty($tanggalSewa) || empty($posMasuk) || empty($posKeluar)) {
            return redirect()->back()->withInput()->with('error', 'Silakan tentukan Gunung, Tanggal, dan Pos Perizinan sebelum mencari!');
        }

        // Simpan filter pencarian ke dalam Session
        $filterData = [
            'id_gunung'    => $idGunung,
            'tanggal_sewa' => $tanggalSewa,
            'pos_masuk'    => $posMasuk,
            'pos_keluar'   => $posKeluar
        ];
        session()->set('sewa_filter', $filterData);

        // Ambil detail gunung yang dipilih
        $gunung = $this->MountainsModel->find($idGunung);
        if (!$gunung) {
            return redirect()->back()->withInput()->with('error', 'Data gunung yang Anda pilih tidak valid.');
        }

        // Ambil seluruh daftar alat camping riil dari database
        $daftarAlat = $this->EquipmentsModel->findAll();

        // Siapkan data untuk dikirim ke view
        $data = [
            'filter'      => $filterData,
            'gunung'      => $gunung,
            'daftar_alat' => $daftarAlat
        ];

        return view('daftar_alat', $data);
    }

    /**
     * Memproses transaksi penyewaan, menyimpan data ke database (Belum Bayar),
     * dan memanggil Midtrans Snap API untuk mengembalikan Snap Token.
     */
    public function prosesBayar()
    {
        // 1. Proteksi API
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
        }

        $namaPenyewa = $this->request->getPost('nama_penyewa');
        $cartItemsJson = $this->request->getPost('cart_items');

        if (empty($namaPenyewa) || empty($cartItemsJson)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap!']);
        }

        $cartItems = json_decode($cartItemsJson, true);
        $filter = session()->get('sewa_filter');

        // 2. Validasi Stok & Hitung Harga
        $totalHarga = 0;
        foreach ($cartItems as $item) {
            $alat = $this->EquipmentsModel->find($item['id']);
            if (!$alat || $alat['stok_tersedia'] < $item['qty']) {
                return $this->response->setJSON(['success' => false, 'message' => 'Stok alat "' . ($alat['nama_alat'] ?? 'Unknown') . '" tidak mencukupi.']);
            }
            $totalHarga += (int) $alat['harga_sewa'] * (int) $item['qty'];
        }

        // 3. Ambil ID_USER dari session fallback ke 1
        $idUser = session()->get('id_user') ?? session()->get('ID_USER') ?? session()->get('id');
        if (empty($idUser)) {
            $idUser = 1;
        }

        // 4. Persiapan Database
        $db = \Config\Database::connect();
        $db->transBegin();

        $idTransaksi = rand(100, 999) . substr(time(), -6);
        $sesiText = 'Sewa - Masuk: ' . substr($filter['pos_masuk'] ?? '', 0, 12) . ' Keluar: ' . substr($filter['pos_keluar'] ?? '', 0, 12);

        $dataTransaksi = [
            'ID_TRANSAKSI'     => $idTransaksi,
            'ID_USER'          => $idUser,
            'ID_GUNUNG'        => $filter['id_gunung'],
            'TGL_BOOKING'      => date('Y-m-d'),
            'TGL_MENDAKI'      => $filter['tanggal_sewa'],
            'SESI'             => $sesiText,
            'TOT_BAYAR'        => (int) $totalHarga,
            'STATUS_BAYAR'     => 'Belum Bayar',
            'BARCODE'          => 'SEWA-TEMP-' . time(),
            'STATUS_KEHADIRAN' => 'Pending'
        ];

        $builderTransaksi = $db->table('transaksi');
        $builderTransaksi->insert($dataTransaksi);

        $builderRentals = $db->table('rentals');
        foreach ($cartItems as $item) {
            $dataRental = [
                'transaction_id' => $idTransaksi,
                'equipment_id'   => $item['id'],
                'jumlah'         => (int) $item['qty'],
                'tgl_pinjam'     => date('Y-m-d'),
                'status'         => 'Dipinjam',
                'created_at'     => date('Y-m-d H:i:s')
            ];
            $builderRentals->insert($dataRental);

            $sisaStok = $this->EquipmentsModel->find($item['id'])['stok_tersedia'] - $item['qty'];
            $this->EquipmentsModel->update($item['id'], ['stok_tersedia' => $sisaStok]);
        }

        if ($db->transStatus() === false) {
            $dbError = $db->error();
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal simpan ke database.',
                'db_error' => $dbError
            ]);
        }

        $db->transCommit();

        // 6. Siapkan email customer valid untuk Midtrans
        $customerEmail = session()->get('email');
        if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $user = $db->table('user')->where('ID_USER', $idUser)->get()->getRowArray();
            $customerEmail = $user['EMAIL'] ?? $customerEmail;
        }
        if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $customerEmail = 'user@pendakian.com';
        }

        $result = $this->getMidtransSnapToken($idTransaksi, $totalHarga, [
            'nama'  => $namaPenyewa,
            'email' => $customerEmail,
            'phone' => session()->get('no_wa') ?? '08123456789'
        ]);

        return $this->response->setJSON($result);
    }

    /**
     * Merender halaman sukses transaksi sewa alat pendakian dengan QR code resmi.
     */
    public function sukses($idTransaksi)
{
    if (!session()->get('isLoggedIn')) {
        return redirect()->to(base_url('login'))->with('error', 'Silakan login.');
    }

    $db = \Config\Database::connect();
    
    // 1. Ambil data transaksi (Pastikan ID_TRANSAKSI sesuai case di DB)
    $transaksi = $db->table('transaksi')->where('ID_TRANSAKSI', $idTransaksi)->get()->getRowArray();
    if (!$transaksi) {
        return redirect()->to(base_url('sewa-alat'))->with('error', 'Transaksi tidak ditemukan.');
    }

    // 2. Update status jika masih temp
    if (str_starts_with($transaksi['BARCODE'] ?? '', 'SEWA-TEMP-')) {
        $officialBarcode = 'SEWA-' . date('Ymd') . '-' . rand(1000, 9999);
        $db->table('transaksi')->where('ID_TRANSAKSI', $idTransaksi)->update([
            'STATUS_BAYAR' => 'Sudah Bayar',
            'BARCODE'      => $officialBarcode
        ]);
        $transaksi['BARCODE'] = $officialBarcode; // Update data lokal
    }

    // 3. Ambil data gunung
    $gunung = $this->MountainsModel->find($transaksi['ID_GUNUNG']);

    // 4. Perbaikan Query: Mengambil data dari 'rentals' BUKAN 'detail_layanan'
    // Sesuaikan join berdasarkan nama kolom yang benar di tabel rentals dan equipments
    $rentalItems = $db->table('rentals')
                      ->select('rentals.*, equipments.nama_alat') 
                      ->join('equipments', 'equipments.id = rentals.equipment_id')
                      ->where('rentals.transaction_id', $idTransaksi)
                      ->get()
                      ->getResultArray();

    // 5. Data untuk View
    $data = [
        'transaksi'    => $transaksi,
        'gunung'       => $gunung,
        'rental_items' => $rental_items = $rentalItems, // Kirim ke view
        'nama_penyewa' => session()->get('nama_penyewa_' . $idTransaksi) ?? 'Penyewa',
        'ticket_code'  => $transaksi['BARCODE']
    ];

    return view('sewa_sukses', $data);
}

    /**
     * Memanggil Midtrans Sandbox API
     */
    private function getMidtransSnapToken($orderId, $grossAmount, $customerDetails)
    {
        $serverKey = env('midtrans.serverKey') ?? 'Mid-server-HWtGtaOAvln6P4YgtFUwt3iN';
        $url = 'https://app.sandbox.midtrans.com/snap/v1/transactions';
        
        $payload = [
            'transaction_details' => [
                'order_id'     => (string) $orderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'credit_card' => [
                'secure' => true
            ],
            'customer_details' => [
                'first_name' => $customerDetails['nama'],
                'email'      => $customerDetails['email'],
                'phone'      => $customerDetails['phone']
            ]
        ];
        
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
                ],
                'json' => $payload,
                'verify' => false,
                'http_errors' => false
            ]);
            
            $statusCode = $response->getStatusCode();
            $bodyText = $response->getBody();
            $body = json_decode($bodyText, true);

            if ($statusCode === 201 || $statusCode === 200) {
                if (isset($body['token'])) {
                    return [
                        'success' => true,
                        'token'   => $body['token']
                    ];
                }
            }

            return [
                'success' => false,
                'message' => "HTTP $statusCode - " . ($bodyText ?: 'Respon Kosong')
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception cURL: ' . $e->getMessage()
            ];
        }
    }

    public function daftarAlat() 
    {
        // Karena di __construct sudah ada $this->EquipmentsModel, kita langsung pakai
        $data['daftar_alat'] = $this->EquipmentsModel->findAll(); 
        
        return view('daftar_alat', $data);
    }
}
