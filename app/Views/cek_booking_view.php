<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: '#2d5a27',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 pb-10">

    <nav class="bg-white sticky top-0 z-50 border-b shadow-sm">
        <div class="max-w-screen-xl mx-auto px-6 py-4 flex items-center gap-4">
            <a href="<?= base_url('/') ?>" class="text-gray-600">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Cek Status Booking</h1>
        </div>
    </nav>

    <div class="max-w-screen-md mx-auto px-6 mt-8 space-y-6">
        <?php if (!empty($daftar_booking)) : ?>
            <?php foreach ($daftar_booking as $b) : ?>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-mountain-sun text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg"><?= $b['nama_gunung'] ?></h3>
                        <p class="text-sm text-gray-400 font-medium">Tanggal: <?= date('d M Y', strtotime($b['tanggal_booking'])) ?></p>
                        <p class="text-forest font-bold mt-1 text-sm">Rp <?= number_format($b['total_harga'], 0, ',', '.') ?></p>
                    </div>
                </div>

                <?php 
                $statusColor = ($b['status_bayar'] == 'Lunas') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                ?>
                <div class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest <?= $statusColor ?>">
                    <?= $b['status_bayar'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-center py-20">
                <i class="fa-solid fa-calendar-xmark text-6xl text-gray-200 mb-4"></i>
                <p class="text-gray-400 font-medium">Kamu belum memiliki riwayat booking.</p>
                <a href="<?= base_url('destinasi') ?>" class="inline-block mt-4 text-forest font-bold">Cari Destinasi Sekarang →</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if (isset($buka_modal) && $buka_modal && !empty($transaksi_terbaru)): ?>
    <div id="modalInvoice" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full max-h-[90vh] overflow-y-auto shadow-2xl p-6 flex flex-col gap-6 animate-in fade-in zoom-in-95 duration-200">
            
            <div class="flex flex-col items-center text-center gap-2">
                <div class="w-48 h-48 bg-gray-100 rounded-2xl flex flex-col items-center justify-center p-4 border border-dashed border-gray-300">
                    <i class="fa-solid fa-qrcode text-7xl text-gray-400 mb-2"></i>
                    <p class="text-xs text-gray-400 px-2 font-medium">QRIS Placeholder. Scan dengan aplikasimu untuk menyelesaikan pembayaran.</p>
                </div>
                <p class="text-sm text-gray-500 mt-2">Total <span class="text-gray-900 font-black text-base ml-1">Rp <?= number_format($transaksi_terbaru['total_harga'], 0, ',', '.') ?></span></p>
            </div>

            <div class="bg-gray-50/70 border border-gray-100 rounded-2xl p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Pemesan</p>
                <p class="font-bold text-gray-800 text-lg">Faradita</p>
            </div>

            <div class="bg-gray-50/70 border border-gray-100 rounded-2xl p-4 space-y-1 text-sm text-gray-600">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Detail Penyewaan</p>
                <p><span class="font-medium text-gray-400">Gunung:</span> <span class="font-semibold text-gray-800"><?= $transaksi_terbaru['nama_gunung'] ?></span></p>
                <p><span class="font-medium text-gray-400">Tanggal:</span> <span class="font-semibold text-gray-800"><?= date('Y-m-d', strtotime($transaksi_terbaru['tanggal_mendaki'])) ?></span></p>
                <p><span class="font-medium text-gray-400">Sesi Keluar-Masuk:</span> <span class="font-semibold text-gray-700 text-xs"><?= $transaksi_terbaru['sesi'] ?></span></p>
                <p><span class="font-medium text-gray-400">Kode Booking:</span> <span class="font-mono text-xs font-bold text-forest bg-green-50 px-1.5 py-0.5 rounded"><?= $transaksi_terbaru['id_transaksi'] ?></span></p>
            </div>

            <button onclick="tutupModal()" class="w-full bg-forest text-white font-bold py-3.5 rounded-2xl hover:bg-emerald-900 transition-colors shadow-md shadow-green-950/20">
                Tutup
            </button>
        </div>
    </div>

    <script>
        function tutupModal() {
            const modal = document.getElementById('modalInvoice');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
    <?php endif; ?>

</body>
</html>