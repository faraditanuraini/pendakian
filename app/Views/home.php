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
                <div class="hidden md:flex items-center bg-gray-100 px-4 py-2 rounded-full gap-3 border border-transparent focus-within:border-forest/30 focus-within:bg-white transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    <input type="text" placeholder="Cari gunung..." class="bg-transparent text-sm outline-none w-40">
                </div>
                
                <?php if (session()->get('isLoggedIn')) : ?>
                    <a href="<?= base_url('logout') ?>" class="inline-block bg-red-600 text-white px-8 py-2.5 rounded-full text-sm font-bold shadow-md hover:bg-red-700 hover:-translate-y-0.5 transition-all active:scale-95 text-center">
                        Keluar
                    </a>
                <?php else : ?>
                    <a href="<?= base_url('login') ?>" class="inline-block bg-forest text-white px-8 py-2.5 rounded-full text-sm font-bold shadow-md hover:bg-green-800 hover:-translate-y-0.5 transition-all active:scale-95 text-center">
                        Masuk
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
                            Halo <?= session()->get('isLoggedIn') ? esc(session()->get('username')) : 'Pendaki' ?>.,
                        </h2>
                        <p class="text-gray-400 text-base font-medium italic">"The mountains are calling and I must go."</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <?php 
                        $items = [
                            ['icon' => 'fa-mountain', 'label' => 'Tiket Masuk', 'url' => base_url('tiket'), 'color' => 'bg-emerald-100/50'],
                            ['icon' => 'fa-person-hiking', 'label' => 'Porter & Guide', 'url' => base_url('porter-guide'), 'color' => 'bg-sky-100/50'],
                            ['icon' => 'fa-tent', 'label' => 'Sewa Alat', 'url' => base_url('sewa-alat'), 'color' => 'bg-orange-100/50'],
                            ['icon' => 'fa-motorcycle', 'label' => 'Ojek', 'url' => '#', 'color' => 'bg-amber-100/50'],
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

                <section class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-12 border-t border-gray-200">
                    <div class="md:col-span-2 bg-white p-10 rounded-[3rem] shadow-sm border border-gray-100 relative overflow-hidden">
                        <h3 class="font-black text-forest text-xl mb-6 flex items-center gap-3">
                            <i class="fa-solid fa-clock-rotate-left"></i> AKTIVITAS TERAKHIR
                        </h3>
                        
                        <div class="space-y-4">
                            <?php if (!empty($riwayat_transaksi)) : ?>
                                <?php foreach ($riwayat_transaksi as $transaksi) : ?>
                                    <div class="bg-white border border-gray-100 p-5 rounded-3xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-all hover:shadow-md">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-emerald-50 text-emerald-700 rounded-2xl flex items-center justify-center text-xl shrink-0">
                                                <i class="fa-solid fa-receipt"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 font-semibold">Nota #TRX-<?= $transaksi['id_transaksi'] ?></p>
                                                <h4 class="text-sm font-black text-gray-800 mt-0.5">Booking Tiket - <?= esc($transaksi['nama_gunung']) ?></h4>
                                                <p class="text-[11px] text-gray-400 mt-0.5">Booking: <?= date('d M Y - H:i', strtotime($transaksi['tanggal_booking'])) ?> WIB</p>
                                                <p class="text-[11px] text-emerald-600 font-medium">Rencana Mendaki: <?= date('d M Y', strtotime($transaksi['tanggal_mendaki'])) ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex md:flex-col justify-between w-full md:w-auto items-center md:items-end border-t md:border-0 pt-3 md:pt-0">
                                            <p class="text-sm font-black text-emerald-700">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></p>
                                            
                                            <?php if ($transaksi['status_bayar'] === 'Pending' || $transaksi['status_bayar'] === 'Belum Bayar') : ?>
                                                <span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-3 py-1 rounded-full mt-1 border border-amber-200">Menunggu Pembayaran</span>
                                            <?php elseif ($transaksi['status_bayar'] === 'Lunas') : ?>
                                                <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-3 py-1 rounded-full mt-1">Lunas / Siap Mendaki</span>
                                            <?php else : ?>
                                                <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-3 py-1 rounded-full mt-1"><?= esc($transaksi['status_bayar']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="border-2 border-dashed border-gray-200 p-8 rounded-3xl bg-white text-center flex flex-col items-center justify-center min-h-[200px]">
                                    <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-2xl mb-3">
                                        <i class="fa-solid fa-mountain-city"></i>
                                    </div>
                                    <p class="text-sm text-gray-400 font-bold italic">Belum ada riwayat pendakian.</p>
                                    <p class="text-xs text-gray-300 max-w-xs mt-1">Aktivitas pemesanan tiket atau penyewaan alat perlengkapanmu akan otomatis tercatat di sini setelah transaksi dibuat.</p>
                                </div>
                            <?php endif; ?>
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