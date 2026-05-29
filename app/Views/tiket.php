<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Pendakian - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    forest: '#2d5a27', // Ganti dengan kode warna hijau forest yang kamu inginkan
                }
            }
        }
    }
</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfaf7; }
        .bg-forest { background-color: #2D5A27; }
        .text-forest { color: #2D5A27; }
    </style>
</head>
<body class="pb-20">

    <!-- Header / Nav -->
    <nav class="bg-white sticky top-0 z-[100] border-b shadow-sm">
        <div class="max-w-screen-xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?= base_url('/') ?>" class="text-gray-600 hover:text-forest transition-colors">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Tiket Pendakian</h1>
            </div>
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-xl cursor-pointer"></i>
        </div>
    </nav>

    <div class="max-w-screen-xl mx-auto px-6 mt-10 space-y-16">
        
        <!-- SECTION 1: KATEGORI GUNUNG UTAMA -->
        <section>
            <div class="flex items-center gap-3 mb-8">
                <span class="w-1.5 h-8 bg-forest rounded-full"></span>
                <h2 class="text-2xl font-black text-forest uppercase tracking-tighter">Kategori Gunung Utama</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($daftar_gunung as $g) : ?>
                    <?php if (isset($g['KATEGORI']) && strtolower($g['KATEGORI']) == 'utama') : ?>
                        <a href="<?= base_url('gunung/detail/' . $g['ID_GUNUNG']) ?>" class="block group">
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 group border border-gray-100">
                        <div class="relative overflow-hidden aspect-[4/3]">
                            <img src="<?= base_url('uploads/' . $g['GAMBAR']) ?>" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                 alt="<?= $g['NAMA_GUNUNG'] ?>"
                                 onerror="this.src='<?= base_url('uploads/placeholder.jpg') ?>';">
                            <div class="absolute top-4 right-4 bg-black/50 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full">
                                Max: <?= $g['KAPASITAS_MAX'] ?> Org
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-black text-gray-800 text-lg leading-tight mb-1 group-hover:text-forest transition-colors uppercase">
                                <?= $g['NAMA_GUNUNG'] ?>
                            </h3>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">
                                <?= $g['LOKASI'] ?>
                            </p>
                            <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                                <span class="text-sm font-bold text-forest">
                                    Rp <?= number_format($g['HARGA_TIKET'], 0, ',', '.') ?>
                                </span>
                                <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-lg">
                                    <i class="fa-solid fa-cloud mr-1"></i> <?= $g['CUACA'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- SECTION 2: KATEGORI BUKIT & PUTHUK -->
        <section>
            <div class="flex items-center gap-3 mb-8">
                <span class="w-1.5 h-8 bg-orange-500 rounded-full"></span>
                <h2 class="text-2xl font-black text-orange-600 uppercase tracking-tighter">Kategori Bukit & Puthuk</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($daftar_gunung as $g) : ?>
                    <?php if (isset($g['KATEGORI']) && strtolower($g['KATEGORI']) == 'bukit') : ?>
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 group border border-gray-100">
                        <div class="relative overflow-hidden aspect-[4/3]">
                            <!-- Sekarang Bukit juga memanggil foto dari Database -->
                            <img src="<?= base_url('uploads/' . $g['GAMBAR']) ?>" 
     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
     alt="<?= $g['NAMA_GUNUNG'] ?>"
     onerror="this.onerror=null; this.src='<?= base_url('uploads/placeholder.jpg') ?>';">
                            <div class="absolute top-4 right-4 bg-black/50 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full">
                                Max: <?= $g['KAPASITAS_MAX'] ?> Org
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-black text-gray-800 text-lg leading-tight mb-1 group-hover:text-orange-600 transition-colors uppercase">
                                <?= $g['NAMA_GUNUNG'] ?>
                            </h3>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">
                                <?= $g['LOKASI'] ?>
                            </p>
                            <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                                <span class="text-sm font-bold text-orange-600">
                                    Rp <?= number_format($g['HARGA_TIKET'], 0, ',', '.') ?>
                                </span>
                                <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-lg">
                                    <i class="fa-solid fa-cloud mr-1"></i> <?= $g['CUACA'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>

    </div>
</body>
</html>