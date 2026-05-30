<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Gunung - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f6] text-slate-900 min-h-screen font-sans">

    <?= $this->include('admin/sidebar') ?>

    <div class="ml-64 min-h-screen">
        <div class="max-w-6xl mx-auto px-6 py-8">
            <div class="flex flex-col gap-6">
                
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900">Manajemen Data Gunung</h1>
                        <p class="text-sm text-slate-500 mt-1">Kelola informasi gunung, kuota, dan status jalur pendakian.</p>
                    </div>
                    <a href="<?= base_url('admin/gunung/create') ?>" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700 transition shadow-sm">
                        <i class="fa-solid fa-plus"></i> Tambah Gunung
                    </a>
                </div>

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-medium">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <th class="px-6 py-4">Nama Gunung</th>
                                <th class="px-6 py-4">Lokasi</th>
                                <th class="px-6 py-4">Kapasitas Max</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            <?php if (!empty($daftar_gunung)) : ?>
                                <?php foreach ($daftar_gunung as $g) : ?>
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <td class="px-6 py-4 font-semibold text-slate-900"><?= esc($g['NAMA_GUNUNG']) ?></td>
                                        <td class="px-6 py-4"><?= esc($g['LOKASI']) ?></td>
                                        <td class="px-6 py-4"><?= esc($g['KAPASITAS_MAX'] ?? '-') ?> Orang/Hari</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $g['STATUS_JALUR'] === 'Buka' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' ?>">
                                                <?= esc($g['STATUS_JALUR']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="<?= base_url('admin/gunung/edit/' . $g['ID_GUNUNG']) ?>" class="p-2 text-amber-600 hover:bg-amber-50 rounded-xl transition" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="<?= base_url('admin/gunung/delete/' . $g['ID_GUNUNG']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus gunung ini?')" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400 font-medium">Belum ada data gunung yang tersimpan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</body>
</html>