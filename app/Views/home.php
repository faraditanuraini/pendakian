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
                    <div class="absolute top-[-20%] right-[-5%] w-96 h-96 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-colors duration-700"></div>
                    <div class="absolute bottom-[-10%] left-[20%] w-64 h-64 bg-black/10 rounded-full blur-2xl"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
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

                        <div class="hidden lg:flex items-center gap-4 opacity-40 group-hover:opacity-100 transition-all duration-700 transform group-hover:translate-x-2">
                             <div class="text-right">
                                <p class="text-6xl font-black leading-none italic">EXPLORE</p>
                                <p class="text-2xl font-bold tracking-[0.3em] opacity-60">THE WILDERNESS</p>
                             </div>
                             <i class="fa-solid fa-compass text-9xl rotate-12"></i>
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

                <section class="space-y-6">
                    <div class="flex justify-between items-center px-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-1 bg-forest rounded-full"></span>
                            <h3 class="text-forest font-black text-xl uppercase tracking-tighter">Promo Meriah</h3>
                        </div>
                        <a href="#" class="text-forest text-xs font-bold px-4 py-2 bg-white border rounded-full hover:bg-forest hover:text-white transition-all">Lihat Semua</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php if (!empty($data_promo)) : ?>
                            <?php foreach ($data_promo as $promo) : ?>
                                <div class="group relative rounded-[3rem] overflow-hidden aspect-[21/9] shadow-xl">
                                    <img src="<?= esc($promo['gambar']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                    <div class="absolute inset-0 bg-gradient-to-r <?= esc($promo['gradient']) ?> p-10 flex flex-col justify-center">
                                        <span class="text-white font-black text-3xl leading-tight max-w-[200px]"><?= esc($promo['judul']) ?></span>
                                        <button class="mt-6 <?= esc($promo['warna_button']) ?> px-6 py-2 rounded-xl text-xs font-black self-start shadow-lg hover:scale-110 transition-transform"><?= esc($promo['tombol']) ?></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="col-span-2 border-2 border-dashed border-gray-200 p-8 rounded-3xl text-center bg-white">
                                <p class="text-xs text-gray-400 font-bold italic">Belum ada promo tersedia.</p>
                            </div>
                        <?php endif; ?>
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
                    <div class="bg-forest p-8 rounded-[3rem] shadow-2xl text-white relative overflow-hidden group flex flex-col justify-between min-h-[280px] h-full">
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <div>
                                <p class="font-black text-2xl mb-2 leading-tight uppercase tracking-tight">BUTUH<br>BANTUAN?</p>
                                <p class="text-xs opacity-75 mb-6 font-medium leading-relaxed"> CS kami siap melayani rencana muncakmu 24/7. </p>
                            </div>
                            <button class="w-full bg-white text-forest py-3.5 rounded-2xl text-xs font-black hover:bg-yellow-400 hover:text-gray-900 transition-all shadow-lg active:scale-95">
                                HUBUNGI KAMI
                            </button>
                        </div>
                        <i class="fa-solid fa-headset text-8xl absolute -bottom-6 -right-6 opacity-10 group-hover:rotate-12 transition-transform"></i>
                    </div>
                </section>
                
            </main>
        </div>
    </div>

</body>
</html>