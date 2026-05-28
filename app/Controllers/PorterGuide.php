<?php

namespace App\Controllers;

use App\Models\GunungModel;

class PorterGuide extends BaseController
{
    protected $gunungModel;

    public function __construct()
    {
        $this->gunungModel = new GunungModel();
    }

    public function index($id = null)
    {
        $data['daftar_gunung'] = $this->gunungModel->where('KATEGORI !=', 'bukit')->findAll();

        if ($id) {
            $gunung = $this->gunungModel->find($id);
            if ($gunung) {
                $data['gunung'] = $gunung;
            }
        }

        return view('porter_guide', $data);
    }

    public function proses_bayar()
    {
        // Proteksi API: Pastikan user sudah login
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk melakukan transaksi.'
            ]);
        }

        // Tangkap input POST
        $idGunung      = $this->request->getPost('id_gunung');
        $posMasuk      = $this->request->getPost('pos_masuk');
        $posKeluar     = $this->request->getPost('pos_keluar');
        $tipeLayanan   = $this->request->getPost('tipe_layanan');
        $tanggalMasuk  = $this->request->getPost('tanggal_masuk');
        $tanggalKeluar = $this->request->getPost('tanggal_keluar');
        $jumlahPesanan = $this->request->getPost('jumlah_pesanan');
        $namaPemesan   = $this->request->getPost('nama_pemesan');

        if (empty($idGunung) || empty($posMasuk) || empty($posKeluar) || empty($tipeLayanan) || empty($tanggalMasuk) || empty($tanggalKeluar) || empty($jumlahPesanan) || empty($namaPemesan)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Semua kolom detail pemesanan wajib diisi!'
            ]);
        }

        // Hitung total bayar berdasarkan hari (Rp 8.000 per hari per pesanan)
        $date1 = strtotime($tanggalMasuk);
        $date2 = strtotime($tanggalKeluar);
        $daysDiff = ceil(($date2 - $date1) / (60 * 60 * 24)) + 1;
        if ($daysDiff <= 0) $daysDiff = 1;
        
        $pricePerDay = 8000;
        $totalBayar = (int) $jumlahPesanan * $daysDiff * $pricePerDay;

        // Generate ID_PESANAN Unik
        $idPesanan = 'PRT-' . rand(100, 999) . substr(time(), -6);

        // Simpan pesanan porter dengan status 'Belum Bayar'
        $db = \Config\Database::connect();
        $builder = $db->table('pesanan_porter');

        $dataPesanan = [
            'ID_PESANAN'   => $idPesanan,
            'ID_USER'      => session()->get('id_user'),
            'ID_GUNUNG'    => $idGunung,
            'NAMA_PEMESAN' => $namaPemesan,
            'POS_MASUK'    => $posMasuk,
            'POS_KELUAR'   => $posKeluar,
            'TGL_MASUK'    => $tanggalMasuk,
            'TGL_KELUAR'   => $tanggalKeluar,
            'TIPE_LAYANAN' => $tipeLayanan,
            'JML_PESANAN'  => (int) $jumlahPesanan,
            'TOT_BAYAR'    => (int) $totalBayar, // SENSITIF MIDTRANS: Pastikan integer murni
            'STATUS_BAYAR' => 'Belum Bayar',
            'BARCODE'      => 'PRT-TEMP-' . time(), // temporary barcode
            'TGL_BOOKING'  => date('Y-m-d H:i:s')
        ];

        if ($builder->insert($dataPesanan)) {
            // Ambil email dari session & tambahkan format fallback aman jika tidak valid (misal 'fara')
            $customerEmail = session()->get('email') ?? 'email@example.com';
            if (strpos($customerEmail, '@') === false) {
                $customerEmail = $customerEmail . '@example.com';
            }

            // Panggil API Midtrans Sandbox
            $customerDetails = [
                'nama'  => $namaPemesan,
                'email' => $customerEmail,
                'phone' => session()->get('no_wa') ?? '08123456789'
            ];

            $result = $this->getMidtransSnapToken($idPesanan, $totalBayar, $customerDetails);

            if ($result['success']) {
                return $this->response->setJSON([
                    'success'    => true,
                    'snap_token' => $result['token'],
                    'id_pesanan' => $idPesanan
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal terhubung ke gerbang pembayaran Midtrans. Detail: ' . $result['message']
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menyimpan pesanan porter ke database.'
        ]);
    }

    public function success($idPesanan)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('pesanan_porter');

        $pesanan = $builder->where('ID_PESANAN', $idPesanan)->get()->getRowArray();
        if (!$pesanan) {
            return redirect()->to(base_url('porter-guide'))->with('error', 'Pesanan porter tidak ditemukan.');
        }

        // Update status menjadi Sudah Bayar dan simpan barcode resmi
        $officialCode = $pesanan['BARCODE'];
        if (str_starts_with($officialCode, 'PRT-TEMP-')) {
            $officialCode = 'PRT-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $builder->where('ID_PESANAN', $idPesanan)->update([
                'STATUS_BAYAR' => 'Sudah Bayar',
                'BARCODE'      => $officialCode
            ]);
        }

        $gunung = $this->gunungModel->find($pesanan['ID_GUNUNG']);

        // Data teks terformat untuk di-encode ke QR Code/Barcode
        $barcodeText = "=== DETAIL LAYANAN PORTER & GUIDE ===\n"
                     . "Kode Pesanan : " . $officialCode . "\n"
                     . "Nama Pemesan : " . $pesanan['NAMA_PEMESAN'] . "\n"
                     . "Gunung       : " . ($gunung['NAMA_GUNUNG'] ?? 'Gunung') . "\n"
                     . "Pos Masuk    : " . $pesanan['POS_MASUK'] . "\n"
                     . "Pos Keluar   : " . $pesanan['POS_KELUAR'] . "\n"
                     . "Layanan      : " . $pesanan['TIPE_LAYANAN'] . "\n"
                     . "Jumlah       : " . $pesanan['JML_PESANAN'] . " Pesanan\n"
                     . "Tanggal      : " . date('d M Y', strtotime($pesanan['TGL_MASUK'])) . " s/d " . date('d M Y', strtotime($pesanan['TGL_KELUAR'])) . "\n"
                     . "Status       : SUDAH BAYAR\n"
                     . "=====================================";

        $data = [
            'pesanan'      => $pesanan,
            'gunung'       => $gunung,
            'ticket_code'  => $officialCode,
            'barcode_data' => $barcodeText
        ];

        return view('porter_sukses', $data);
    }

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
