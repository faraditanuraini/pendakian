<?php
// Pastikan Controller mengirimkan $total_gunung, $booking_hari_ini, $pendaki_aktif, $total_pendapatan, $daftar_gunung, dan $recent_transactions
$total_gunung = $total_gunung ?? 0; 
$booking_hari_ini = $booking_hari_ini ?? 0;
$pendaki_aktif = $pendaki_aktif ?? 0; 
$total_pendapatan = $total_pendapatan ?? 0;
$daftar_gunung = $daftar_gunung ?? []; 
$recent_transactions = $recent_transactions ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Sembunyikan scrollbar bawaan browser agar UI terlihat clean */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#f4f7f6] flex min-h-screen font-sans text-slate-800">

    <?= $this->include('admin/sidebar') ?>

    <div class="flex-1 flex flex-col ml-64">
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-10 px-8 py-5 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Ringkasan Sistem</h1>
                <p class="text-sm text-slate-500 font-medium">Pantau aktivitas pendakian dan transaksi secara real-time.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-bold text-slate-800">Administrator</p>
                    <p class="text-xs text-emerald-600 font-bold flex items-center justify-end gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Online</p>
                </div>
                <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center text-slate-500 border-2 border-white shadow-sm">
                    <i class="fa-solid fa-user-shield text-xl"></i>
                </div>
            </div>
        </header>

        <main class="p-8 flex-1 space-y-8">
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl text-sm font-bold flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Pendaki di Atas</p>
                        <p class="text-3xl font-black text-slate-800 mt-2"><?= esc($pendaki_aktif) ?> <span class="text-sm font-bold text-slate-400">Jiwa</span></p>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl"><i class="fa-solid fa-person-hiking"></i></div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Booking Hari Ini</p>
                        <p class="text-3xl font-black text-slate-800 mt-2"><?= esc($booking_hari_ini) ?> <span class="text-sm font-bold text-slate-400">Grup</span></p>
                    </div>
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 text-2xl"><i class="fa-solid fa-clipboard-list"></i></div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                        <p class="text-2xl font-black text-slate-800 mt-2 tracking-tight">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></p>
                    </div>
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl"><i class="fa-solid fa-wallet"></i></div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-wider">Gunung Terdaftar</p>
                        <p class="text-3xl font-black text-slate-800 mt-2"><?= esc($total_gunung) ?> <span class="text-sm font-bold text-slate-400">Lokasi</span></p>
                    </div>
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 text-2xl"><i class="fa-solid fa-mountain"></i></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-black text-slate-800">Grafik Maksimal Kapasitas Jalur</h3>
                        <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full">Database Terhubung</span>
                    </div>
                    <div class="relative flex-1 min-h-[300px] w-full">
                        <canvas id="kuotaChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col h-[390px]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-black text-slate-800">Status Jalur</h3>
                        <i class="fa-solid fa-tower-broadcast text-slate-400 animate-pulse"></i>
                    </div>
                    
                    <div class="space-y-3 flex-1 overflow-y-auto pr-1 scrollbar-hide">
                        <?php if (!empty($daftar_gunung)) : ?>
                            <?php foreach ($daftar_gunung as $g) : ?>
                                <?php 
                                    $mountainName = $g['NAMA_GUNUNG'] ?? $g['nama'] ?? 'Tidak Diketahui';
                                    $mountainStatus = strtolower($g['STATUS_JALUR'] ?? $g['status_jalur'] ?? '');
                                    $kapasitasMax = (int) ($g['KAPASITAS_MAX'] ?? $g['kuota_harian'] ?? 0);
                                    $sisaKuota = (int) ($g['SISA_KUOTA'] ?? $g['sisa_kuota'] ?? 0);
                                    $isOpen = in_array($mountainStatus, ['open', 'buka', 'opened']);
                                    $textColor = $isOpen ? 'text-emerald-600' : 'text-red-500';
                                    $statusText = $isOpen ? 'Jalur Dibuka' : 'Jalur Ditutup';
                                ?>
                                <div class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-100 bg-slate-50 hover:border-slate-200 transition">
                                    <div class="max-w-[65%]">
                                        <p class="font-black text-xs text-slate-800 uppercase tracking-tight truncate"><?= esc($mountainName) ?></p>
                                        <p class="status-text-label text-[11px] font-bold <?= $textColor ?> mt-0.5"><?= esc($statusText) ?> | <span class="text-slate-400 font-medium"><?= esc($g['CUACA'] ?? 'Cerah') ?></span></p>
                                        <p class="text-[10px] text-slate-500 mt-1">Kuota: <?= number_format($kapasitasMax, 0, ',', '.') ?> | Sisa: <?= number_format($sisaKuota, 0, ',', '.') ?></p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" value="<?= esc($g['ID_GUNUNG'] ?? $g['id'] ?? '') ?>" class="sr-only peer status-toggle" <?= $isOpen ? 'checked' : '' ?>>
                                        <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="text-center text-sm text-slate-400 py-20 flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-folder-open text-2xl text-slate-300"></i>
                                <p>Data gunung belum dimuat.<br><span class="text-xs text-red-400 font-semibold">Cek parsing data dari Controller!</span></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-black text-slate-800">Rekapitulasi Transaksi Terpadu</h3>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Data digenerate menggunakan teknik spesifik basis data (JOIN Multi-table & ORDER BY Descending)</p>
                    </div>
                    <a href="<?= base_url('admin/transaksi') ?>" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-full text-xs font-bold transition inline-flex items-center">Lihat Laporan Lengkap</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-xs uppercase tracking-widest text-slate-400 border-b border-slate-100">
                                <th class="p-5 font-black">ID Booking</th>
                                <th class="p-5 font-black">Pengguna</th>
                                <th class="p-5 font-black">Destinasi</th>
                                <th class="p-5 font-black">Layanan Terkait</th>
                                <th class="p-5 font-black">Total Tagihan</th>
                                <th class="p-5 font-black">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                            <?php if(!empty($recent_transactions)): ?>
                                <?php foreach ($recent_transactions as $trx) : ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-5 font-bold text-slate-800"><?= $trx['id'] ?></td>
                                    <td class="p-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex justify-center items-center font-bold text-xs">
                                                <?= substr($trx['user'], 0, 1) ?>
                                            </div>
                                            <?= $trx['user'] ?>
                                        </div>
                                    </td>
                                    <td class="p-5 font-bold uppercase text-xs tracking-wide"><?= $trx['gunung'] ?></td>
                                    <td class="p-5 text-slate-500 text-xs"><?= $trx['layanan'] ?></td>
                                    <td class="p-5 font-black text-emerald-600">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
                                    <td class="p-5">
                                        <span class="<?= in_array($trx['status'], ['Lunas', 'Sudah Bayar']) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?> px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wide">
                                            <?= $trx['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="p-5 text-center text-slate-400 text-xs">Belum ada transaksi terbaru.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        // 1. KODE GRAFIK / CHART SINKRON DATABASE
        const ctx = document.getElementById('kuotaChart').getContext('2d');
        const labelsGunung = [
            <?php foreach ($daftar_gunung as $g): ?>
                '<?= esc(str_replace(['Gunung ', 'Bukit ', 'Puthuk '], '', $g['NAMA_GUNUNG'] ?? $g['nama'] ?? '')) ?>',
            <?php endforeach; ?>
        ];

        const dataKapasitas = [
            <?php foreach ($daftar_gunung as $g): ?>
                <?= (int) ($g['KAPASITAS_MAX'] ?? $g['kuota_harian'] ?? 0) ?>,
            <?php endforeach; ?>
        ];

        const backgroundColors = [
            <?php foreach ($daftar_gunung as $g): ?>
                <?php 
                    $mountainStatus = strtolower($g['STATUS_JALUR'] ?? $g['status_jalur'] ?? '');
                    $isOpen = in_array($mountainStatus, ['open', 'buka', 'opened']);
                    echo $isOpen ? "'rgba(16, 185, 129, 0.85)'," : "'rgba(239, 68, 68, 0.85)',";
                ?>
            <?php endforeach; ?>
        ];

        const kuotaChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsGunung,
                datasets: [{
                    label: 'Kapasitas Max (Orang)',
                    data: dataKapasitas,
                    backgroundColor: backgroundColors,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Kapasitas: ${context.raw} Orang`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        border: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            font: { size: 9, weight: 'bold' },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });

        // 2. KODE AJAX TOGGLE SAKELAR JALUR AKTIF REALTIME
        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const idGunung = this.value;
                const statusBaru = this.checked ? 'Buka' : 'Tutup';
                const textStatus = this.closest('.flex')?.querySelector('.status-text-label');

                // Endpoint disesuaikan dengan fungsi toggleStatus di MountainController kamu
                fetch('<?= base_url('admin/gunung/toggle-status') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id: idGunung,
                        status: this.checked ? 'open' : 'closed'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && textStatus) {
                        if (statusBaru === 'Buka') {
                            textStatus.innerHTML = `Jalur Dibuka | <span class="text-slate-400 font-medium">Diperbarui Admin</span>`;
                            textStatus.className = 'status-text-label text-[11px] font-bold text-emerald-600 mt-0.5';
                        } else {
                            textStatus.innerHTML = `Jalur Ditutup | <span class="text-slate-400 font-medium">Diperbarui Admin</span>`;
                            textStatus.className = 'status-text-label text-[11px] font-bold text-red-500 mt-0.5';
                        }
                    } else {
                        this.checked = !this.checked;
                        alert('Gagal memperbarui status di database.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !this.checked;
                });
            });
        });
    </script>
</body>
</html>