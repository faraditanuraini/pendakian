<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    protected function susunTeksQRCode($trx)
    {
        return "KODE: " . $trx['barcode'] . "\n" .
               "GUNUNG: " . $trx['nm_gunung'] . "\n" .
               "PENDAKI UTAMA: " . $trx['nm_lengkap'] . "\n" .
               "JALUR: " . $trx['sesi'] . "\n" .
               "TANGGAL: " . date('d M Y', strtotime($trx['tgl_mendaki'])) . " s/d " . date('d M Y', strtotime($trx['tgl_turun'])) . "\n" .
               "NO. TELEPON: " . $trx['no_wa'] . "\n" .
               "STATUS: SUDAH BAYAR";
    }

    protected function susunTeksSewaQRCode($trx, $items)
    {
        $textBarang = "";
        foreach ($items as $item) {
            $itemName = $item['NAMA_ALAT'] ?? $item['NAMA_LAYANAN'] ?? $item['nm_layanan'] ?? 'Barang';
            $itemQty = $item['JUMLAH_ITEM'] ?? $item['jumlah_detal'] ?? $item['qty'] ?? 1;
            $textBarang .= "- " . $itemName . " (" . $itemQty . "x)\n";
        }

        return "KODE SEWA: " . $trx['barcode'] . "\n" .
               "NAMA PENYEWA: " . $trx['nm_lengkap'] . "\n" .
               "GUNUNG: " . $trx['nm_gunung'] . "\n" .
               "TANGGAL SEWA: " . date('d M Y', strtotime($trx['tgl_mendaki'])) . "\n\n" .
               "RINCIAN BARANG:\n" . $textBarang .
               "TOTAL TAGIHAN: Rp " . number_format($trx['tot_bayar'], 0, ',', '.') . "\n" .
               "STATUS: SUDAH BAYAR";
    }
}
