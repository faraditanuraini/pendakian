<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Gunung - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f6] text-slate-900 min-h-screen font-sans">

    <?= $this->include('admin/sidebar') ?>

    <div class="ml-64 min-h-screen">
        <div class="max-w-3xl mx-auto px-6 py-8">
            <div class="flex flex-col gap-6">
                
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900">Edit Data Gunung</h1>
                            <p class="text-sm text-slate-500 mt-1">Silakan ubah formulir di bawah untuk memperbarui data gunung.</p>
                        </div>
                        <a href="<?= base_url('admin/gunung') ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
                    <?php 
                        // Proteksi agar data terbaca aman baik huruf besar maupun kecil dari database
                        $id_gunung = $gunung['ID_GUNUNG'] ?? $gunung['id'] ?? '';
                        $nama_gunung = $gunung['NAMA_GUNUNG'] ?? $gunung['nama'] ?? $gunung['nama_gunung'] ?? '';
                        $lokasi = $gunung['LOKASI'] ?? $gunung['lokasi'] ?? '';
                        $kapasitas_max = $gunung['KAPASITAS_MAX'] ?? $gunung['kapasitas_max'] ?? $gunung['kuota_harian'] ?? 0;
                        $status_jalur = $gunung['STATUS_JALUR'] ?? $gunung['status_jalur'] ?? 'Buka';
                    ?>

                    <form action="<?= base_url('admin/gunung/update/' . $id_gunung) ?>" method="post" class="flex flex-col gap-6">
                        <?= csrf_field() ?>
                        
                        <div class="flex flex-col gap-2">
                            <label for="nama_gunung" class="text-sm font-bold text-slate-700">Nama Gunung</label>
                            <input type="text" id="nama_gunung" name="nama_gunung" value="<?= esc($nama_gunung) ?>" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-emerald-500 focus:bg-white focus:outline-none transition">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="lokasi" class="text-sm font-bold text-slate-700">Lokasi / Wilayah</label>
                            <input type="text" id="lokasi" name="lokasi" value="<?= esc($lokasi) ?>" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-emerald-500 focus:bg-white focus:outline-none transition">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="kapasitas_max" class="text-sm font-bold text-slate-700">Kapasitas Maksimal Kuota (Orang/Hari)</label>
                            <input type="number" id="kapasitas_max" name="kapasitas_max" value="<?= (int)$kapasitas_max ?>" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-emerald-500 focus:bg-white focus:outline-none transition">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label for="status_jalur" class="text-sm font-bold text-slate-700">Status Jalur</label>
                            <div class="relative">
                                <select id="status_jalur" name="status_jalur" required
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-emerald-500 focus:bg-white focus:outline-none transition appearance-none">
                                    <option value="Buka" <?= $status_jalur === 'Buka' ? 'selected' : '' ?>>Buka</option>
                                    <option value="Tutup" <?= $status_jalur === 'Tutup' ? 'selected' : '' ?>>Tutup</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <a href="<?= base_url('admin/gunung') ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition shadow-sm">
                                <i class="fa-solid fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>
</html>