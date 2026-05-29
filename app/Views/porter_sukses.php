<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Sukses - Porter & Guide - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { forest: '#2d5a27' }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f6f5f2; }
    </style>
</head>
<!-- CONTAINER UTAMA: min-h-screen, flex, items-center, justify-center untuk Center Vertikal & Horizontal penuh di Laptop -->
<body class="min-h-screen bg-[#f6f5f2] flex items-center justify-center p-4 md:p-8">

    <!-- CARD PORTER RESPONSIVE:
         - Mobile: w-full max-w-md (Vertikal)
         - Desktop (md ke atas): md:max-w-4xl flex-row (Horizontal / Dua Kolom Menyamping) -->
    <div class="w-full max-w-md md:max-w-4xl bg-white rounded-[2.5rem] shadow-2xl border border-gray-100 overflow-hidden relative flex flex-col md:flex-row my-6 md:my-10 transition-all duration-300">
        
        <!-- KOLOM KIRI (Header Hijau & Barcode) -->
        <div class="w-full md:w-1/2 flex flex-col border-b md:border-b-0 md:border-r border-gray-50">
            <!-- Header Hijau -->
            <div class="bg-gradient-to-br from-forest to-green-800 text-white p-8 text-center space-y-4 flex flex-col justify-center items-center md:h-1/3 flex-none">
                <div class="bg-white/10 backdrop-blur-md w-12 h-12 rounded-full flex items-center justify-center mx-auto shadow-md">
                    <i class="fa-solid fa-circle-check text-green-300 text-2xl"></i>
                </div>
                <div class="space-y-1">
                    <span class="text-[9px] font-black uppercase tracking-widest text-green-300 block">Pemesanan Porter & Guide Sukses</span>
                    <h2 class="text-lg font-extrabold uppercase tracking-tight leading-tight"><?= esc($gunung['NAMA_GUNUNG']) ?></h2>
                </div>
                <div class="bg-black/20 rounded-full inline-block px-4 py-1.5 text-xs font-bold shadow-sm">
                    Kode Pesanan: <span class="text-yellow-300"><?= esc($ticket_code) ?></span>
                </div>
            </div>
            
            <!-- Barcode & QR Code Section -->
            <div class="p-8 flex flex-col items-center justify-center text-center space-y-4 bg-white flex-1">
                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Scan Barcode Ini di Pos Porter</span>
                
                <!-- QR CODE BOX -->
                <div class="p-4 bg-white border border-gray-100 rounded-3xl shadow-md relative group hover:scale-[1.02] transition-all duration-300">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?= urlencode($barcode_data) ?>" 
                     alt="Pesanan QR Code" 
                     class="w-40 h-40 md:w-48 md:h-48 rounded-xl object-contain"
                     loading="lazy">
                </div>
                
                <p class="text-[10px] text-gray-400 font-semibold italic max-w-xs mx-auto">Tunjukkan bukti pemesanan porter & guide ini kepada koordinator porter di basecamp utama.</p>
            </div>
        </div>

        <!-- SEPARATOR RASIONAL TEAR (HORIZONTAL DI MOBILE, VERTIKAL DI DESKTOP) -->
        <!-- Mobile Tear Separator -->
        <div class="md:hidden relative h-6 bg-white flex items-center justify-between px-1">
            <div class="absolute -left-3 w-6 h-6 bg-[#f6f5f2] rounded-full border-r border-gray-100"></div>
            <div class="w-full border-t-2 border-dashed border-gray-200"></div>
            <div class="absolute -right-3 w-6 h-6 bg-[#f6f5f2] rounded-full border-l border-gray-100"></div>
        </div>
        <!-- Desktop Tear Separator (Vertikal yang sangat estetik) -->
        <div class="hidden md:flex relative w-6 bg-white items-center justify-center py-1 flex-none">
            <div class="absolute -top-3 w-6 h-6 bg-[#f6f5f2] rounded-full border-b border-gray-100"></div>
            <div class="h-full border-l-2 border-dashed border-gray-200"></div>
            <div class="absolute -bottom-3 w-6 h-6 bg-[#f6f5f2] rounded-full border-t border-gray-100"></div>
        </div>

        <!-- KOLOM KANAN (Tabel Detail & Tombol Aksi) -->
        <div class="w-full md:w-1/2 bg-gray-50 p-8 flex flex-col justify-between flex-1">
            <div class="space-y-6">
                <!-- Header khusus desktop -->
                <div class="hidden md:block">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-wider">Detail Layanan Porter & Guide</h3>
                    <div class="w-12 h-1 bg-forest mt-1.5 rounded-full"></div>
                </div>

                <!-- Tabel Detail Pesanan -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 text-xs space-y-3 shadow-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold uppercase tracking-wider">Nama Pemesan</span>
                        <span class="font-extrabold text-gray-800 text-right"><?= esc($pesanan['NAMA_PEMESAN']) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold uppercase tracking-wider">Tipe Layanan</span>
                        <span class="font-extrabold text-gray-800 text-right uppercase"><?= esc($pesanan['TIPE_LAYANAN']) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold uppercase tracking-wider">Jumlah Pesanan</span>
                        <span class="font-extrabold text-gray-800 text-right"><?= esc($pesanan['JML_PESANAN']) ?> Layanan</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold uppercase tracking-wider">Pos Masuk / Keluar</span>
                        <span class="font-extrabold text-gray-800 text-right"><?= esc($pesanan['POS_MASUK']) ?> / <?= esc($pesanan['POS_KELUAR']) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-bold uppercase tracking-wider">Tanggal Layanan</span>
                        <span class="font-extrabold text-gray-800 text-right"><?= date('d M Y', strtotime($pesanan['TGL_MASUK'])) ?> - <?= date('d M Y', strtotime($pesanan['TGL_KELUAR'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center pt-2.5 border-t border-gray-200">
                        <span class="text-gray-400 font-bold uppercase tracking-wider">Status Pembayaran</span>
                        <span class="font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg uppercase tracking-wide text-[10px]">
                            <i class="fa-solid fa-circle-check mr-0.5"></i> Sudah Bayar
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi (Cetak & Ke Beranda) -->
            <div class="flex gap-4 mt-8">
                <button onclick="window.print()" class="flex-1 bg-white hover:bg-gray-100 text-gray-700 font-bold py-3.5 px-4 rounded-2xl text-xs shadow-sm border border-gray-200 transition-all flex items-center justify-center gap-1.5 active:scale-[0.98]">
                    <i class="fa-solid fa-print"></i> Cetak Bukti
                </button>
                <a href="<?= base_url('/') ?>" class="flex-1 bg-forest hover:bg-green-800 text-white font-bold py-3.5 px-4 rounded-2xl text-xs shadow-md shadow-green-100 text-center transition-all flex items-center justify-center gap-1.5 active:scale-[0.98]">
                    <i class="fa-solid fa-house"></i> Ke Beranda
                </a>
            </div>
        </div>

    </div>

    <!-- FontAwesome load fallback -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" async></script>
</body>
</html>
