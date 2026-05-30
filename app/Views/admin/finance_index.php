<?php
$start_date = $start_date ?? date('Y-m-01');
$end_date = $end_date ?? date('Y-m-d');
$periode = $periode ?? 'bulanan';
$total_pendapatan = $total_pendapatan ?? 0;
$total_transaksi = $total_transaksi ?? 0;
$partner_share = $partner_share ?? 0;
$basecamp_share = $basecamp_share ?? 0;
$report_rows = $report_rows ?? [];
$chart_labels = $chart_labels ?? [];
$chart_values = $chart_values ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuangan - Admin Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#f4f7f6] text-slate-900 min-h-screen font-sans">
    <?= $this->include('admin/sidebar') ?>

    <div class="ml-64 min-h-screen">
        <header class="bg-white/90 backdrop-blur sticky top-0 z-10 border-b border-slate-200 px-8 py-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Keuangan</h1>
                <p class="text-sm text-slate-500 mt-1">Ringkasan pendapatan, pembagian hak mitra, dan ekspor laporan.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="<?= base_url('admin/finance/export?start_date=' . $start_date . '&end_date=' . $end_date . '&periode=' . $periode . '&format=csv') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800 transition">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </a>
                <a href="<?= base_url('admin/finance/export?start_date=' . $start_date . '&end_date=' . $end_date . '&periode=' . $periode . '&format=excel') ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-900 hover:bg-slate-50 transition">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </a>
            </div>
        </header>

        <main class="p-8 space-y-8">
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-700 shadow-sm"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-400">Total Pendapatan</p>
                    <p class="text-3xl font-black text-slate-900 mt-4">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></p>
                    <p class="text-sm text-slate-500 mt-2">Periode <?= esc($start_date) ?> sampai <?= esc($end_date) ?></p>
                </div>
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-400">Transaksi Valid</p>
                    <p class="text-3xl font-black text-slate-900 mt-4"><?= number_format($total_transaksi, 0, ',', '.') ?></p>
                    <p class="text-sm text-slate-500 mt-2">Total transaksi dibayar dan berhasil</p>
                </div>
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-400">Mitra 70%</p>
                    <p class="text-3xl font-black text-emerald-600 mt-4">Rp <?= number_format($partner_share, 0, ',', '.') ?></p>
                    <p class="text-sm text-slate-500 mt-2">Pendapatan hak mitra lokal</p>
                </div>
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-400">Kas Basecamp 30%</p>
                    <p class="text-3xl font-black text-slate-900 mt-4">Rp <?= number_format($basecamp_share, 0, ',', '.') ?></p>
                    <p class="text-sm text-slate-500 mt-2">Pendapatan basecamp</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Filter Laporan</h2>
                        <p class="text-sm text-slate-500 mt-1">Sesuaikan rentang tanggal dan periode laporan.</p>
                    </div>
                    <form method="get" action="<?= base_url('admin/finance') ?>" class="grid gap-3 sm:grid-cols-4">
                        <div>
                            <label class="text-xs font-black uppercase tracking-wider text-slate-400">Mulai</label>
                            <input type="date" name="start_date" value="<?= esc($start_date) ?>" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-black uppercase tracking-wider text-slate-400">Selesai</label>
                            <input type="date" name="end_date" value="<?= esc($end_date) ?>" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-black uppercase tracking-wider text-slate-400">Periode</label>
                            <select name="periode" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none">
                                <option value="harian" <?= $periode === 'harian' ? 'selected' : '' ?>>Harian</option>
                                <option value="bulanan" <?= $periode === 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full rounded-3xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-black text-slate-900">Grafik Pendapatan</h2>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-slate-500"><?= ucfirst($periode) ?></span>
                    </div>
                    <div class="relative min-h-[320px]">
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-black text-slate-900 mb-4">Proporsi Pendapatan</h2>
                    <div class="space-y-4">
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mitra Lokal</p>
                            <p class="text-3xl font-black text-emerald-600 mt-3">Rp <?= number_format($partner_share, 0, ',', '.') ?></p>
                            <div class="mt-4 h-3 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-full rounded-full bg-emerald-500" style="width:70%"></div>
                            </div>
                        </div>
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kas Basecamp</p>
                            <p class="text-3xl font-black text-slate-900 mt-3">Rp <?= number_format($basecamp_share, 0, ',', '.') ?></p>
                            <div class="mt-4 h-3 rounded-full bg-slate-200 overflow-hidden">
                                <div class="h-full rounded-full bg-slate-900" style="width:30%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-black text-slate-900">Detail Laporan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 text-xs uppercase tracking-widest text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-semibold"><?= $periode === 'bulanan' ? 'Periode' : 'Tanggal' ?></th>
                                <th class="px-6 py-4 font-semibold">Total Pendapatan</th>
                                <th class="px-6 py-4 font-semibold">Hak Mitra (70%)</th>
                                <th class="px-6 py-4 font-semibold">Kas Basecamp (30%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <?php if (!empty($report_rows)) : ?>
                                <?php foreach ($report_rows as $row) : ?>
                                    <?php $value = (int) ($row['total_pendapatan'] ?? $row['total'] ?? 0); ?>
                                    <tr>
                                        <td class="px-6 py-4 font-semibold text-slate-900"><?= esc($row[$periode === 'bulanan' ? 'periode' : 'tanggal']) ?></td>
                                        <td class="px-6 py-4">Rp <?= number_format($value, 0, ',', '.') ?></td>
                                        <td class="px-6 py-4">Rp <?= number_format((int) round($value * 0.7), 0, ',', '.') ?></td>
                                        <td class="px-6 py-4">Rp <?= number_format($value - (int) round($value * 0.7), 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400">Tidak ada data untuk rentang tanggal ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const labels = <?= json_encode($chart_labels) ?>;
        const values = <?= json_encode($chart_values) ?>;
        const ctx = document.getElementById('financeChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: values,
                    borderColor: '#0f766e',
                    backgroundColor: 'rgba(16, 185, 129, 0.18)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#0f766e',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID'),
                        },
                    },
                },
            },
        });
    </script>
</body>
</html>
