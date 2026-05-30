<?php
$equipments = $equipments ?? [];
$activeRentals = $activeRentals ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Alat - Admin Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#f4f7f6] text-slate-900 min-h-screen font-sans">
    <?= $this->include('admin/sidebar') ?>

    <div class="ml-64 min-h-screen">
        <header class="bg-white/90 backdrop-blur sticky top-0 z-10 border-b border-slate-200 px-8 py-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Manajemen Sewa Alat</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau ketersediaan perlengkapan dan selesaikan sewa dengan cepat.</p>
            </div>
            <a href="<?= base_url('admin/dashboard') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </header>

        <main class="p-8 space-y-8">
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-700 shadow-sm"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="rounded-3xl border border-red-200 bg-red-50 px-6 py-4 text-red-700 shadow-sm"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 overflow-hidden">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-5">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Katalog Stok Alat</h2>
                        <p class="text-sm text-slate-500 mt-1">Lihat stok tersedia dan kondisi setiap perlengkapan.</p>
                    </div>
                    <a href="<?= base_url('admin/equipments/create') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">
                        <i class="fa-solid fa-plus"></i> Tambah Alat
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-700 border-collapse">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">Nama Alat</th>
                                <th class="px-5 py-4 font-semibold">Total Stok</th>
                                <th class="px-5 py-4 font-semibold">Stok Tersedia</th>
                                <th class="px-5 py-4 font-semibold">Kondisi</th>
                                <th class="px-5 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <?php if (!empty($equipments)) : ?>
                                <?php foreach ($equipments as $equipment) : ?>
                                    <?php
                                        $condition = $equipment['kondisi'] ?? 'Baik';
                                        $conditionClass = $condition === 'Baik' ? 'bg-emerald-100 text-emerald-700' : ($condition === 'Perlu Dicuci' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700');
                                    ?>
                                    <tr>
                                        <td class="px-5 py-4 font-semibold text-slate-900"><?= esc($equipment['nama_alat']) ?></td>
                                        <td class="px-5 py-4"><?= number_format((int) $equipment['total_stok'], 0, ',', '.') ?></td>
                                        <td class="px-5 py-4"><?= number_format((int) $equipment['stok_tersedia'], 0, ',', '.') ?></td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold <?= $conditionClass ?>"><?= esc($condition) ?></span>
                                        </td>
                                        <td class="px-5 py-4 flex flex-col gap-2">
                                            <a href="<?= base_url('admin/equipments/edit/' . $equipment['id']) ?>" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>
                                            <a href="<?= base_url('admin/equipments/delete/' . $equipment['id']) ?>" onclick="return confirm('Hapus alat ini?')" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100 transition">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="px-5 py-16 text-center text-slate-500">Belum ada data inventaris alat.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Logistik Sewa Aktif</h2>
                        <p class="text-sm text-slate-500 mt-1">Daftar alat yang sedang dipinjam, dengan tanggal kembali dan aksi penyelesaian sewa.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-700 border-collapse">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">Transaksi</th>
                                <th class="px-5 py-4 font-semibold">Alat</th>
                                <th class="px-5 py-4 font-semibold">Jumlah</th>
                                <th class="px-5 py-4 font-semibold">Pinjam</th>
                                <th class="px-5 py-4 font-semibold">Tgl Kembali</th>
                                <th class="px-5 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <?php if (!empty($activeRentals)) : ?>
                                <?php foreach ($activeRentals as $rental) : ?>
                                    <tr>
                                        <td class="px-5 py-4 font-semibold text-slate-900">#<?= esc($rental['transaction_id']) ?> — <?= esc($rental['nama_ketua'] ?? $rental['nama_penyewa'] ?? 'Pelanggan') ?></td>
                                        <td class="px-5 py-4"><?= esc($rental['nama_alat']) ?></td>
                                        <td class="px-5 py-4"><?= number_format((int) $rental['jumlah'], 0, ',', '.') ?></td>
                                        <td class="px-5 py-4"><?= esc($rental['tgl_pinjam']) ?></td>
                                        <td class="px-5 py-4"><?= esc($rental['tgl_kembali']) ?: 'Belum ditentukan' ?></td>
                                        <td class="px-5 py-4">
                                            <button type="button" class="complete-rental-button inline-flex items-center gap-2 rounded-full bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition" data-id="<?= esc($rental['id']) ?>" data-equipment="<?= esc($rental['nama_alat']) ?>" data-jumlah="<?= esc($rental['jumlah']) ?>">
                                                <i class="fa-solid fa-circle-check"></i> Selesai Sewa
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center text-slate-500">Tidak ada sewa aktif saat ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="returnModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4 py-8">
        <div class="w-full max-w-xl rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h3 class="text-xl font-black text-slate-900">Selesaikan Sewa</h3>
                    <p class="text-sm text-slate-500 mt-1">Kembalikan stok dan perbarui kondisi alat.</p>
                </div>
                <button id="closeReturnModal" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="post" id="returnForm" class="space-y-5 p-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Alat</label>
                        <input type="text" id="modalToolName" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900" disabled>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Jumlah Dipinjam</label>
                        <input type="text" id="modalToolQuantity" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900" disabled>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-600">Kondisi Alat Setelah Kembali</label>
                    <select name="kondisi" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                        <option value="Baik">Baik</option>
                        <option value="Perlu Dicuci">Perlu Dicuci</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button type="button" id="cancelReturn" class="rounded-2xl border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">Update Selesai</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const rentalButtons = document.querySelectorAll('.complete-rental-button');
        const returnModal = document.getElementById('returnModal');
        const closeReturnModal = document.getElementById('closeReturnModal');
        const cancelReturn = document.getElementById('cancelReturn');
        const modalToolName = document.getElementById('modalToolName');
        const modalToolQuantity = document.getElementById('modalToolQuantity');
        const returnForm = document.getElementById('returnForm');

        rentalButtons.forEach(button => {
            button.addEventListener('click', () => {
                const rentalId = button.dataset.id;
                const equipmentName = button.dataset.equipment;
                const quantity = button.dataset.jumlah;
                modalToolName.value = equipmentName;
                modalToolQuantity.value = `${quantity} unit`;
                returnForm.action = `<?= base_url('admin/equipments/return') ?>/${rentalId}`;
                returnModal.classList.remove('hidden');
                returnModal.classList.add('flex');
            });
        });

        const closeModal = () => {
            returnModal.classList.add('hidden');
            returnModal.classList.remove('flex');
        };

        closeReturnModal.addEventListener('click', closeModal);
        cancelReturn.addEventListener('click', closeModal);
        returnModal.addEventListener('click', (event) => {
            if (event.target === returnModal) closeModal();
        });
    </script>
</body>
</html>
