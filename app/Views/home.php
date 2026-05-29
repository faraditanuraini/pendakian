<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Pendakian - Pro Desktop Look</title>
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfaf7; }
        .bg-forest { background-color: #2D5A27; }
        .text-forest { color: #2D5A27; }
        .bg-mocca { background-color: #A38C7D; }
    </style>
</head>
<body class="min-h-screen">

    <nav class="bg-white/80 backdrop-blur-md shadow-sm border-b sticky top-0 z-[100]">
        <div class="max-w-screen-xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-10">
                <span class="font-black text-forest tracking-tighter text-2xl uppercase">Pendaki.id</span>
                <div class="hidden md:flex gap-8 text-sm font-bold text-gray-500">
                    <a href="#" class="text-forest border-b-2 border-forest pb-1">Beranda</a>
                    <a href="<?= base_url('destinasi') ?>" class="hover:text-forest transition-colors">Destinasi</a>
                    <a href="<?= base_url('cek-booking') ?>" class="hover:text-forest transition-colors">Cek Booking</a>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <form action="<?= base_url('destinasi') ?>" method="get" class="hidden md:flex items-center bg-gray-100 px-4 py-2 rounded-full gap-3 border border-transparent focus-within:border-forest/30 focus-within:bg-white transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    <input type="text" name="search" placeholder="Cari gunung..." class="bg-transparent text-sm outline-none w-40">
                </form>
                
                <?php if (session()->get('isLoggedIn')) : ?>
                    <a href="<?= base_url('logout') ?>" class="inline-block bg-red-600 text-white px-8 py-2.5 rounded-full text-sm font-bold shadow-md hover:bg-red-700 hover:-translate-y-0.5 transition-all active:scale-95 text-center">
                        Keluar
                    </a>
                <?php else : ?>
                    <a href="<?= base_url('login') ?>" class="inline-block bg-forest text-white px-8 py-2.5 rounded-full text-sm font-bold shadow-md hover:bg-green-800 hover:-translate-y-0.5 transition-all active:scale-95 text-center">
                        Masuk / Daftar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="max-w-screen-xl mx-auto px-4 md:px-6 lg:px-8 mt-6 pb-20">
        <div class="flex flex-col gap-12">
            
            <main class="space-y-12">
                
                <header class="bg-forest text-white p-8 md:p-12 rounded-[2.5rem] shadow-lg relative overflow-hidden group">
                    <!-- Background Mountains & Forest Vector Silhouette (Full Width bottom-aligned) -->
                    <div class="absolute bottom-0 left-0 w-full h-1/2 pointer-events-none overflow-hidden select-none z-0 opacity-20">
                        <svg class="w-full h-full object-cover object-bottom" viewBox="0 0 1000 300" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                            <!-- Mountain Back (Sage green tone) -->
                            <path d="M0 300 L120 120 L280 200 L450 90 L600 180 L780 70 L920 190 L1000 130 L1000 300 Z" fill="#b8d5b3" opacity="0.25"/>
                            <!-- Mountain Mid (Darker green overlay) -->
                            <path d="M0 300 L90 170 L220 220 L380 140 L530 200 L680 120 L840 210 L1000 150 L1000 300 Z" fill="#1b3d16" opacity="0.45"/>
                            <!-- Mountain Front (Deepest dark green) -->
                            <path d="M0 300 L50 210 L160 250 L300 180 L440 230 L580 160 L720 220 L880 170 L1000 240 L1000 300 Z" fill="#0c2409" opacity="0.7"/>
                            
                            <!-- Pine forest ridge at the bottom edge -->
                            <path d="M0 300 L5 270 L10 275 L15 260 L20 268 L25 250 L30 255 L35 240 L40 248 L45 235 L50 240 L55 255 L60 250 L65 265 L70 260 L75 272 L80 268 L85 280 L90 275 L95 285 L100 280 L105 270 L110 275 L115 260 L120 265 L125 250 L130 255 L135 240 L140 245 L145 230 L150 235 L155 250 L160 245 L165 260 L170 255 L175 268 L180 264 L185 275 L190 270 L195 282 L200 278 L205 270 L210 275 L215 260 L220 265 L225 250 L230 255 L235 238 L240 244 L245 225 L250 230 L255 248 L260 243 L265 258 L270 253 L275 265 L280 260 L285 272 L290 268 L295 280 L300 275 L305 270 L310 275 L315 258 L320 264 L325 245 L330 250 L335 235 L340 242 L345 228 L350 232 L355 248 L360 243 L365 258 L370 253 L375 265 L380 260 L385 272 L390 268 L395 280 L400 275 L405 270 L410 275 L415 258 L420 264 L425 245 L430 250 L435 235 L440 242 L445 228 L450 232 L455 248 L460 243 L465 258 L470 253 L475 265 L480 260 L485 272 L490 268 L495 280 L500 275 L505 270 L510 275 L515 258 L520 264 L525 245 L530 250 L535 235 L540 242 L545 228 L550 232 L555 248 L560 243 L565 258 L570 253 L575 265 L580 260 L585 272 L590 268 L595 280 L600 275 L605 270 L610 275 L615 258 L620 264 L625 245 L630 250 L635 235 L640 242 L645 228 L650 232 L655 248 L660 243 L665 258 L670 253 L675 265 L680 260 L685 272 L690 268 L695 280 L700 275 L705 270 L710 275 L715 258 L720 264 L725 245 L730 250 L735 235 L740 242 L745 228 L750 232 L755 248 L760 243 L765 258 L770 253 L775 265 L776 265 L780 260 L785 272 L790 268 L795 280 L800 275 L805 270 L810 275 L815 258 L820 264 L825 245 L830 250 L835 235 L840 242 L845 228 L850 232 L855 248 L860 243 L865 258 L870 253 L875 265 L876 265 L880 260 L885 272 L890 268 L895 280 L900 275 L905 270 L910 275 L915 258 L920 264 L925 245 L930 250 L935 235 L940 242 L945 228 L950 232 L955 248 L960 243 L965 258 L970 253 L975 265 L980 260 L985 272 L990 268 L995 280 L1000 275 L1000 300 Z" fill="#051204" opacity="0.9"/>
                        </svg>
                    </div>

                    <!-- Ambient Glow Effects -->
                    <div class="absolute top-[-20%] right-[-5%] w-96 h-96 bg-white/10 rounded-full blur-3xl group-hover:bg-white/15 transition-colors duration-700 z-0"></div>
                    <div class="absolute bottom-[-10%] left-[20%] w-64 h-64 bg-black/10 rounded-full blur-2xl z-0"></div>

                    <!-- Banner Content -->
                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                        <div class="w-full lg:max-w-[55%] flex flex-col">
                            <!-- Title -->
                            <div class="flex items-center gap-4 mb-8">
                                <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm shadow-inner text-white">
                                    <i class="fa-solid fa-mountain-sun text-3xl"></i>
                                </div>
                                <h1 class="text-3xl font-extrabold tracking-tight text-white">TIKET PENDAKIAN</h1>
                            </div>

                            <!-- Note For You Card -->
                            <div class="bg-white text-gray-800 p-6 md:p-8 rounded-[2rem] shadow-2xl w-full max-w-lg transform hover:scale-[1.01] transition-all duration-300 border border-gray-100/50">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-xl font-extrabold text-gray-900 tracking-tight uppercase">Note For You</h4>
                                    <span class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-forest">
                                        <i class="fa-solid fa-feather-pointed text-sm"></i>
                                    </span>
                                </div>
                                
                                <div class="border-b border-gray-100 mb-5"></div>
                                
                                <div class="py-1">
                                    <p class="text-gray-700 italic text-sm md:text-base leading-relaxed font-semibold">
                                        "Setiap gunung punya cerita, dan setiap jalur pendakian mengajarkan kita tentang arti kesabaran serta kerendahan hati di hadapan semesta."
                                    </p>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-3 mt-6 pt-4 border-t border-gray-50 text-[11px] font-extrabold tracking-wider">
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50/70 text-forest px-3 py-1.5 rounded-full uppercase shadow-xs">
                                        <i class="fa-solid fa-tag text-[9px] text-forest"></i> Respect Nature
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50/70 text-forest px-3 py-1.5 rounded-full uppercase shadow-xs">
                                        <i class="fa-solid fa-tag text-[9px] text-forest"></i> Be Prepared
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50/70 text-forest px-3 py-1.5 rounded-full uppercase shadow-xs">
                                        <i class="fa-solid fa-tag text-[9px] text-forest"></i> Leave No Trace
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Text Area (Clean with Silhouette background) -->
                        <div class="hidden lg:flex flex-col items-end text-right select-none pr-4 relative z-10">
                            <p class="text-7xl md:text-8xl font-black tracking-tight text-white italic leading-none drop-shadow-md">
                                EXPLORE
                            </p>
                            <p class="text-2xl md:text-3xl font-extrabold tracking-[0.25em] text-[#cbf3c9] mt-3 leading-none uppercase drop-shadow-sm">
                                THE WILDERNESS
                            </p>
                        </div>
                    </div>
                </header>

                <section class="space-y-8">
                    <div>
                        <h2 class="text-forest font-extrabold text-3xl mb-1">
                            <?php if (session()->get('isLoggedIn')) : ?>
                                Halo, <?= esc(session()->get('username')) ?>!
                            <?php else : ?>
                                Selamat Datang di Pendaki.ID
                            <?php endif; ?>
                        </h2>
                        <p class="text-gray-400 text-base font-medium italic">
                            <?php if (session()->get('isLoggedIn')) : ?>
                                "The mountains are calling and I must go."
                            <?php else : ?>
                                Jelajahi Alam Bersama Kami!
                            <?php endif; ?>
                        </p>
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

                <section class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-12 border-t border-gray-200 items-stretch">
                    <!-- Kolom Riwayat Transaksi -->
                    <div class="md:col-span-2 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 flex flex-col justify-between overflow-hidden">
                        <div>
                            <!-- Header Section dengan Tombol "Lihat Semua" di Kanan Asal -->
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-black text-forest text-xl flex items-center gap-3">
                                    <i class="fa-solid fa-clock-rotate-left"></i> AKTIVITAS TERAKHIR
                                </h3>
                                <?php if (session()->get('isLoggedIn')) : ?>
                                    <a href="<?= base_url('cek-booking') ?>" class="text-forest hover:text-green-800 text-xs font-bold px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-full transition-all border border-gray-100 shadow-sm flex items-center gap-2">
                                        Lihat Semua Aktivitas <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-3">
                                <?php if (session()->get('isLoggedIn') && !empty($riwayat_transaksi)) : ?>
                                    <?php foreach ($riwayat_transaksi as $transaksi) : ?>
                                        <!-- Desain List yang Lebih Ringkas & Ramping (p-4, text-xs) -->
                                        <div class="bg-white border border-gray-100 p-4 rounded-2xl shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 transition-all hover:shadow-sm">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-emerald-50 text-emerald-700 rounded-xl flex items-center justify-center text-lg shrink-0">
                                                    <i class="fa-solid fa-receipt"></i>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[10px] text-gray-400 font-bold">#TRX-<?= $transaksi['id_transaksi'] ?></span>
                                                        <span class="text-[10px] text-gray-300">|</span>
                                                        <span class="text-[10px] text-gray-400"><?= date('d M Y - H:i', strtotime($transaksi['tanggal_booking'])) ?> WIB</span>
                                                    </div>
                                                    <h4 class="text-xs font-black text-gray-800 mt-0.5">Booking Tiket - <?= esc($transaksi['nama_gunung']) ?></h4>
                                                    <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Pendakian: <?= date('d M Y', strtotime($transaksi['tanggal_mendaki'])) ?></p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex sm:flex-col justify-between w-full sm:w-auto items-center sm:items-end border-t sm:border-0 pt-2 sm:pt-0 gap-1">
                                                <p class="text-xs font-black text-emerald-700">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></p>
                                                
                                                <?php if ($transaksi['status_bayar'] === 'Pending' || $transaksi['status_bayar'] === 'Belum Bayar') : ?>
                                                    <span class="bg-amber-50 text-amber-700 text-[9px] font-bold px-2 py-0.5 rounded-full border border-amber-200">Pending</span>
                                                <?php elseif ($transaksi['status_bayar'] === 'Lunas') : ?>
                                                    <span class="bg-emerald-100 text-emerald-700 text-[9px] font-bold px-2 py-0.5 rounded-full">Lunas</span>
                                                <?php else : ?>
                                                    <span class="bg-gray-100 text-gray-600 text-[9px] font-bold px-2 py-0.5 rounded-full"><?= esc($transaksi['status_bayar']) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <!-- Tampilan Jika Belum Login / Belum Ada Transaksi -->
                                    <div class="border-2 border-dashed border-gray-200 py-8 px-4 rounded-3xl bg-white text-center flex flex-col items-center justify-center min-h-[180px]">
                                        <div class="w-12 h-12 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-xl mb-2">
                                            <i class="fa-solid fa-mountain-city"></i>
                                        </div>
                                        <?php if (!session()->get('isLoggedIn')) : ?>
                                            <p class="text-xs text-gray-400 font-bold">Ingin melihat riwayat transaksimu?</p>
                                            <a href="<?= base_url('login') ?>" class="mt-2 text-[11px] font-black text-forest hover:underline">Masuk Sekarang &rarr;</a>
                                        <?php else : ?>
                                            <p class="text-xs text-gray-400 font-bold italic">Belum ada riwayat pendakian.</p>
                                            <p class="text-[10px] text-gray-300 max-w-xs mt-1">Aktivitas pemesanan tiket gunung akan tercatat di sini setelah transaksi berhasil dibuat.</p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Card Butuh Bantuan (Kanan) -->
                    <div class="bg-forest p-8 rounded-[3rem] shadow-2xl text-white relative overflow-hidden group flex flex-col justify-center items-center text-center h-full">
                        <div class="relative z-10 flex flex-col items-center justify-center text-center space-y-6 w-full h-full py-4">
                            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center text-white mb-1 shadow-inner shrink-0">
                                <i class="fa-solid fa-headset text-3xl"></i>
                            </div>
                            <div class="space-y-2">
                                <p class="font-black text-2xl leading-tight uppercase tracking-tight">BUTUH BANTUAN?</p>
                                <p class="text-xs opacity-75 font-medium leading-relaxed max-w-[200px] mx-auto">CS kami siap melayani rencana muncakmu 24/7.</p>
                            </div>
                            <a href="https://wa.me/6283119317920?text=Halo%20Admin%2C%20saya%20butuh%20bantuan%20terkait%20aplikasi%20pendakian." class="w-full max-w-[220px] bg-white text-forest py-3.5 rounded-2xl text-xs font-black hover:bg-yellow-400 hover:text-gray-900 transition-all shadow-lg active:scale-95 text-center shrink-0">
                                HUBUNGI KAMI
                            </a>
                        </div>
                        <i class="fa-solid fa-headset text-8xl absolute -bottom-6 -right-6 opacity-10 group-hover:rotate-12 transition-transform"></i>
                    </div>
                </section>
                
            </main>
        </div>
    </div>

</body>
</html>