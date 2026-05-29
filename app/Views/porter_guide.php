<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $pageTitle = isset($gunung) ? 'Porter & Guide - ' . $gunung['NAMA_GUNUNG'] : 'Porter & Guide'; ?>
    <title><?= $pageTitle ?></title>
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
</head>
<body class="bg-[#f7faf6] min-h-screen pb-10">

    <header class="bg-[#2D5A27] text-white p-5 sticky top-0 z-50 shadow-lg">
        <div class="max-w-screen-md mx-auto flex items-center gap-4">
            <a href="javascript:history.back()" class="p-2 rounded-full bg-white/10 hover:bg-white/20 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-xs uppercase tracking-[0.35em] opacity-80">Porter & Guide</p>
                <h1 class="text-xl font-black leading-snug"><?= isset($gunung) ? $gunung['NAMA_GUNUNG'] : 'Pilih Gunung' ?></h1>
                <p class="text-sm text-slate-200"><?= isset($gunung) ? $gunung['LOKASI'] : 'Pilih gunung terlebih dahulu untuk lanjut' ?></p>
            </div>
        </div>
    </header>

    <main class="max-w-screen-md mx-auto px-5 pt-6 space-y-6">
        <?php if (! isset($gunung)) : ?>
            <section class="bg-white rounded-[2rem] p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-xs uppercase font-bold tracking-[0.35em] text-green-700">Porter & Guide</p>
                        <h2 class="text-2xl font-black text-slate-900">Pilih Gunung</h2>
                        <p class="text-sm text-slate-600 mt-1">Gunung yang tersedia sama dengan data di halaman destinasi. Bukit tidak ditampilkan.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700">Gunung saja</span>
                </div>

                <div class="grid gap-4">
                    <?php foreach ($daftar_gunung as $g) : ?>
                        <?php if (isset($g['KATEGORI']) && strtolower($g['KATEGORI']) == 'bukit') continue; ?>
                        <a href="<?= base_url('porter-guide/' . $g['ID_GUNUNG']) ?>" class="block overflow-hidden rounded-[2rem] border border-gray-200 bg-slate-50 shadow-sm hover:border-forest hover:shadow-lg transition">
                            <div class="relative overflow-hidden aspect-[4/2]">
                                <img src="<?= base_url('uploads/' . $g['GAMBAR']) ?>"
                                     class="w-full h-full object-cover"
                                     alt="<?= $g['NAMA_GUNUNG'] ?>"
                                     onerror="this.src='<?= base_url('uploads/placeholder.jpg') ?>';">
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-slate-900 uppercase"><?= $g['NAMA_GUNUNG'] ?></h3>
                                <p class="text-sm text-slate-500 mt-1"><?= $g['LOKASI'] ?></p>
                                <div class="mt-4 flex items-center justify-between text-xs uppercase tracking-[0.2em] text-slate-500">
                                    <span><?= $g['KATEGORI'] ?? 'Gunung' ?></span>
                                    <span><i class="fa-solid fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else : ?>
            <section class="bg-white rounded-[2rem] p-6 shadow-lg border border-gray-100">
                <div class="space-y-3">
                    <p class="text-xs uppercase font-bold tracking-[0.35em] text-green-700">Isi data layanan</p>
                    <h2 class="text-2xl font-black text-slate-900"><?= $gunung['NAMA_GUNUNG'] ?></h2>
                    <p class="text-sm text-slate-600">Lokasi: <?= $gunung['LOKASI'] ?></p>
                </div>

                <form class="space-y-5 mt-6" onsubmit="return false;">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Pos Perizinan Masuk</label>
                        <div class="relative">
                            <input type="text" id="posEntry" placeholder="Ex: Jalur Senaru" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                            <i class="fa-solid fa-magnifying-glass absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Pos Perizinan Keluar</label>
                        <div class="relative">
                            <input type="text" id="posExit" placeholder="Ex: Jalur Sembalun" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                            <i class="fa-solid fa-map-pin absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Tipe Layanan</label>
                        <div class="relative">
                            <input type="text" id="serviceType" placeholder="Ex: Guide" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                            <i class="fa-solid fa-user-tie absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-800 uppercase">Tanggal Masuk</label>
                            <input type="date" id="dateEntry" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-800 uppercase">Tanggal Keluar</label>
                            <input type="date" id="dateExit" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Jumlah Pesanan</label>
                        <input type="number" id="quantity" min="1" placeholder="Ex: 2 porter" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                    </div>
                </form>
            </section>

            <section class="grid gap-4">
                <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-gray-100 flex flex-col gap-4 md:flex-row md:items-center">
                    <div class="min-w-[42px] min-h-[42px] rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800">Butuh Bantuan?</p>
                        <p class="text-sm text-slate-500">Chat admin lewat WhatsApp jika mau tanya-tanya soal porter atau guide.</p>
                    </div>
                    <a href="https://wa.me/6281234567890" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-black text-white shadow hover:bg-emerald-700 transition">+62 812-3456-7890</a>
                </div>
            </section>

            <button type="button" class="w-full bg-forest text-white font-black py-4 rounded-3xl shadow-xl hover:bg-[#1f3f1d] active:shadow-lg transition-all cursor-pointer" onclick="openOrderModal()">Pesan Sekarang</button>
        <?php endif; ?>
    </main>

    <!-- Modal Detail Pesanan -->
    <div id="orderModal" class="hidden fixed inset-0 bg-black/50 z-50 overflow-y-auto">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-b from-slate-100 to-slate-50 p-6 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-black text-slate-900">PEMBAYARAN</h2>
                    <button onclick="closeOrderModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Konten Modal -->
                <div class="p-6 space-y-6 overflow-y-auto max-h-[calc(100vh-200px)]">
                    <!-- QRIS Section -->
                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-3xl p-6 text-center border border-slate-200">
                        <div class="inline-flex items-center justify-center w-48 h-48 bg-white rounded-2xl border-4 border-dashed border-slate-300 mb-4">
                            <div class="text-center">
                                <i class="fa-solid fa-qrcode text-5xl text-slate-300 block mb-2"></i>
                                <p class="text-sm text-slate-400">QRIS Placeholder</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 mt-4">QRIS placeholder. Scan dengan aplikasimu untuk menyelesaikan pembayaran.</p>
                        <p class="text-xl font-black text-slate-900 mt-3">Total <span id="modalTotal" class="text-forest">Rp 0</span></p>
                    </div>

                    <!-- Nama Pemesan -->
                    <div>
                        <p class="text-sm font-bold text-slate-600 uppercase tracking-wide">Nama Pemesan</p>
                        <div class="mt-2">
                            <input type="text" id="customerName" placeholder="Nama Anda" class="w-full rounded-2xl border border-gray-200 bg-slate-50 px-4 py-3 text-lg font-black text-slate-900 outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                        </div>
                    </div>

                    <!-- Detail Pesanan -->
                    <div class="space-y-3 bg-slate-50 rounded-2xl p-4 border border-slate-200">
                        <p class="text-sm font-bold text-slate-600 uppercase tracking-wide">Detail Pesanan</p>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex items-start gap-2">
                                <span class="text-slate-500 font-semibold min-w-[120px]">Gunung:</span>
                                <span class="text-slate-800 font-medium" id="modalMountain"><?= isset($gunung) ? $gunung['NAMA_GUNUNG'] : '-' ?></span>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-slate-500 font-semibold min-w-[120px]">Tanggal:</span>
                                <div class="text-slate-800 font-medium">
                                    <p id="modalDateRange">-</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-slate-500 font-semibold min-w-[120px]">Pos Masuk:</span>
                                <span class="text-slate-800 font-medium" id="modalPosEntry">-</span>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-slate-500 font-semibold min-w-[120px]">Pos Keluar:</span>
                                <span class="text-slate-800 font-medium" id="modalPosExit">-</span>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-slate-500 font-semibold min-w-[120px]">Tipe Layanan:</span>
                                <span class="text-slate-800 font-medium" id="modalServiceType">-</span>
                            </div>
                            
                            <div class="flex items-start gap-2">
                                <span class="text-slate-500 font-semibold min-w-[120px]">Jumlah Pesanan:</span>
                                <span class="text-slate-800 font-medium" id="modalQuantity">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Button -->
                <div class="bg-slate-50 border-t border-gray-200 p-6">
                    <button onclick="processPayment()" class="w-full bg-forest text-white font-black py-3 rounded-2xl shadow-lg hover:bg-[#1f3f1d] transition-all">
                        Lanjut Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka modal
        function openOrderModal() {
            // Ambil data dari form menggunakan ID
            const posEntry = document.getElementById('posEntry')?.value || '';
            const posExit = document.getElementById('posExit')?.value || '';
            const serviceType = document.getElementById('serviceType')?.value || '';
            const dateEntry = document.getElementById('dateEntry')?.value || '';
            const dateExit = document.getElementById('dateExit')?.value || '';
            const quantity = document.getElementById('quantity')?.value || '';

            // Validasi form
            if (!posEntry || !posExit || !serviceType || !dateEntry || !dateExit || !quantity) {
                alert('Mohon isi semua data terlebih dahulu');
                return;
            }

            // Update data di modal
            document.getElementById('modalPosEntry').textContent = posEntry;
            document.getElementById('modalPosExit').textContent = posExit;
            document.getElementById('modalServiceType').textContent = serviceType;
            document.getElementById('modalQuantity').textContent = quantity + ' pesanan';
            
            // Format tanggal
            const dateRangeText = dateEntry + ' sampai ' + dateExit;
            document.getElementById('modalDateRange').textContent = dateRangeText;

            // Hitung total (example: Rp 8.000 per pesanan per hari)
            const date1 = new Date(dateEntry);
            const date2 = new Date(dateExit);
            const daysDiff = Math.ceil((date2 - date1) / (1000 * 60 * 60 * 24)) + 1;
            const pricePerDay = 8000;
            const total = quantity * daysDiff * pricePerDay;
            
            document.getElementById('modalTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');

            // Tampilkan modal
            document.getElementById('orderModal').classList.remove('hidden');
        }

        // Fungsi untuk menutup modal
        function closeOrderModal() {
            document.getElementById('orderModal').classList.add('hidden');
        }

        // Fungsi untuk proses pembayaran
        function processPayment() {
            const customerName = document.getElementById('customerName').value;
            
            if (!customerName) {
                alert('Mohon masukkan nama Anda terlebih dahulu');
                return;
            }

            alert('Pembayaran untuk ' + customerName + ' sedang diproses...');
            // Di sini bisa ditambahkan logika untuk mengirim data ke backend
        }

        // Tutup modal ketika klik di luar
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('orderModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeOrderModal();
                    }
                });
            }
        });
    </script>

</body>
</html>
