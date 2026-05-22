<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Gunung - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex min-h-screen">

    <div class="w-64 bg-slate-900 text-white flex flex-col justify-between p-6">
        <div class="space-y-6">
            <div class="text-xl font-black uppercase tracking-wider border-b border-slate-700 pb-4 text-center">
                Admin Panel
            </div>
            <nav class="space-y-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="block text-slate-400 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3 transition-colors">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
                <a href="<?= base_url('admin/gunung') ?>" class="block bg-emerald-700 text-white px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
                    <i class="fa-solid fa-mountain"></i> Kelola Gunung (CRUD)
                </a>
                <a href="#" class="block text-slate-400 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3 transition-colors">
                    <i class="fa-solid fa-ticket"></i> Kelola Tiket
                </a>
            </nav>
        </div>
        
        <div>
            <a href="<?= base_url('logout') ?>" class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-3 rounded-xl text-sm font-bold transition-colors">
                <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
            </a>
        </div>
    </div>

    <div class="flex-1 flex flex-col">
        <header class="bg-white p-6 shadow-sm flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Manajemen Destinasi Pendakian</h1>
            <div class="text-sm text-gray-500 font-semibold">
                <i class="fa-solid fa-user-gear mr-1"></i> Admin Aktif
            </div>
        </header>

        <main class="p-8 flex-1 space-y-6">
            
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl text-sm flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-check"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Daftar Gunung</h2>
                    <p class="text-xs text-gray-400 font-medium">Total terdaftar: <?= count($daftar_gunung) ?> destinasi</p>
                </div>
                <a href="<?= base_url('admin/gunung/tambah') ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-emerald-100 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Gunung
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase w-20 text-center">ID</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase">Nama Gunung</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase">Lokasi / Jalur</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (empty($daftar_gunung)) : ?>
                            <tr>
                                <td colspan="4" class="p-12 text-center text-gray-400 font-medium">
                                    <i class="fa-solid fa-folder-open text-3xl mb-2 block text-gray-300"></i>
                                    Belum ada data gunung di database.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($daftar_gunung as $g) : ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-6 text-sm font-bold text-gray-400 text-center">
                                    <?= esc($g['ID_GUNUNG']) ?>
                                </td>
                                <td class="p-6 text-sm font-bold text-gray-800">
                                    <?= esc($g['NAMA_GUNUNG']) ?>
                                </td>
                                <td class="p-6 text-sm font-medium text-gray-500">
                                    <?= esc($g['LOKASI'] ?? 'Jawa Timur') ?>
                                </td>
                                <td class="p-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= base_url('admin/gunung/edit/' . $g['ID_GUNUNG']) ?>" class="bg-blue-50 text-blue-600 p-2.5 rounded-xl hover:bg-blue-600 hover:text-white shadow-sm transition-all text-xs" title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="<?= base_url('admin/gunung/hapus/' . $g['ID_GUNUNG']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus gunung <?= esc($g['NAMA_GUNUNG']) ?>?')" class="bg-red-50 text-red-600 p-2.5 rounded-xl hover:bg-red-600 hover:text-white shadow-sm transition-all text-xs" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>
</html>