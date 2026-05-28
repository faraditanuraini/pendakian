<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Pendakian - Pro Desktop Look</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfaf7; } /* Hint of cream background */
        .bg-forest { background-color: #2D5A27; }
        .text-forest { color: #2D5A27; }
        .bg-mocca { background-color: #A38C7D; }
    </style>
</head>
<body class="min-h-screen">

    <!-- 1. TOP NAVIGATION -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm border-b sticky top-0 z-[100]">
        <div class="max-w-screen-xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-10">
                <span class="font-black text-forest tracking-tighter text-2xl uppercase">Pendaki.id</span>
                <div class="hidden md:flex gap-8 text-sm font-bold text-gray-500">
                    <a href="#" class="text-forest border-b-2 border-forest pb-1">Beranda</a>
                    <!-- Cari bagian ini di Navbar kamu -->
<a href="<?= base_url('destinasi') ?>" class="hover:text-forest transition-colors">Destinasi</a>
                    <a href="<?= base_url('cek-booking') ?>" class="hover:text-forest transition-colors">Cek Booking</a>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="hidden md:flex items-center bg-gray-100 px-4 py-2 rounded-full gap-3 border border-transparent focus-within:border-forest/30 focus-within:bg-white transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    <input type="text" placeholder="Cari gunung..." class="bg-transparent text-sm outline-none w-40">
                </div>
                <button class="bg-forest text-white px-8 py-2.5 rounded-full text-sm font-bold shadow-md hover:bg-green-800 hover:-translate-y-0.5 transition-all active:scale-95">
                    Masuk
                </button>
            </div>
        </div>
    </nav>

    <!-- 2. MAIN CONTENT AREA -->
    <div class="max-w-screen-xl mx-auto px-4 md:px-6 lg:px-8 mt-6 pb-20">
        <div class="flex flex-col gap-12">
            
            <main class="space-y-12">
                
                <!-- Header Banner dengan Filler Visual -->
                <header class="bg-forest text-white p-8 md:p-12 rounded-[2.5rem] shadow-lg relative overflow-hidden group">
                    <!-- Layer Dekorasi Abstract -->
                    <div class="absolute top-[-20%] right-[-5%] w-96 h-96 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-colors duration-700"></div>
                    <div class="absolute bottom-[-10%] left-[20%] w-64 h-64 bg-black/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                        <!-- Sisi Kiri: Teks -->
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-10">
                                <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm shadow-inner">
                                    <i class="fa-solid fa-mountain-sun text-4xl"></i>
                                </div>
                                <h1 class="text-3xl font-extrabold tracking-tight">TIKET PENDAKIAN</h1>
                            </div>

                            <div class="bg-white text-gray-800 p-5 rounded-2xl flex items-center gap-5 shadow-xl max-w-sm transform hover:rotate-1 transition-transform">
                                <div class="bg-yellow-400 w-12 h-12 rounded-full flex items-center justify-center shrink-0 shadow-md">
                                    <i class="fa-solid fa-coins text-white text-xl"></i>
                                </div>
                                <div class="leading-tight">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Point Pendaki</p>
                                    <p class="text-[12px] font-bold text-gray-700 leading-snug">Dapatkan diskon dengan merawat ekosistem di area pendakian</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sisi Kanan: Visual Filler (Mengisi kekosongan image_57d97c.png) -->
                        <div class="hidden lg:flex items-center gap-4 opacity-40 group-hover:opacity-100 transition-all duration-700 transform group-hover:translate-x-2">
                             <div class="text-right">
                                <p class="text-6xl font-black leading-none italic">EXPLORE</p>
                                <p class="text-2xl font-bold tracking-[0.3em] opacity-60">THE WILDERNESS</p>
                             </div>
                             <i class="fa-solid fa-compass text-9xl rotate-12"></i>
                        </div>
                    </div>
                </header>

                <!-- Greeting & Menu Fitur -->
                <section class="space-y-8">
                    <div>
                        <h2 class="text-forest font-extrabold text-3xl mb-1">Halo Faradita.,</h2>
                        <p class="text-gray-400 text-base font-medium italic">"The mountains are calling and I must go."</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <?php 
                        $items = [
                            ['icon' => 'fa-mountain', 'label' => 'Tiket Masuk', 'url' => base_url('tiket'), 'color' => 'bg-emerald-100/50'],
                            ['icon' => 'fa-person-hiking', 'label' => 'Porter & Guide', 'url' => base_url('porter-guide'), 'color' => 'bg-sky-100/50'],
                            ['icon' => 'fa-tent', 'label' => 'Sewa Alat', 'url' => base_url('sewa-alat'), 'color' => 'bg-orange-100/50'],
                            ['icon' => 'fa-motorcycle', 'label' => 'Ojek', 'url' => base_url('ojek'), 'color' => 'bg-amber-100/50'],
                        ];
                        foreach ($items as $it) : ?>
                            <a href="<?= $it['url'] ?>" class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col items-center gap-4 hover:shadow-2xl hover:-translate-y-2 transition-all group">
                                <div class="<?= $it['color'] ?> p-6 rounded-3xl group-hover:bg-forest transition-colors duration-300">
                                    <i class="fa-solid <?= $it['icon'] ?> text-3xl text-forest group-hover:text-white transition-colors duration-300"></i>
                                </div>
                                <span class="text-sm font-extrabold text-forest uppercase tracking-wider"><?= $it['label'] ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Promo Section -->
                <section class="space-y-6">
                    <div class="flex justify-between items-center px-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-1 bg-forest rounded-full"></span>
                            <h3 class="text-forest font-black text-xl uppercase tracking-tighter">Promo Meriah</h3>
                        </div>
                        <a href="#" class="text-forest text-xs font-bold px-4 py-2 bg-white border rounded-full hover:bg-forest hover:text-white transition-all">Lihat Semua</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group relative rounded-[3rem] overflow-hidden aspect-[21/9] shadow-xl">
                            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/20 to-transparent p-10 flex flex-col justify-center">
                                <span class="text-white font-black text-3xl leading-tight max-w-[200px]">Diskon Alat Outdoor 20%</span>
                                <button class="mt-6 bg-orange-500 text-white px-6 py-2 rounded-xl text-xs font-black self-start shadow-lg hover:scale-110 transition-transform">AMBIL</button>
                            </div>
                        </div>
                        <div class="group relative rounded-[3rem] overflow-hidden aspect-[21/9] shadow-xl">
                            <img src="https://images.unsplash.com/photo-1501555088652-021faa106b9b?w=800" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                            <div class="absolute inset-0 bg-gradient-to-r from-forest/90 via-forest/20 to-transparent p-10 flex flex-col justify-center">
                                <span class="text-white font-black text-3xl leading-tight max-w-[200px]">Open Trip Prau Merapi</span>
                                <button class="mt-6 bg-white text-forest px-6 py-2 rounded-xl text-xs font-black self-start shadow-lg hover:scale-110 transition-transform">CEK JADWAL</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Footer Widgets -->
                <section class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-12 border-t border-gray-200">
                    <div class="md:col-span-2 bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 relative overflow-hidden">
                        <h3 class="font-black text-forest text-xl mb-6 flex items-center gap-3">
                            <i class="fa-solid fa-clock-rotate-left"></i> AKTIVITAS TERAKHIR
                        </h3>
                        <div class="py-6 flex flex-col items-center justify-center border-2 border-dashed border-gray-100 rounded-[2rem]">
                            <i class="fa-solid fa-mountain-city text-5xl text-gray-100 mb-4"></i>
                            <p class="text-gray-400 text-sm font-bold italic tracking-wide">Belum ada riwayat pendakian.</p>
                        </div>
                    </div>

                    <div class="bg-forest p-10 rounded-[3rem] shadow-2xl text-white relative overflow-hidden group">
                        <div class="relative z-10">
                            <p class="font-black text-2xl mb-4 leading-tight">BUTUH<br>BANTUAN?</p>
                            <p class="text-sm opacity-70 mb-8 font-medium">CS kami siap melayani rencana muncakmu 24/7.</p>
                            <button class="w-full bg-white text-forest py-4 rounded-2xl text-xs font-black hover:bg-yellow-400 transition-all shadow-lg active:scale-95">
                                HUBUNGI KAMI
                            </button>
                        </div>
                        <i class="fa-solid fa-headset text-9xl absolute -bottom-10 -right-10 opacity-10 group-hover:rotate-12 transition-transform"></i>
                    </div>
                </section>
                
            </main>
        </div>
    </div>

</body>
</html>