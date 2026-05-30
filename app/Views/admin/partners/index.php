<?php
$partners = $partners ?? [];
$transactions = $transactions ?? [];
$assignments = $assignments ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partners - Admin Pendaki.id</title>
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
                <h1 class="text-3xl font-black text-slate-900">Manajemen Mitra Lokal</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola ketersediaan ojek, porter, dan guide untuk setiap transaksi secara terpusat.</p>
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

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-black text-slate-900">Direktori Mitra Lokal</h2>
                            <p class="text-sm text-slate-500 mt-1">Lihat status dan gunakan dispatcher untuk menugaskan mitra.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="<?= base_url('admin/partners/create') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">
                                <i class="fa-solid fa-plus"></i> Partner Baru
                            </a>
                            <span class="rounded-2xl bg-slate-100 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Dispatcher System</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm text-slate-700 border-collapse">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="px-5 py-4 font-semibold">Nama Mitra</th>
                                    <th class="px-5 py-4 font-semibold">Tipe</th>
                                    <th class="px-5 py-4 font-semibold">Kontak</th>
                                    <th class="px-5 py-4 font-semibold">Status</th>
                                    <th class="px-5 py-4 font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <?php if (!empty($partners)) : ?>
                                    <?php foreach ($partners as $partner) : ?>
                                        <?php
                                            $status = $partner['status'] ?? 'Tersedia';
                                            $statusClass = $status === 'Tersedia' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700';
                                            $typeClass = $partner['tipe'] === 'Guide' ? 'bg-blue-100 text-blue-700' : ($partner['tipe'] === 'Porter' ? 'bg-orange-100 text-orange-700' : 'bg-slate-100 text-slate-700');
                                        ?>
                                        <tr>
                                            <td class="px-5 py-4 font-semibold text-slate-900"><?= esc($partner['nama']) ?></td>
                                            <td class="px-5 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold <?= $typeClass ?>"><?= esc($partner['tipe']) ?></span>
                                            </td>
                                            <td class="px-5 py-4 text-slate-500"><?= esc($partner['kontak']) ?></td>
                                            <td class="px-5 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold <?= $statusClass ?>"><?= esc($status) ?></span>
                                            </td>
                                            <td class="px-5 py-4 space-y-2">
                                                <?php $buttonDisabled = $status !== 'Tersedia'; ?>
                                                <div class="flex flex-col gap-2">
                                                    <a href="<?= base_url('admin/partners/edit/' . $partner['id']) ?>" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                                    </a>
                                                    <a href="<?= base_url('admin/partners/delete/' . $partner['id']) ?>" onclick="return confirm('Hapus partner ini?')" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100 transition">
                                                        <i class="fa-solid fa-trash"></i> Hapus
                                                    </a>
                                                    <button type="button" class="assign-button inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-3 py-2 text-xs font-bold text-white transition hover:bg-slate-800 <?= $buttonDisabled ? 'opacity-50 cursor-not-allowed' : '' ?>" data-id="<?= esc($partner['id']) ?>" data-name="<?= esc($partner['nama']) ?>" data-type="<?= esc($partner['tipe']) ?>" data-status="<?= esc($status) ?>" <?= $buttonDisabled ? 'disabled aria-disabled="true"' : '' ?>>
                                                        <i class="fa-solid fa-paper-plane"></i> Tugaskan
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="px-5 py-16 text-center text-slate-500">Belum ada data mitra tersedia.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-xl font-black text-slate-900 mb-4">Ringkasan Dispatcher</h2>
                    <div class="space-y-4">
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                            <p class="text-sm text-slate-500 uppercase tracking-[0.2em] font-black">Mitra Tersedia</p>
                            <p class="mt-4 text-3xl font-black text-slate-900"><?= count(array_filter($partners, fn($partner) => $partner['status'] === 'Tersedia')) ?></p>
                        </div>
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                            <p class="text-sm text-slate-500 uppercase tracking-[0.2em] font-black">Mitra Bertugas</p>
                            <p class="mt-4 text-3xl font-black text-slate-900"><?= count(array_filter($partners, fn($partner) => $partner['status'] === 'Bertugas')) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Riwayat Tugas Mitra</h2>
                        <p class="text-sm text-slate-500 mt-1">Lihat daftar penugasan mitra ke transaksi beserta tanggal tugas.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-700 border-collapse">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">Mitra</th>
                                <th class="px-5 py-4 font-semibold">Tipe</th>
                                <th class="px-5 py-4 font-semibold">Transaksi</th>
                                <th class="px-5 py-4 font-semibold">Tanggal Tugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
    <?php if (!empty($assignments)) : ?>
        <?php foreach ($assignments as $item) : ?>
            <tr>
                <td class="px-5 py-4 font-semibold text-slate-900"><?= esc($item['partner_name']) ?></td>
                <td class="px-5 py-4 uppercase text-xs tracking-[0.2em] text-slate-500"><?= esc($item['partner_type']) ?></td>
                <td class="px-5 py-4 text-slate-500">
                    User ID: <?= esc($item['ID_USER'] ?? 'N/A') ?> 
                    (#<?= esc($item['transaction_id']) ?>)
                </td>
                <td class="px-5 py-4 text-slate-500"><?= esc($item['tgl_tugas']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="4" class="px-5 py-16 text-center text-slate-500">Belum ada penugasan mitra.</td>
        </tr>
    <?php endif; ?>
</tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="assignModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4 py-8">
        <div class="w-full max-w-2xl rounded-3xl bg-white shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h3 class="text-xl font-black text-slate-900">Tugaskan Mitra</h3>
                    <p class="text-sm text-slate-500 mt-1">Pilih transaksi yang akan didukung oleh mitra ini.</p>
                </div>
                <button id="closeAssignModal" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form method="post" action="<?= base_url('admin/partners/assign') ?>" class="space-y-5 p-6">
                <input type="hidden" name="partner_id" id="modalPartnerId">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Nama Mitra</label>
                        <input type="text" id="modalPartnerName" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" disabled>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Tanggal Tugas</label>
                        <input type="date" name="tgl_tugas" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-600">Pilih Transaksi</label>
                    <select name="transaction_id" id="modalTransactionSelect" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
    <option value="">Pilih transaksi...</option>
    <?php foreach ($transactions as $trx) : ?>
        <option value="<?= esc($trx['ID_TRANSAKSI']) ?>">
            #<?= esc($trx['ID_TRANSAKSI']) ?> — 
            User ID: <?= esc($trx['ID_USER'] ?? 'Pengguna') ?> — 
            <?= esc($trx['NAMA_GUNUNG'] ?? 'Gunung Tidak Ditemukan') ?>
        </option>
    <?php endforeach; ?>
</select>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button type="button" id="cancelAssign" class="rounded-2xl border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800 transition">Tugaskan Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const assignButtons = document.querySelectorAll('.assign-button');
        const assignModal = document.getElementById('assignModal');
        const closeAssignModal = document.getElementById('closeAssignModal');
        const cancelAssign = document.getElementById('cancelAssign');
        const modalPartnerId = document.getElementById('modalPartnerId');
        const modalPartnerName = document.getElementById('modalPartnerName');

        const openAssignModal = (partnerId, partnerName) => {
            modalPartnerId.value = partnerId;
            modalPartnerName.value = partnerName;
            assignModal.classList.remove('hidden');
            assignModal.classList.add('flex');
        };

        const closeModal = () => {
            assignModal.classList.add('hidden');
            assignModal.classList.remove('flex');
        };

        assignButtons.forEach(button => {
            button.addEventListener('click', () => {
                if (button.disabled) return;
                openAssignModal(button.dataset.id, button.dataset.name);
            });
        });

        closeAssignModal.addEventListener('click', closeModal);
        cancelAssign.addEventListener('click', closeModal);
        assignModal.addEventListener('click', (event) => {
            if (event.target === assignModal) closeModal();
        });
    </script>
</body>
</html>
