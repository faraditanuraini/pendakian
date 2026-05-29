<?php

namespace App\Controllers;

use App\Models\GunungModel;

class Tiket extends BaseController
{
    protected $gunungModel;

    public function __construct()
    {
        $this->gunungModel = new GunungModel();
    }

    public function index()
    {
        $data['daftar_gunung'] = $this->gunungModel->findAll();
        return view('tiket', $data);
    }

    public function proses_tahap1()
    {
        $idGunung = $this->request->getPost('id_gunung');
        
        // Proteksi Awal: Pengguna harus login terlebih dahulu
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login?redirect=' . urlencode(base_url('gunung/detail/' . $idGunung))))
                             ->with('error', 'Silakan login terlebih dahulu untuk memesan tiket pendakian.');
        }

        $posMasuk = $this->request->getPost('pos_masuk');
        $tanggalMasuk = $this->request->getPost('tanggal_masuk');
        $tanggalKeluar = $this->request->getPost('tanggal_keluar');
        $jumlahPemesan = $this->request->getPost('jumlah_pemesan');

        if (empty($idGunung) || empty($posMasuk) || empty($tanggalMasuk) || empty($tanggalKeluar) || empty($jumlahPemesan)) {
            return redirect()->back()->withInput()->with('error', 'Semua kolom detail pemesanan awal wajib diisi!');
        }

        // Simpan sementara detail pemesanan Tahap 1 ke Session
        session()->set('booking_tahap1', [
            'id_gunung'      => $idGunung,
            'pos_masuk'      => $posMasuk,
            'tanggal_masuk'  => $tanggalMasuk,
            'tanggal_keluar' => $tanggalKeluar,
            'jumlah_pemesan' => (int) $jumlahPemesan
        ]);

        return redirect()->to(base_url('tiket/biodata'));
    }

    public function biodata()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $tahap1 = session()->get('booking_tahap1');
        if (!$tahap1) {
            return redirect()->to(base_url('tiket'))->with('error', 'Silakan isi detail pemesanan awal terlebih dahulu.');
        }

        $gunung = $this->gunungModel->find($tahap1['id_gunung']);
        if (!$gunung) {
            return redirect()->to(base_url('tiket'))->with('error', 'Data gunung tidak ditemukan.');
        }

        $data = [
            'tahap1'      => $tahap1,
            'gunung'      => $gunung,
            'total_bayar' => $tahap1['jumlah_pemesan'] * $gunung['HARGA_TIKET'],
            'snapToken'   => session()->getFlashdata('snapToken'),
            'idTransaksi' => session()->getFlashdata('idTransaksi')
        ];

        return view('form_biodata', $data);
    }

    public function proses_bayar()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $namaLengkap = $this->request->getPost('nama_lengkap');
        $alamat      = $this->request->getPost('alamat');
        $noTelp      = $this->request->getPost('no_telp');
        $noDarurat   = $this->request->getPost('no_darurat');

        if (empty($namaLengkap) || empty($alamat) || empty($noTelp) || empty($noDarurat)) {
            return redirect()->back()->withInput()->with('error', 'Semua kolom biodata pendaki wajib diisi!');
        }

        $tahap1 = session()->get('booking_tahap1');
        if (!$tahap1) {
            return redirect()->to(base_url('tiket'))->with('error', 'Detail pemesanan kedaluwarsa, silakan ulangi kembali.');
        }

        $gunung = $this->gunungModel->find($tahap1['id_gunung']);
        $totalBayar = $tahap1['jumlah_pemesan'] * $gunung['HARGA_TIKET'];

        // 1. Generate ID_TRANSAKSI Unik
        $idTransaksi = rand(100, 999) . substr(time(), -6);

        // 2. Simpan Transaksi Baru dengan Status 'Belum Bayar'
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi');

        $sesiVal = 'Masuk: ' . substr($tahap1['pos_masuk'], 0, 40);

        $dataTransaksi = [
            'ID_TRANSAKSI' => $idTransaksi,
            'ID_USER'      => session()->get('id_user'),
            'ID_GUNUNG'    => $tahap1['id_gunung'],
            'TGL_BOOKING'  => date('Y-m-d H:i:s'),
            'TGL_MENDAKI'  => $tahap1['tanggal_masuk'],
            'SESI'         => $sesiVal,
            'TOT_BAYAR'    => (int) $totalBayar, // SENSITIF MIDTRANS: Pastikan integer murni
            'STATUS_BAYAR' => 'Belum Bayar',
            'BARCODE'      => 'TRX-TEMP-' . time() // Temporary barcode
        ];

        if ($builder->insert($dataTransaksi)) {
            // Simpan detail biodata ke session untuk nanti di-encode ke Barcode saat Sukses
            session()->set('booking_biodata', [
                'nama_lengkap' => $namaLengkap,
                'alamat'       => $alamat,
                'no_telp'      => $noTelp,
                'no_darurat'   => $noDarurat
            ]);

            // SENSITIF MIDTRANS: Ambil email dari session. Jika formatnya tidak valid (misal 'fara' tanpa @), 
            // kita lakukan auto-fallback menjadi format email yang valid ('fara@example.com') untuk menghindari HTTP 400 Bad Request!
            $customerEmail = session()->get('email') ?? 'email@example.com';
            if (strpos($customerEmail, '@') === false) {
                $customerEmail = $customerEmail . '@example.com';
            }

            // 3. Panggil API Midtrans Sandbox untuk mendapatkan Snap Token
            $customerDetails = [
                'nama'  => $namaLengkap,
                'email' => $customerEmail,
                'phone' => $noTelp
            ];
            
            // Panggil API Midtrans Snap dengan feedback error detail
            $result = $this->getMidtransSnapToken($idTransaksi, $totalBayar, $customerDetails);

            if ($result['success']) {
                // Simpan token ke flashdata dan redirect ke halaman biodata agar pop-up meluncur
                session()->setFlashdata('snapToken', $result['token']);
                session()->setFlashdata('idTransaksi', $idTransaksi);
                return redirect()->to(base_url('tiket/biodata'));
            } else {
                // SENSITIF MIDTRANS: Berikan error asli yang detail agar mudah didebug
                return redirect()->back()->withInput()->with('error', 'Gagal terhubung ke gerbang pembayaran Midtrans. Detail: ' . $result['message']);
            }
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memproses pembuatan pesanan.');
    }

    public function success($idTransaksi)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('transaksi');

        // 1. Cek keberadaan transaksi
        $transaksi = $builder->where('ID_TRANSAKSI', $idTransaksi)->get()->getRowArray();
        if (!$transaksi) {
            return redirect()->to(base_url('tiket'))->with('error', 'Transaksi tidak ditemukan.');
        }

        // 2. Generate kode tiket resmi unik jika belum ada
        $officialTicketCode = $transaksi['BARCODE'];
        if (str_starts_with($officialTicketCode, 'TRX-TEMP-')) {
            $officialTicketCode = 'TRX-' . date('Ymd') . '-' . rand(1000, 9999);
            
            // Update database status menjadi 'Sudah Bayar' dan simpan kode barcode resmi
            $builder->where('ID_TRANSAKSI', $idTransaksi)->update([
                'STATUS_BAYAR' => 'Sudah Bayar',
                'BARCODE'      => $officialTicketCode
            ]);
        }

        $gunung = $this->gunungModel->find($transaksi['ID_GUNUNG']);
        $biodata = session()->get('booking_biodata') ?? [
            'nama_lengkap' => session()->get('username') ?? 'Pendaki',
            'alamat'       => 'Tidak Diketahui',
            'no_telp'      => 'Tidak Diketahui',
            'no_darurat'   => 'Tidak Diketahui'
        ];
        
        $tahap1 = session()->get('booking_tahap1') ?? [
            'pos_masuk'       => 'Pos Utama',
            'tanggal_masuk'   => $transaksi['TGL_MENDAKI'],
            'tanggal_keluar'  => date('Y-m-d', strtotime($transaksi['TGL_MENDAKI'] . ' +2 days')),
            'jumlah_pemesan'  => 1
        ];

        // 3. Gabungkan detail biodata pendaki untuk di-encode ke QR Code/Barcode
        $barcodeDataText = "=== TIKET PENDAKIAN RESMI ===\n"
                         . "Kode Tiket : " . $officialTicketCode . "\n"
                         . "Nama       : " . $biodata['nama_lengkap'] . "\n"
                         . "Gunung     : " . ($gunung['NAMA_GUNUNG'] ?? 'Gunung') . "\n"
                         . "Pos Masuk  : " . $tahap1['pos_masuk'] . "\n"
                         . "Tanggal    : " . $tahap1['tanggal_masuk'] . " s/d " . $tahap1['tanggal_keluar'] . "\n"
                         . "Jumlah     : " . $tahap1['jumlah_pemesan'] . " Orang\n"
                         . "No. Telp   : " . $biodata['no_telp'] . "\n"
                         . "Darurat    : " . $biodata['no_darurat'] . "\n"
                         . "Alamat     : " . $biodata['alamat'] . "\n"
                         . "=============================";

        $data = [
            'transaksi'    => $transaksi,
            'gunung'       => $gunung,
            'biodata'      => $biodata,
            'tahap1'       => $tahap1,
            'ticket_code'  => $officialTicketCode,
            'barcode_data' => $barcodeDataText
        ];

        return view('tiket_sukses', $data);
    }

    private function getMidtransSnapToken($orderId, $grossAmount, $customerDetails)
    {
        // Hubungkan ke file .env secara dinamis dengan fallback yang aman
        $serverKey = env('midtrans.serverKey') ?? 'Mid-server-HWtGtaOAvln6P4YgtFUwt3iN';
        $url = 'https://app.sandbox.midtrans.com/snap/v1/transactions';
        
        $payload = [
            'transaction_details' => [
                'order_id'     => (string) $orderId,
                'gross_amount' => (int) $grossAmount, // SENSITIF MIDTRANS: Pastikan integer murni tanpa pemformatan string
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
                'http_errors' => false // SENSITIF MIDTRANS: Cegah cURL melempar Exception pada HTTP 400 agar kita bisa membaca body error asli!
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

            // Jika statusnya bukan 200/201, kembalikan respon body asli dari Midtrans untuk memudahkan debugging
            return [
                'success' => false,
                'message' => "HTTP $statusCode - Respon: " . ($bodyText ?: 'Kosong')
            ];

        } catch (\Exception $e) {
            // Tangkap kesalahan koneksi cURL fisik
            return [
                'success' => false,
                'message' => 'Koneksi cURL Gagal: ' . $e->getMessage()
            ];
        }
    }
}
