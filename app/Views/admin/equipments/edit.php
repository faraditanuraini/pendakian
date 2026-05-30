<?php
// Inisialisasi data agar tidak error jika variabel kosong
$equipment = $equipment ?? ['id' => '', 'nama_alat' => '', 'total_stok' => 0, 'stok_tersedia' => 0, 'harga_sewa' => 0, 'kondisi' => 'Baik', 'gambar' => ''];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat - Admin Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f6] text-slate-900 min-h-screen font-sans">
    <?= $this->include('admin/sidebar') ?>

    <div class="ml-64 min-h-screen">
        <header class="bg-white/90 backdrop-blur sticky top-0 z-10 border-b border-slate-200 px-8 py-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Edit Alat</h1>
            </div>
            <a href="<?= base_url('admin/equipments') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </header>

        <main class="p-8">
            <div class="max-w-3xl rounded-3xl bg-white border border-slate-200 shadow-sm p-8">
                
                <?php if (session()->has('errors')): ?>
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                        <p class="font-bold">Terjadi kesalahan:</p>
                        <ul class="list-disc ml-5">
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/equipments/update/' . (int) $equipment['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Nama Alat</label>
                        <input type="text" name="nama_alat" value="<?= esc($equipment['nama_alat']) ?>" class="mt-3 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Total Stok</label>
                            <input type="number" name="total_stok" value="<?= esc($equipment['total_stok']) ?>" min="0" class="mt-3 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Stok Tersedia</label>
                            <input type="number" name="stok_tersedia" value="<?= esc($equipment['stok_tersedia']) ?>" min="0" class="mt-3 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Harga Sewa per Hari (Rp)</label>
                        <input type="number" name="harga_sewa" value="<?= esc($equipment['harga_sewa'] ?? 0) ?>" min="0" class="mt-3 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Kondisi</label>
                        <select name="kondisi" class="mt-3 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                            <option value="Baik" <?= $equipment['kondisi'] === 'Baik' ? 'selected' : '' ?>>Baik</option>
                            <option value="Perlu Dicuci" <?= $equipment['kondisi'] === 'Perlu Dicuci' ? 'selected' : '' ?>>Perlu Dicuci</option>
                            <option value="Rusak" <?= $equipment['kondisi'] === 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Gambar Alat</label>
                        <?php if (!empty($equipment['gambar'])): ?>
                            <p class="text-xs text-slate-500 mt-1 mb-2">Gambar saat ini: <?= esc($equipment['gambar']) ?></p>
                        <?php endif; ?>
                        <input type="file" name="gambar" class="mt-3 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none">
                        <p class="text-xs text-slate-400 mt-2">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    </div>

                    <div class="flex justify-between gap-3 pt-4 border-t border-slate-100">
                        <a href="<?= base_url('admin/equipments') ?>" class="rounded-3xl border border-slate-200 bg-slate-100 px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-200 transition">Batal</a>
                        <button type="submit" class="rounded-3xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">Perbarui Alat</button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>