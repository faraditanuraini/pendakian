<?php

namespace App\Controllers;

use App\Models\GunungModel;
use App\Models\PeralatanModel;

class SewaAlat extends BaseController
{
    protected $gunungModel;
    protected $peralatanModel;

    public function __construct()
    {
        $this->gunungModel = new GunungModel();
        $this->peralatanModel = new PeralatanModel();
    }

    /**
     * Tampilan Awal Form Pencarian Alat
     */
    public function index()
    {
        $data['daftar_gunung'] = $this->gunungModel->findAll();
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
        $gunung = $this->gunungModel->find($idGunung);
        if (!$gunung) {
            return redirect()->back()->withInput()->with('error', 'Data gunung yang Anda pilih tidak valid.');
        }

        // Ambil seluruh daftar alat camping riil dari database
        $daftarAlat = $this->peralatanModel->findAll();

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
        // Proteksi API: Pastikan user sudah login
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk melakukan transaksi.'
            ]);
        }

        // Tangkap data POST
        $namaPenyewa   = $this->request->getPost('nama_penyewa');
        $cartItemsJson = $this->request->getPost('cart_items');

        if (empty($namaPenyewa) || empty($cartItemsJson)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama penyewa dan rincian belanja wajib diisi!'
            ]);
        }

        $cartItems = json_decode($cartItemsJson, true);
        if (empty($cartItems)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Keranjang belanja Anda masih kosong!'
            ]);
        }

        // Ambil filter pencarian dari session
        $filter = session()->get('sewa_filter');
        if (empty($filter)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sesi pencarian sewa telah kedaluwarsa. Silakan ulangi filter pencarian Anda.'
            ]);
        }

        // Hitung total harga riil dari database (proteksi manipulasi harga di frontend)
        $totalHarga = 0;
        foreach ($cartItems as $item) {
            $alat = $this->peralatanModel->find($item['id']);
            if (!$alat) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Peralatan dengan ID ' . esc($item['id']) . ' tidak ditemukan.'
                ]);
            }
            // Proteksi stok
            if ($alat['STOK'] < $item['qty']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Stok peralatan "' . $alat['NAMA_ALAT'] . '" tidak mencukupi (Tersedia: ' . $alat['STOK'] . ' Pcs).'
                ]);
            }
            $totalHarga += (int) $alat['HARGA_SEWA'] * (int) $item['qty'];
        }

        // Generate ID_TRANSAKSI Unik numerik (CI4 PK kompatibel & Midtrans Snap kompatibel)
        $idTransaksi = rand(100, 999) . substr(time(), -6);

        // Memulai transaksi DB secara aman
        $db = \Config\Database::connect();
        $db->transBegin();

        $builderTransaksi = $db->table('transaksi');
        // SENSITIF DB: Kolom 'SESI' bertipe varchar(50) di database. Pastikan tidak melebihi 50 karakter agar query insert tidak ditolak!
        $sesiText = 'Sewa - Masuk: ' . substr($filter['pos_masuk'], 0, 12) . ' Keluar: ' . substr($filter['pos_keluar'], 0, 12);

        $dataTransaksi = [
            'ID_TRANSAKSI' => $idTransaksi,
            'ID_USER'      => session()->get('id_user'),
            'ID_GUNUNG'    => $filter['id_gunung'],
            'TGL_BOOKING'  => date('Y-m-d H:i:s'),
            'TGL_MENDAKI'  => $filter['tanggal_sewa'],
            'SESI'         => $sesiText,
            'TOT_BAYAR'    => (int) $totalHarga, // SENSITIF MIDTRANS: Pastikan integer murni
            'STATUS_BAYAR' => 'Belum Bayar',
            'BARCODE'      => 'SEWA-TEMP-' . time()
        ];

        $builderTransaksi->insert($dataTransaksi);

        // Simpan detail belanja ke tabel 'detail_layanan'
        $builderDetail = $db->table('detail_layanan');
        $builderLayanan = $db->table('layanan');
        $idx = 1;
        foreach ($cartItems as $item) {
            $alat = $this->peralatanModel->find($item['id']);
            
            // SENSITIF DB: Kolom 'ID_LAYANAN_' memiliki foreign key ke tabel 'layanan'.
            // Lakukan self-healing insert untuk memastikan ID_LAYANAN_ sudah terdaftar di tabel layanan terlebih dahulu!
            $layananExist = $builderLayanan->where('ID_LAYANAN_', $item['id'])->get()->getRowArray();
            if (!$layananExist) {
                $builderLayanan->insert([
                    'ID_LAYANAN_'  => $item['id'],
                    'NAMA_LAYANAN' => $alat['NAMA_ALAT'],
                    'KATEGORI'     => 'Peralatan',
                    'HARGA'        => (int) $alat['HARGA_SEWA'],
                    'STOK'         => (int) $alat['STOK']
                ]);
            }

            // SENSITIF DB: Kolom 'ID_DETAIL' bertipe varchar(255) PK di database dan TIDAK memiliki extra auto_increment!
            // Kita wajib men-generate ID_DETAIL unik secara manual di setiap baris perulangan.
            $idDetail = 'DTL-' . $idTransaksi . '-' . rand(100, 999) . $idx;

            $dataDetail = [
                'ID_DETAIL'      => $idDetail,
                'ID_LAYANAN_'    => $item['id'],
                'ID_TRANSAKSI'   => $idTransaksi,
                'JUMLAH_ITEM'    => (int) $item['qty'],
                'SUBTOTAL'       => (int) $alat['HARGA_SEWA'] * (int) $item['qty'],
                'DURASI'         => 1, // default durasi
                'STATUS_LAYANAN' => 'Sewa',
                'HARGA_SATUAN'   => (int) $alat['HARGA_SEWA']
            ];
            $builderDetail->insert($dataDetail);
            $idx++;
        }

        if ($db->transStatus() === false) {
            $error = $db->error();
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mendaftarkan transaksi sewa ke database. Detail Eror: ' . ($error['message'] ?? 'Unknown SQL Error') . ' (Code: ' . ($error['code'] ?? '0') . ')'
            ]);
        }

        $db->transCommit();

        // Simpan nama penyewa ke session untuk ditampilkan di halaman sukses
        session()->set('nama_penyewa_' . $idTransaksi, $namaPenyewa);

        // Ambil email & data contact dari session untuk payload Midtrans
        $customerEmail = session()->get('email') ?? 'email@example.com';
        if (strpos($customerEmail, '@') === false) {
            $customerEmail = $customerEmail . '@example.com';
        }

        $customerDetails = [
            'nama'  => $namaPenyewa,
            'email' => $customerEmail,
            'phone' => session()->get('no_wa') ?? '08123456789'
        ];

        // Tembak API Midtrans Sandbox
        $result = $this->getMidtransSnapToken($idTransaksi, $totalHarga, $customerDetails);

        if ($result['success']) {
            return $this->response->setJSON([
                'success'    => true,
                'snap_token' => $result['token'],
                'id_order'   => $idTransaksi
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal terhubung ke gerbang pembayaran Midtrans. Detail: ' . $result['message']
            ]);
        }
    }

    /**
     * Merender halaman sukses transaksi sewa alat pendakian dengan QR code resmi.
     */
    public function sukses($idTransaksi)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $builderTransaksi = $db->table('transaksi');

        // 1. Ambil data transaksi
        $transaksi = $builderTransaksi->where('ID_TRANSAKSI', $idTransaksi)->get()->getRowArray();
        if (!$transaksi) {
            return redirect()->to(base_url('sewa-alat'))->with('error', 'Transaksi sewa tidak ditemukan.');
        }

        // 2. Update status jadi Sudah Bayar & generate barcode resmi jika diperlukan
        $officialBarcode = $transaksi['BARCODE'];
        if (str_starts_with($officialBarcode, 'SEWA-TEMP-')) {
            $officialBarcode = 'SEWA-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $builderTransaksi->where('ID_TRANSAKSI', $idTransaksi)->update([
                'STATUS_BAYAR' => 'Sudah Bayar',
                'BARCODE'      => $officialBarcode
            ]);
        }

        // 3. Ambil data gunung
        $gunung = $this->gunungModel->find($transaksi['ID_GUNUNG']);

        // 4. Ambil rincian detail barang sewa dari detail_layanan
        $rentalItems = $db->table('detail_layanan')
                          ->select('detail_layanan.*, peralatan.NAMA_ALAT')
                          ->join('peralatan', 'peralatan.ID_PERALATAN = detail_layanan.ID_LAYANAN_')
                          ->where('ID_TRANSAKSI', $idTransaksi)
                          ->get()
                          ->getResultArray();

        $namaPenyewa = session()->get('nama_penyewa_' . $idTransaksi) ?? session()->get('username') ?? 'Penyewa';

        // 5. Gabungkan rincian sewa menjadi teks terformat untuk Barcode menggunakan helper tersentralisasi
        $trxData = [
            'barcode'     => $officialBarcode,
            'nm_lengkap'  => $namaPenyewa,
            'nm_gunung'   => $gunung['NAMA_GUNUNG'] ?? 'Gunung',
            'tgl_mendaki' => $transaksi['TGL_MENDAKI'],
            'tot_bayar'   => $transaksi['TOT_BAYAR']
        ];
        $barcodeText = $this->susunTeksSewaQRCode($trxData, $rentalItems);

        $data = [
            'transaksi'    => $transaksi,
            'gunung'       => $gunung,
            'rental_items' => $rentalItems,
            'nama_penyewa' => $namaPenyewa,
            'ticket_code'  => $officialBarcode,
            'barcode_data' => $barcodeText
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
}
