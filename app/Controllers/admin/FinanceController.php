<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionsModel;
use CodeIgniter\HTTP\ResponseInterface;

class FinanceController extends BaseController
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionsModel();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');
        $periode = $this->request->getGet('periode') ?: 'bulanan';

        $validStatuses = ['Lunas', 'Sudah Bayar', 'Berhasil'];
        $totals = $this->transactionModel
            ->select('SUM(total_harga) AS total_pendapatan, COUNT(*) AS transaksi_berhasil')
            ->whereIn('status_pembayaran', $validStatuses)
            ->where('tgl_mendaki >=', $startDate)
            ->where('tgl_mendaki <=', $endDate)
            ->get()
            ->getRowArray();

        $totalRevenue = (int) ($totals['total_pendapatan'] ?? 0);
        $partnerShare = (int) round($totalRevenue * 0.7);
        $basecampShare = $totalRevenue - $partnerShare;

        $reportQuery = $this->transactionModel
            ->select($periode === 'bulanan'
                ? "DATE_FORMAT(tgl_mendaki, '%Y-%m') AS periode, SUM(total_harga) AS total_pendapatan"
                : "DATE(tgl_mendaki) AS tanggal, SUM(total_harga) AS total_pendapatan")
            ->whereIn('status_pembayaran', $validStatuses)
            ->where('tgl_mendaki >=', $startDate)
            ->where('tgl_mendaki <=', $endDate)
            ->groupBy($periode === 'bulanan' ? 'periode' : 'tanggal')
            ->orderBy($periode === 'bulanan' ? 'periode' : 'tanggal', 'ASC');

        $reportRows = $reportQuery->get()->getResultArray();
        $chartLabels = array_map(fn ($row) => $row[$periode === 'bulanan' ? 'periode' : 'tanggal'], $reportRows);
        $chartValues = array_map(fn ($row) => (int) $row['total_pendapatan'], $reportRows);

        return view('admin/finance_index', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'periode' => $periode,
            'total_transaksi' => (int) ($totals['transaksi_berhasil'] ?? 0),
            'total_pendapatan' => $totalRevenue,
            'partner_share' => $partnerShare,
            'basecamp_share' => $basecampShare,
            'report_rows' => $reportRows,
            'chart_labels' => $chartLabels,
            'chart_values' => $chartValues,
        ]);
    }

    public function exportReport()
    {
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-d');
        $periode = $this->request->getGet('periode') ?: 'bulanan';
        $format = $this->request->getGet('format') === 'excel' ? 'excel' : 'csv';
        $validStatuses = ['Lunas', 'Sudah Bayar', 'Berhasil'];

        $query = $this->transactionModel
            ->select($periode === 'bulanan'
                ? "DATE_FORMAT(tgl_mendaki, '%Y-%m') AS periode, SUM(total_harga) AS total_pendapatan"
                : "DATE(tgl_mendaki) AS tanggal, SUM(total_harga) AS total_pendapatan")
            ->whereIn('status_pembayaran', $validStatuses)
            ->where('tgl_mendaki >=', $startDate)
            ->where('tgl_mendaki <=', $endDate)
            ->groupBy($periode === 'bulanan' ? 'periode' : 'tanggal')
            ->orderBy($periode === 'bulanan' ? 'periode' : 'tanggal', 'ASC');

        $results = $query->get()->getResultArray();
        $csv = $this->buildCsv($results, $periode, $startDate, $endDate);

        $extension = $format === 'excel' ? 'xls' : 'csv';
        $contentType = $format === 'excel' ? 'application/vnd.ms-excel' : 'text/csv';
        $filename = sprintf('finance-report-%s-%s.%s', date('YmdHis'), $periode, $extension);

        return $this->response
            ->setHeader('Content-Type', $contentType)
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    private function buildCsv(array $rows, string $periode, string $startDate, string $endDate): string
    {
        $buffer = fopen('php://temp', 'r+');

        if ($periode === 'bulanan') {
            fputcsv($buffer, ['Periode', 'Total Pendapatan', 'Hak Mitra (70%)', 'Kas Basecamp (30%)']);
            foreach ($rows as $row) {
                $total = (int) $row['total_pendapatan'];
                fputcsv($buffer, [
                    $row['periode'],
                    $total,
                    (int) round($total * 0.7),
                    $total - (int) round($total * 0.7),
                ]);
            }
        } else {
            fputcsv($buffer, ['Tanggal', 'Total Pendapatan', 'Hak Mitra (70%)', 'Kas Basecamp (30%)']);
            foreach ($rows as $row) {
                $total = (int) $row['total_pendapatan'];
                fputcsv($buffer, [
                    $row['tanggal'],
                    $total,
                    (int) round($total * 0.7),
                    $total - (int) round($total * 0.7),
                ]);
            }
        }

        rewind($buffer);
        $csv = stream_get_contents($buffer);
        fclose($buffer);

        return $csv;
    }
}
