<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pendaki.id</title>
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
    <a href="<?= base_url('admin/dashboard') ?>" class="block <?= (uri_string() == 'admin/dashboard') ? 'bg-emerald-700' : 'text-slate-400 hover:bg-slate-800' ?> px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3 transition-colors">
        <i class="fa-solid fa-chart-pie"></i> Dashboard
    </a>
    
    <a href="<?= base_url('admin/gunung') ?>" class="block <?= (strpos(uri_string(), 'admin/gunung') !== false) ? 'bg-emerald-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3 transition-colors">
        <i class="fa-solid fa-mountain"></i> Kelola Gunung (CRUD)
    </a>
    
    <a href="<?= base_url('admin/tiket') ?>" class="block text-slate-400 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3 transition-colors">
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
            <h1 class="text-xl font-bold text-gray-800">Selamat Datang, Administrator!</h1>
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Total Gunung</p>
                        <p class="text-2xl font-black text-gray-800 mt-1"><?= esc($total_gunung) ?></p>
                    </div>
                    <div class="bg-emerald-100 p-4 rounded-2xl text-emerald-700 text-2xl"><i class="fa-solid fa-mountain"></i></div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Booking Hari Ini</p>
                        <p class="text-2xl font-black text-gray-800 mt-1"><?= esc($booking_hari_ini) ?></p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-2xl text-blue-700 text-2xl"><i class="fa-solid fa-clipboard-list"></i></div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Total Pendapatan</p>
                        <p class="text-2xl font-black text-gray-800 mt-1">
                            Rp <?= number_format($total_pendapatan, 0, ',', '.') ?>
                        </p>
                    </div>
                    <div class="bg-amber-100 p-4 rounded-2xl text-amber-700 text-2xl"><i class="fa-solid fa-wallet"></i></div>
                </div>

            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 min-h-[300px] flex flex-col items-center justify-center border-2 border-dashed border-gray-200">
                <i class="fa-solid fa-folder-open text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-gray-700 font-bold text-lg mb-1">Siap untuk mengelola data?</h3>
                <p class="text-gray-400 text-sm max-w-sm text-center">Silakan pilih menu di sidebar kiri untuk memulai proses Tambah, Edit, atau Hapus data destinasi pendakian.</p>
            </div>
        </main>
    </div>

</body>
</html>