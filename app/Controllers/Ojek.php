<?php

namespace App\Controllers;

use App\Models\GunungModel;

class Ojek extends BaseController
{
    protected $gunungModel;

    public function __construct()
    {
        $this->gunungModel = new GunungModel();
    }

    /**
     * Menampilkan daftar gunung dan form pesanan ojek
     */
    public function index($id = null)
    {
        $data = [
            'daftar_gunung' => $this->gunungModel->findAll()
        ];

        if ($id != null) {
            $data['gunung'] = $this->gunungModel->find($id);
        }

        return view('ojek', $data);
    }

    /**
     * Memproses pesanan ojek gunung, menghitung total bayar,
     * menyimpan ke tabel pesanan_ojek, dan meminta Snap Token dari Midtrans Sandbox.
     */
    public function prosesPesan()
    {
        // Proteksi API: Pastikan user sudah login
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk memesan ojek.'
            ]);
        }

        // Tangkap parameter POST
        $idGunung         = $this->request->getPost('id_gunung');
        $namaLeader       = $this->request->getPost('nama_leader');
        $titikJemput      = $this->request->getPost('titik_jemput');
        $posTujuan        = $this->request->getPost('pos_tujuan');
        $tglKeberangkatan = $this->request->getPost('tgl_keberangkatan');
        $jmlPenumpang     = $this->request->getPost('jml_penumpang');

        // Validasi input
        if (empty($idGunung) || empty($namaLeader) || empty($titikJemput) || empty($posTujuan) || empty($tglKeberangkatan) || empty($jmlPenumpang)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Semua kolom detail pemesanan ojek wajib diisi!'
            ]);
        }

        // Hitung total harga: Rp 35.000 flat per motor/penumpang
        $totalBayar = (int) $jmlPenumpang * 35000;

        // Generate ID_PESANAN Unik
        $idPesanan = 'OJK-' . rand(100, 999) . substr(time(), -6);

        // Memasukkan data transaksi ke tabel pesanan_ojek (Belum Bayar)
        $db = \Config\Database::connect();
        $builder = $db->table('pesanan_ojek');

        $dataPesanan = [
            'ID_PESANAN'            => $idPesanan,
            'ID_USER'               => session()->get('id_user'),
            'ID_GUNUNG'             => $idGunung,
            'NAMA_PENANGGUNG_JAWAB' => $namaLeader,
            'TITIK_JEMPUT'          => $titikJemput,
            'POS_TUJUAN'            => $posTujuan,
            'TGL_KEBERANGKATAN'     => $tglKeberangkatan,
            'JML_PENUMPANG'         => (int) $jmlPenumpang,
            'TOT_BAYAR'             => (int) $totalBayar, // SENSITIF MIDTRANS: Pastikan integer murni
            'STATUS_BAYAR'          => 'Belum Bayar',
            'BARCODE'               => 'OJK-TEMP-' . time(),
            'TGL_BOOKING'           => date('Y-m-d H:i:s')
        ];

        if ($builder->insert($dataPesanan)) {
            // Ambil email & no WA dari session untuk payload Midtrans
            $customerEmail = session()->get('email') ?? 'email@example.com';
            if (strpos($customerEmail, '@') === false) {
                $customerEmail = $customerEmail . '@example.com';
            }

            $customerDetails = [
                'nama'  => $namaLeader,
                'email' => $customerEmail,
                'phone' => session()->get('no_wa') ?? '08123456789'
            ];

            // Tembak API Midtrans Sandbox
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
            'message' => 'Gagal menyimpan pesanan ojek ke database.'
        ]);
    }

    /**
     * Merender halaman e-ticket/voucher sukses pesanan ojek dengan QR Code manifes
     */
    public function sukses($idPesanan)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('pesanan_ojek');

        // 1. Ambil data pesanan
        $pesanan = $builder->where('ID_PESANAN', $idPesanan)->get()->getRowArray();
        if (!$pesanan) {
            return redirect()->to(base_url('ojek'))->with('error', 'Pesanan ojek tidak ditemukan.');
        }

        // 2. Update status bayar & simpan barcode resmi jika masih temporary
        $officialCode = $pesanan['BARCODE'];
        if (str_starts_with($officialCode, 'OJK-TEMP-')) {
            $officialCode = 'OJK-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $builder->where('ID_PESANAN', $idPesanan)->update([
                'STATUS_BAYAR' => 'Sudah Bayar',
                'BARCODE'      => $officialCode
            ]);
            
            // Perbarui array local agar data yang dikirim ke view akurat
            $pesanan['STATUS_BAYAR'] = 'Sudah Bayar';
            $pesanan['BARCODE'] = $officialCode;
        }

        // 3. Ambil data gunung
        $gunung = $this->gunungModel->find($pesanan['ID_GUNUNG']);

        // 4. Buat rincian teks manifes ojek untuk di-encode ke Barcode
        $barcodeText = "=== MANIFES OJEK GUNUNG RESMI ===\n"
                     . "Kode Voucher: " . $officialCode . "\n"
                     . "Atas Nama   : " . $pesanan['NAMA_PENANGGUNG_JAWAB'] . "\n"
                     . "Total Ojek  : " . $pesanan['JML_PENUMPANG'] . " Motor / Penumpang\n"
                     . "Gunung      : " . ($gunung['NAMA_GUNUNG'] ?? 'Gunung') . "\n"
                     . "Rute        : " . $pesanan['TITIK_JEMPUT'] . " ke " . $pesanan['POS_TUJUAN'] . "\n"
                     . "Tanggal     : " . date('d M Y', strtotime($pesanan['TGL_KEBERANGKATAN'])) . "\n"
                     . "Status      : LUNAS / SUDAH BAYAR\n"
                     . "=================================";

        $data = [
            'pesanan'      => $pesanan,
            'gunung'       => $gunung,
            'ticket_code'  => $officialCode,
            'barcode_data' => $barcodeText
        ];

        return view('ojek_sukses', $data);
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