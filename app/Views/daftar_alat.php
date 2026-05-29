<?php
// Helper untuk emoji fallback jika gambar peralatan belum diupload
function getAlatEmoji($id) {
    switch ($id) {
        case 'ALAT-001': return '⛺'; // Tenda Dome
        case 'ALAT-002': return '🎒'; // Carrier Consina
        case 'ALAT-003': return '💤'; // Sleeping Bag
        case 'ALAT-004': return '🧘'; // Matras Spons
        case 'ALAT-005': return '🔥'; // Kompor Portable
        case 'ALAT-006': return '🍲'; // Nesting Set
        case 'ALAT-007': return '🥾'; // Tracking Pole
        case 'ALAT-008': return '🔦'; // Headlamp
        case 'ALAT-009': return '🏮'; // Lampu Tenda
        case 'ALAT-010': return '⛺'; // Flysheet
        default: return '📦';
    }
}

// Helper untuk gradien background fallback
function getAlatGradient($id) {
    switch ($id) {
        case 'ALAT-001': return 'from-emerald-400 to-teal-600';
        case 'ALAT-002': return 'from-orange-400 to-rose-600';
        case 'ALAT-003': return 'from-blue-400 to-indigo-600';
        case 'ALAT-004': return 'from-violet-400 to-purple-600';
        case 'ALAT-005': return 'from-yellow-400 to-amber-600';
        case 'ALAT-006': return 'from-cyan-400 to-sky-600';
        case 'ALAT-007': return 'from-lime-400 to-lime-600';
        case 'ALAT-008': return 'from-rose-400 to-rose-600';
        case 'ALAT-009': return 'from-amber-400 to-amber-600';
        case 'ALAT-010': return 'from-sky-400 to-indigo-600';
        default: return 'from-slate-400 to-slate-600';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian Sewa Alat - Pendaki.id</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f7f6f1; }
        
        /* Custom Scrollbar for list */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    <!-- Midtrans Snap.js Sandbox -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= env('midtrans.clientKey') ?? 'Mid-client-5DWPhj0CBsPVQy8Q' ?>"></script>
</head>
<body class="min-h-screen text-slate-800 flex flex-col pb-28 lg:pb-0">

    <!-- Navigation Header -->
    <nav class="bg-white/95 backdrop-blur-md shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-5 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="<?= base_url('sewa-alat') ?>" class="inline-flex items-center justify-center w-11 h-11 rounded-3xl bg-slate-100 text-slate-700 shadow-sm hover:bg-slate-200 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold">Sewa Alat</p>
                    <h1 class="text-lg font-black text-slate-900">Perlengkapan Camping Tersedia</h1>
                </div>
            </div>
            
            <a href="<?= base_url('sewa-alat') ?>" class="hidden md:inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-2xl text-xs font-bold transition">
                <i class="fa-solid fa-sliders"></i> Ubah Pencarian
            </a>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl w-full mx-auto px-5 py-6 flex-grow flex flex-col gap-6">

        <!-- Banner Filter Aktif (Informasi Pencarian) -->
        <div class="bg-gradient-to-r from-forest to-emerald-800 rounded-[2rem] p-6 text-white shadow-md relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl">
                <i class="fa-solid fa-campground"></i>
            </div>
            <p class="text-xs uppercase tracking-[0.25em] text-emerald-200 font-bold mb-2">Informasi Rute Pendakian & Sewa</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-center relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-mountain"></i>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-100">Lokasi Gunung</p>
                        <h4 class="font-bold text-sm leading-tight"><?= esc($gunung['NAMA_GUNUNG']) ?></h4>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-100">Tanggal Sewa</p>
                        <h4 class="font-bold text-sm leading-tight"><?= date('d F Y', strtotime($filter['tanggal_sewa'])) ?></h4>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-100">Pos Masuk</p>
                        <h4 class="font-bold text-sm leading-tight"><?= esc($filter['pos_masuk']) ?></h4>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-100">Pos Keluar</p>
                        <h4 class="font-bold text-sm leading-tight"><?= esc($filter['pos_keluar']) ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2 Kolom Layout (Grid Alat Kiri & Keranjang Belanja Kanan) -->
        <div class="flex flex-col lg:flex-row gap-8 items-start w-full relative">
            
            <!-- KOLOM KIRI: GRID ALAT OUTDOOR -->
            <div class="w-full lg:w-2/3 xl:w-3/4 space-y-6">
                
                <!-- Kategori & Keterangan -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-cubes text-forest"></i> Pilih Perlengkapan Camping
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kuantitas sewa dibatasi berdasarkan ketersediaan stok riil.</p>
                    </div>
                    
                    <span class="bg-forest/10 text-forest px-3.5 py-1.5 rounded-full text-xs font-bold border border-forest/10">
                        Total <?= count($daftar_alat) ?> Alat Tersedia
                    </span>
                </div>

                <!-- Grid Item Alat -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php if (!empty($daftar_alat)) : ?>
                        <?php foreach ($daftar_alat as $alat) : ?>
                            <?php 
                            $isOutOfStock = $alat['STOK'] <= 0;
                            $emoji = getAlatEmoji($alat['ID_PERALATAN']);
                            $gradient = getAlatGradient($alat['ID_PERALATAN']);
                            ?>
                            <div class="bg-white rounded-[2rem] border border-slate-200 p-5 flex flex-col shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 <?= $isOutOfStock ? 'opacity-65' : '' ?>">
                                
                                <!-- Visual Image / Thumbnail -->
                                <div class="mb-5 h-48 w-full rounded-[1.5rem] relative shadow-inner overflow-hidden select-none bg-slate-100 flex items-center justify-center">
                                    <?php if (!empty($alat['GAMBAR'])) : ?>
                                        <!-- Menampilkan Gambar Lokal dengan Auto-Fallback jika file tidak ditemukan -->
                                        <img src="<?= base_url('assets/img/peralatan/' . $alat['GAMBAR']) ?>" alt="<?= esc($alat['NAMA_ALAT']) ?>" class="w-full h-full object-cover transform hover:scale-105 transition duration-500" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="absolute inset-0 bg-gradient-to-br <?= $gradient ?> flex items-center justify-center text-white text-6xl hidden">
                                            <div class="absolute inset-0 bg-black/10 opacity-30"></div>
                                            <span class="relative z-10 filter drop-shadow-md transform hover:scale-110 transition duration-300"><?= $emoji ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="absolute inset-0 bg-gradient-to-br <?= $gradient ?> flex items-center justify-center text-white text-6xl">
                                            <div class="absolute inset-0 bg-black/10 opacity-30"></div>
                                            <span class="relative z-10 filter drop-shadow-md transform hover:scale-110 transition duration-300"><?= $emoji ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Status Stok Tipis -->
                                    <?php if ($alat['STOK'] > 0 && $alat['STOK'] <= 5) : ?>
                                        <span class="absolute top-3 left-3 bg-amber-500 text-white text-[10px] uppercase font-black tracking-widest px-2.5 py-1 rounded-full shadow-md z-10">
                                            Sisa <?= $alat['STOK'] ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Nama & Deskripsi Alat -->
                                <div class="flex-grow space-y-1.5">
                                    <h4 class="text-base font-black text-slate-900 leading-tight">
                                        <?= esc($alat['NAMA_ALAT']) ?>
                                    </h4>
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-warehouse"></i> Stok: <?= $alat['STOK'] ?> Pcs
                                        </span>
                                        <span class="text-slate-300 text-[10px]">•</span>
                                        <span class="text-xs font-semibold <?= $isOutOfStock ? 'text-rose-500' : 'text-emerald-600' ?>">
                                            <?= $isOutOfStock ? 'Habis Disewa' : 'Ready' ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <hr class="border-slate-100 my-4">

                                <!-- Harga Sewa & Kontrol Item -->
                                <div class="flex items-center justify-between gap-4 mt-auto">
                                    <div>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-widest">Biaya / Hari</p>
                                        <p class="text-base font-black text-slate-900">
                                            Rp <?= number_format($alat['HARGA_SEWA'], 0, ',', '.') ?>
                                        </p>
                                    </div>

                                    <?php if ($isOutOfStock) : ?>
                                        <button disabled class="bg-slate-100 text-slate-400 text-xs font-bold px-4 py-3 rounded-2xl cursor-not-allowed">
                                            Stok Habis
                                        </button>
                                    <?php else : ?>
                                        <!-- Container Kontrol Kuantitas Interaktif -->
                                        <div id="control-<?= esc($alat['ID_PERALATAN']) ?>">
                                            <!-- Tombol Tambah Pertama Kali -->
                                            <button 
                                                type="button" 
                                                onclick="addToCart('<?= esc($alat['ID_PERALATAN']) ?>', '<?= esc($alat['NAMA_ALAT']) ?>', <?= $alat['HARGA_SEWA'] ?>, <?= $alat['STOK'] ?>, '<?= $emoji ?>')"
                                                class="bg-forest hover:bg-emerald-800 text-white text-xs font-black tracking-wider px-5 py-3 rounded-2xl shadow-md hover:shadow-lg transition duration-200">
                                                <i class="fa-solid fa-plus mr-1"></i> TAMBAH
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200">
                            <i class="fa-solid fa-triangle-exclamation text-slate-300 text-5xl mb-3"></i>
                            <h4 class="font-black text-slate-700">Peralatan Kosong</h4>
                            <p class="text-slate-400 text-sm mt-1">Maaf, data perlengkapan sewa belum diinput di database.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- KOLOM KANAN: SIDEBAR RINGKASAN SEWA (DESKTOP MODE) -->
            <div class="hidden lg:block lg:w-1/3 xl:w-1/4 sticky top-28 h-fit">
                <div class="bg-white rounded-[2rem] border border-slate-200 p-6 shadow-sm space-y-6">
                    
                    <!-- Header Keranjang -->
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-shopping-basket text-forest"></i> Ringkasan Sewa
                        </h3>
                        <span id="cart-badge-desktop" class="bg-forest text-white text-xs font-bold px-2.5 py-1 rounded-full">
                            0
                        </span>
                    </div>

                    <!-- List Belanjaan -->
                    <div id="cart-list-desktop" class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
                        <!-- State Kosong Default -->
                        <div class="rounded-3xl border-2 border-dashed border-slate-200 p-8 text-center text-slate-400">
                            <i class="fa-solid fa-cart-shopping text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs leading-normal">Belum ada alat camping yang dipilih.</p>
                        </div>
                    </div>

                    <!-- Total & Rincian -->
                    <div class="border-t border-slate-100 pt-5 space-y-3">
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Estimasi Hari</span>
                            <span class="font-bold text-slate-700">1 Hari</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="text-xs text-slate-500 font-semibold mb-0.5">Total Harga</span>
                            <span id="cart-total-desktop" class="text-lg font-black text-slate-900">
                                Rp 0
                            </span>
                        </div>
                    </div>

                    <!-- Form Integrasi Midtrans -->
                    <form action="<?= base_url('tiket/proses_bayar') ?>" method="POST" id="checkout-form-desktop" class="w-full">
                        <?= csrf_field() ?>
                        <!-- Hidden Inputs filter booking -->
                        <input type="hidden" name="id_gunung" value="<?= esc($filter['id_gunung']) ?>">
                        <input type="hidden" name="tanggal_sewa" value="<?= esc($filter['tanggal_sewa']) ?>">
                        <input type="hidden" name="pos_masuk" value="<?= esc($filter['pos_masuk']) ?>">
                        <input type="hidden" name="pos_keluar" value="<?= esc($filter['pos_keluar']) ?>">
                        <!-- Keranjang Belanja terenkripsi/encoded JSON -->
                        <input type="hidden" name="cart_items" id="cart-items-input-desktop" value="[]">

                        <button 
                            type="button" 
                            onclick="handleCheckout()"
                            class="w-full bg-[#2D5A27] text-white font-black text-sm py-4 rounded-2xl shadow-md hover:bg-emerald-800 hover:shadow-lg transition-all duration-200 block text-center select-none">
                            Lanjut Pembayaran
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </main>

    <!-- FLOATING BOTTOM SHEET / DRAWER (MOBILE MODE - HP) -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-[0_-8px_30px_rgb(0,0,0,0.12)] rounded-t-[2.5rem] p-5 z-40 space-y-4">
        
        <!-- Toggle Atas Mini -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm font-black text-slate-900">Ringkasan Sewa</span>
                <span id="cart-badge-mobile" class="bg-forest text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                    0
                </span>
            </div>
            
            <button onclick="toggleMobileCartDrawer()" class="text-forest text-xs font-bold hover:underline flex items-center gap-1.5">
                <i class="fa-solid fa-list-check"></i> <span id="toggle-drawer-text">Lihat Detail</span>
            </button>
        </div>

        <!-- Detail Drawer Tersembunyi Default (Bisa ditoggle) -->
        <div id="mobile-cart-drawer" class="hidden max-h-[220px] overflow-y-auto pr-1 py-2 border-t border-slate-100">
            <div id="cart-list-mobile" class="space-y-3">
                <div class="text-slate-400 text-center py-4 text-xs">Keranjang kosong.</div>
            </div>
        </div>

        <!-- Harga Ringkasan & Tombol Aksi -->
        <div class="flex items-center justify-between gap-4 pt-1">
            <div>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold">Total Tagihan</p>
                <p id="cart-total-mobile" class="text-lg font-black text-slate-900 leading-none">
                    Rp 0
                </p>
            </div>

            <!-- Form Mobile Checkout -->
            <form action="<?= base_url('tiket/proses_bayar') ?>" method="POST" id="checkout-form-mobile" class="flex-grow max-w-[200px]">
                <?= csrf_field() ?>
                <input type="hidden" name="id_gunung" value="<?= esc($filter['id_gunung']) ?>">
                <input type="hidden" name="tanggal_sewa" value="<?= esc($filter['tanggal_sewa']) ?>">
                <input type="hidden" name="pos_masuk" value="<?= esc($filter['pos_masuk']) ?>">
                <input type="hidden" name="pos_keluar" value="<?= esc($filter['pos_keluar']) ?>">
                <input type="hidden" name="cart_items" id="cart-items-input-mobile" value="[]">

                <button 
                    type="button"
                    onclick="handleCheckout()"
                    class="w-full bg-[#2D5A27] text-white font-black text-xs py-3.5 rounded-2xl shadow-md text-center select-none">
                    Lanjut Bayar
                </button>
            </form>
        </div>
    </div>

    <!-- CORE INTERACTION JAVASCRIPT (CART ENGINE) -->
    <script>
        // Array Penampung Cart
        let cart = [];

        // Format Rupiah
        function formatCurrency(value) {
            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Tambahkan Item Ke Keranjang Belanja
        function addToCart(id, name, price, stock, emoji) {
            const existing = cart.find(item => item.id === id);
            
            if (existing) {
                if (existing.qty < stock) {
                    existing.qty += 1;
                } else {
                    alert(`Maaf, Anda tidak dapat menyewa lebih dari stok yang tersedia (${stock} Pcs)`);
                    return;
                }
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    qty: 1,
                    stock: stock,
                    emoji: emoji
                });
            }

            renderControls(id);
            syncCartState();
        }

        // Kurangi Kuantitas
        function decreaseQty(id) {
            const item = cart.find(item => item.id === id);
            if (!item) return;

            item.qty -= 1;
            if (item.qty <= 0) {
                // Hapus dari cart
                cart = cart.filter(x => x.id !== id);
                resetControlToButton(id);
            } else {
                renderControls(id);
            }
            syncCartState();
        }

        // Sinkronisasi DOM Kontrol inline pada Card
        function renderControls(id) {
            const item = cart.find(x => x.id === id);
            if (!item) return;

            const container = document.getElementById(`control-${id}`);
            if (!container) return;

            container.innerHTML = `
                <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-2xl px-2 py-1 shadow-sm select-none">
                    <button 
                        type="button" 
                        onclick="decreaseQty('${id}')" 
                        class="w-8 h-8 rounded-xl bg-white hover:bg-slate-100 flex items-center justify-center font-bold text-slate-700 border border-slate-200 transition">
                        –
                    </button>
                    <span class="text-sm font-black text-slate-900 w-6 text-center select-none">${item.qty}</span>
                    <button 
                        type="button" 
                        onclick="addToCart('${id}', '${item.name}', ${item.price}, ${item.stock}, '${item.emoji}')" 
                        class="w-8 h-8 rounded-xl bg-white hover:bg-slate-100 flex items-center justify-center font-bold text-slate-700 border border-slate-200 transition">
                        +
                    </button>
                </div>
            `;
        }

        // Kembalikan Tombol Awal (TAMBAH)
        function resetControlToButton(id) {
            const container = document.getElementById(`control-${id}`);
            if (!container) return;

            // Kita butuh mengambil data awal dari attribute HTML jika bisa, 
            // namun karena dideklarasikan statis, kita bisa build tombol resetnya dengan cerdas.
            // Solusi aman: memicu reload state halaman atau menulis ulang HTML aslinya
            const cardEl = container.closest('.bg-white');
            const name = cardEl.querySelector('h4').innerText;
            const rawPrice = cardEl.querySelector('.text-base').innerText.replace(/[^0-9]/g, '');
            const price = parseInt(rawPrice);
            const rawStock = cardEl.querySelector('.fa-warehouse').nextSibling.nodeValue.replace(/[^0-9]/g, '');
            const stock = parseInt(rawStock);
            const emoji = cardEl.querySelector('span.relative').innerText;

            container.innerHTML = `
                <button 
                    type="button" 
                    onclick="addToCart('${id}', '${name}', ${price}, ${stock}, '${emoji}')"
                    class="bg-forest hover:bg-emerald-800 text-white text-xs font-black tracking-wider px-5 py-3 rounded-2xl shadow-md hover:shadow-lg transition duration-200">
                    <i class="fa-solid fa-plus mr-1"></i> TAMBAH
                </button>
            `;
        }

        // Sinkronisasi dan render ulang visual Keranjang Belanja
        function syncCartState() {
            // Hitung Total Kuantitas dan Harga
            const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

            // Update Badge Angka
            document.getElementById('cart-badge-desktop').innerText = totalItems;
            document.getElementById('cart-badge-mobile').innerText = totalItems;

            // Update Total Tagihan
            const formattedTotal = formatCurrency(totalPrice);
            document.getElementById('cart-total-desktop').innerText = formattedTotal;
            document.getElementById('cart-total-mobile').innerText = formattedTotal;

            // Update Hidden Inputs di Form
            const jsonString = JSON.stringify(cart);
            document.getElementById('cart-items-input-desktop').value = jsonString;
            document.getElementById('cart-items-input-mobile').value = jsonString;

            // RENDER LIST DESKTOP
            const listDesktop = document.getElementById('cart-list-desktop');
            if (cart.length === 0) {
                listDesktop.innerHTML = `
                    <div class="rounded-3xl border-2 border-dashed border-slate-200 p-8 text-center text-slate-400 select-none">
                        <i class="fa-solid fa-cart-shopping text-3xl mb-2 text-slate-300"></i>
                        <p class="text-xs leading-normal">Belum ada alat camping yang dipilih.</p>
                    </div>
                `;
            } else {
                listDesktop.innerHTML = cart.map(item => `
                    <div class="flex items-center gap-3.5 bg-slate-50 border border-slate-100 p-3.5 rounded-2xl relative group">
                        <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-2xl shadow-sm select-none">
                            ${item.emoji}
                        </div>
                        <div class="flex-grow select-none">
                            <h5 class="text-xs font-black text-slate-900 leading-tight pr-5">${item.name}</h5>
                            <p class="text-[10px] text-slate-400 mt-1">${item.qty} Pcs x ${formatCurrency(item.price)}</p>
                        </div>
                        <button 
                            type="button" 
                            onclick="removeItemDirectly('${item.id}')"
                            class="absolute top-3 right-3 text-slate-300 hover:text-rose-500 transition">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                `).join('');
            }

            // RENDER LIST MOBILE
            const listMobile = document.getElementById('cart-list-mobile');
            if (cart.length === 0) {
                listMobile.innerHTML = `<div class="text-slate-400 text-center py-4 text-xs select-none">Keranjang kosong.</div>`;
            } else {
                listMobile.innerHTML = cart.map(item => `
                    <div class="flex items-center justify-between gap-3 bg-slate-50 border border-slate-100 p-3 rounded-2xl select-none">
                        <div class="flex items-center gap-2.5">
                            <span class="text-2xl">${item.emoji}</span>
                            <div>
                                <h5 class="text-xs font-black text-slate-900 leading-tight">${item.name}</h5>
                                <p class="text-[9px] text-slate-400 mt-0.5">${item.qty} Pcs x ${formatCurrency(item.price)}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="decreaseQty('${item.id}')" class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-sm">–</button>
                            <span class="text-xs font-black text-slate-900 w-4 text-center">${item.qty}</span>
                            <button type="button" onclick="addToCart('${item.id}', '${item.name}', ${item.price}, ${item.stock}, '${item.emoji}')" class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-sm">+</button>
                        </div>
                    </div>
                `).join('');
            }
        }

        // Hapus item secara instan dari tombol sampah di Keranjang
        function removeItemDirectly(id) {
            cart = cart.filter(x => x.id !== id);
            resetControlToButton(id);
            syncCartState();
        }

        // Toggle Laci Keranjang untuk Mobile (HP)
        let isMobileDrawerOpen = false;
        function toggleMobileCartDrawer() {
            const drawer = document.getElementById('mobile-cart-drawer');
            const toggleText = document.getElementById('toggle-drawer-text');
            
            isMobileDrawerOpen = !isMobileDrawerOpen;
            if (isMobileDrawerOpen) {
                drawer.classList.remove('hidden');
                toggleText.innerText = 'Tutup Detail';
            } else {
                drawer.classList.add('hidden');
                toggleText.innerText = 'Lihat Detail';
            }
        }

        // Buka modal input nama penyewa sebelum checkout
        function handleCheckout() {
            if (cart.length === 0) {
                alert('Keranjang belanja Anda masih kosong! Silakan tambahkan peralatan camping terlebih dahulu.');
                return;
            }

            // Bersihkan input modal
            document.getElementById('modal-nama-penyewa').value = '';
            
            // Tampilkan modal
            const modal = document.getElementById('nama-penyewa-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Tutup modal nama penyewa
        function closeNamaPenyewaModal() {
            const modal = document.getElementById('nama-penyewa-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Kirim checkout data via AJAX/Fetch POST dan trigger Snap Midtrans
        function submitCheckout() {
            const namaPenyewa = document.getElementById('modal-nama-penyewa').value.trim();
            if (!namaPenyewa) {
                alert('Silakan masukkan nama lengkap penyewa!');
                return;
            }

            // Tutup modal agar tidak menutupi pop-up Midtrans
            closeNamaPenyewaModal();

            // Persiapkan FormData untuk POST payload
            const formData = new FormData();
            formData.append('nama_penyewa', namaPenyewa);
            formData.append('cart_items', JSON.stringify(cart));
            // Tambahkan CSRF Token
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            // Jalankan Fetch Request ke controller sewa-alat/proses-bayar
            fetch('<?= base_url('sewa-alat/proses-bayar') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Pemicu Pop-Up Midtrans Sandbox Snap asli
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            // Redirect ke halaman sukses sewa alat
                            window.location.href = '<?= base_url('sewa-alat/sukses') ?>/' + data.id_order;
                        },
                        onPending: function(result) {
                            alert('Pembayaran Anda tertunda. Silakan selesaikan pembayaran.');
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal atau dibatalkan.');
                            window.location.reload();
                        },
                        onClose: function() {
                            alert('Anda menutup halaman pembayaran sebelum selesai.');
                        }
                    });
                } else {
                    alert('Gagal membuat transaksi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi saat memproses checkout.');
            });
        }
    </script>

    <!-- MODAL NAMA PENYEWA (POP-UP PREMIUM) -->
    <div id="nama-penyewa-modal" class="fixed inset-0 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 z-50 transition-all duration-300 select-none">
        <div class="w-full max-w-md rounded-[2.5rem] bg-white p-8 shadow-2xl relative border border-gray-100 flex flex-col space-y-6">
            <div class="space-y-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-forest">Sewa Alat Pendakian</span>
                <h3 class="text-xl font-extrabold text-slate-900 leading-tight">Konfirmasi Identitas</h3>
                <p class="text-xs text-slate-400">Silakan masukkan nama lengkap penyewa utama untuk didaftarkan pada bukti sewa resmi.</p>
            </div>
            
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Nama Lengkap Penyewa</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-user"></i></span>
                    <input type="text" id="modal-nama-penyewa" placeholder="Contoh: Faradita Nuraini" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-4 text-sm text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10 transition">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeNamaPenyewaModal()" class="flex-1 rounded-2xl border border-slate-200 py-3.5 text-xs font-bold text-slate-500 hover:bg-slate-50 transition active:scale-[0.98]">
                    Batal
                </button>
                <button type="button" onclick="submitCheckout()" class="flex-1 rounded-2xl bg-forest hover:bg-emerald-800 py-3.5 text-xs font-bold text-white shadow-md transition active:scale-[0.98]">
                    Konfirmasi & Bayar
                </button>
            </div>
        </div>
    </div>

</body>
</html>
