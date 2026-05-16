<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 pb-10">

    <!-- Nav (Sama dengan Destinasi agar konsisten) -->
    <nav class="bg-white sticky top-0 z-50 border-b shadow-sm">
        <div class="max-w-screen-xl mx-auto px-6 py-4 flex items-center gap-4">
            <a href="<?= base_url('/') ?>" class="text-gray-600">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Cek Status Booking</h1>
        </div>
    </nav>

    <div class="max-w-screen-md mx-auto px-6 mt-8 space-y-6">
        <?php if (!empty($booking_list)) : ?>
            <?php foreach ($booking_list as $b) : ?>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <!-- Icon Gunung -->
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-mountain-sun text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg"><?= $b['nama_gunung'] ?></h3>
                        <p class="text-sm text-gray-400 font-medium">Tanggal: <?= date('d M Y', strtotime($b['tanggal_booking'])) ?></p>
                        <p class="text-forest font-bold mt-1 text-sm">Rp <?= number_format($b['total_harga'], 0, ',', '.') ?></p>
                    </div>
                </div>

                <!-- Status Badge -->
                <?php 
                $statusColor = ($b['status'] == 'Lunas') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                ?>
                <div class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest <?= $statusColor ?>">
                    <?= $b['status'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else : ?>
            <!-- Tampilan Jika Kosong -->
            <div class="text-center py-20">
                <i class="fa-solid fa-calendar-xmark text-6xl text-gray-200 mb-4"></i>
                <p class="text-gray-400 font-medium">Kamu belum memiliki riwayat booking.</p>
                <a href="<?= base_url('destinasi') ?>" class="inline-block mt-4 text-forest font-bold">Cari Destinasi Sekarang →</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>