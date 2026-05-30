<?php
$partner = $partner ?? ['nama' => '', 'tipe' => 'Ojek', 'kontak' => '', 'status' => 'Tersedia'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Partner - Admin Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f6] text-slate-900 min-h-screen font-sans">
    <?= $this->include('admin/sidebar') ?>

    <div class="ml-64 min-h-screen">
        <header class="bg-white/90 backdrop-blur sticky top-0 z-10 border-b border-slate-200 px-8 py-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Edit Partner</h1>
                <p class="text-sm text-slate-500 mt-1">Perbarui data partner lokal.</p>
            </div>
            <a href="<?= base_url('admin/partners') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-bold text-white hover:bg-slate-800 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </header>

        <main class="p-8">
            <div class="max-w-3xl rounded-3xl bg-white border border-slate-200 shadow-sm p-8">
                <form action="<?= base_url('admin/partners/update/' . (int) $partner['id']) ?>" method="post" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Nama Partner</label>
                        <input type="text" name="nama" value="<?= esc($partner['nama']) ?>" class="mt-3 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                    </div>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Tipe Partner</label>
                            <select name="tipe" class="mt-3 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                                <option value="Ojek" <?= $partner['tipe'] === 'Ojek' ? 'selected' : '' ?>>Ojek</option>
                                <option value="Porter" <?= $partner['tipe'] === 'Porter' ? 'selected' : '' ?>>Porter</option>
                                <option value="Guide" <?= $partner['tipe'] === 'Guide' ? 'selected' : '' ?>>Guide</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Status</label>
                            <select name="status" class="mt-3 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                                <option value="Tersedia" <?= $partner['status'] === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                <option value="Bertugas" <?= $partner['status'] === 'Bertugas' ? 'selected' : '' ?>>Bertugas</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Kontak</label>
                        <input type="text" name="kontak" value="<?= esc($partner['kontak']) ?>" class="mt-3 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-emerald-500 focus:outline-none" required>
                    </div>
                    <div class="flex justify-between gap-3">
                        <a href="<?= base_url('admin/partners') ?>" class="rounded-3xl border border-slate-200 bg-slate-100 px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-200 transition">Batal</a>
                        <button type="submit" class="rounded-3xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">Perbarui Partner</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>