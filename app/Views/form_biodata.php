<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata & Review - Pendaki.id</title>
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfaf7; }
    </style>
    <!-- INCLUDE SNAP.JS DARI MIDTRANS SANDBOX DENGAN KEY DARI ENV -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= env('midtrans.clientKey') ?? 'Mid-client-5DWPhj0CBsPVQy8Q' ?>"></script>
</head>
<body class="bg-gray-50 pb-20">

    <!-- Header / Nav -->
    <nav class="bg-white sticky top-0 z-[100] border-b shadow-sm">
        <!-- Menggunakan max-w-5xl agar menyelaraskan dengan konten di laptop landscape -->
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="javascript:history.back()" class="text-gray-600 hover:text-forest transition-colors">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Review & Pembayaran</h1>
            </div>
            <i class="fa-solid fa-shield-halved text-forest text-xl"></i>
        </div>
    </nav>

    <!-- CONTAINER UTAMA: Diubah max-w-5xl agar tidak sempit di desktop landscape -->
    <div class="max-w-5xl mx-auto px-6 mt-8 space-y-6">

        <!-- Notifikasi Error/Pemberitahuan (Full-width di atas kolom) -->
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm flex items-center gap-2 shadow-sm w-full">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <span class="font-semibold"><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- PARENT CONTAINER KEDUA CARD:
             - flex-col di mobile (Vertikal)
             - md:flex-row di laptop (Membelah Menjadi Kiri & Kanan) -->
        <div class="flex flex-col md:flex-row gap-8 items-start w-full">

            <!-- KARTU HIJAU: REVIEW PESANAN (W-full di mobile, w-1/2 di laptop) -->
            <div class="w-full md:w-1/2 bg-gradient-to-br from-forest to-green-800 text-white rounded-[2.5rem] p-8 shadow-xl space-y-6 transition-all duration-300">
                <div class="flex justify-between items-center pb-4 border-b border-white/20">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-green-200">Destinasi</span>
                        <h3 class="text-xl font-black uppercase tracking-tight"><?= esc($gunung['NAMA_GUNUNG']) ?></h3>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-mountain"></i> <?= esc($gunung['LOKASI']) ?>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 text-sm pt-2">
                    <div class="space-y-1">
                        <span class="text-[10px] text-green-200 font-bold uppercase block tracking-wider">Tanggal Masuk</span>
                        <span class="font-bold text-base"><i class="fa-regular fa-calendar mr-1.5 text-xs text-green-300"></i> <?= date('d M Y', strtotime($tahap1['tanggal_masuk'])) ?></span>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] text-green-200 font-bold uppercase block tracking-wider">Tanggal Keluar</span>
                        <span class="font-bold text-base"><i class="fa-regular fa-calendar mr-1.5 text-xs text-green-300"></i> <?= date('d M Y', strtotime($tahap1['tanggal_keluar'])) ?></span>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] text-green-200 font-bold uppercase block tracking-wider">Pos Perizinan</span>
                        <span class="font-bold text-base"><i class="fa-solid fa-map-pin mr-1.5 text-xs text-green-300"></i> <?= esc($tahap1['pos_masuk']) ?></span>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] text-green-200 font-bold uppercase block tracking-wider">Jumlah Pendaki</span>
                        <span class="font-bold text-base"><i class="fa-solid fa-users mr-1.5 text-xs text-green-300"></i> <?= esc($tahap1['jumlah_pemesan']) ?> Orang</span>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/20 flex justify-between items-end">
                    <div class="space-y-1">
                        <span class="text-[10px] text-green-200 font-bold uppercase tracking-wider block">Total Tagihan</span>
                        <span class="text-3xl font-black text-yellow-300">Rp <?= number_format($total_bayar, 0, ',', '.') ?></span>
                    </div>
                    <span class="bg-yellow-400 text-slate-900 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-md">
                        Review Pemesanan
                    </span>
                </div>
            </div>

            <!-- KARTU PUTIH: FORM BIODATA (W-full di mobile, w-1/2 di laptop) -->
            <div class="w-full md:w-1/2 bg-white p-8 rounded-[2.5rem] shadow-md border border-gray-100 space-y-6 transition-all duration-300">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-gray-800">Biodata Pendaki Utama</h3>
                    <p class="text-xs text-gray-400 font-medium">Mohon isi data di bawah dengan benar demi keselamatan pendakian</p>
                </div>

                <form action="<?= base_url('tiket/proses_bayar') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-wider block pl-2">Nama Lengkap</label>
                        <div class="flex items-center bg-gray-50 px-4 py-3.5 rounded-2xl gap-3 border border-gray-200 focus-within:border-forest/30 focus-within:bg-white transition-all">
                            <i class="fa-solid fa-signature text-gray-400"></i>
                            <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap', session()->get('username')) ?>" placeholder="Masukkan nama lengkap Anda" required class="bg-transparent text-sm font-bold outline-none w-full text-gray-700">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-wider block pl-2">Alamat Lengkap</label>
                        <div class="flex items-start bg-gray-50 px-4 py-3.5 rounded-2xl gap-3 border border-gray-200 focus-within:border-forest/30 focus-within:bg-white transition-all">
                            <i class="fa-solid fa-map-location-dot text-gray-400 mt-1"></i>
                            <textarea name="alamat" rows="2" placeholder="Masukkan alamat lengkap tinggal Anda saat ini" required class="bg-transparent text-sm font-bold outline-none w-full text-gray-700 resize-none"><?= old('alamat') ?></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-wider block pl-2">Nomor Telepon</label>
                            <div class="flex items-center bg-gray-50 px-4 py-3.5 rounded-2xl gap-2 border border-gray-200 focus-within:border-forest/30 focus-within:bg-white transition-all">
                                <i class="fa-solid fa-phone text-gray-400"></i>
                                <input type="tel" name="no_telp" value="<?= old('no_telp') ?>" placeholder="08xxx" required class="bg-transparent text-xs font-bold outline-none w-full text-gray-700">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-wider block pl-2">Kontak Darurat</label>
                            <div class="flex items-center bg-gray-50 px-4 py-3.5 rounded-2xl gap-2 border border-gray-200 focus-within:border-forest/30 focus-within:bg-white transition-all">
                                <i class="fa-solid fa-kit-medical text-red-500"></i>
                                <input type="tel" name="no_darurat" value="<?= old('no_darurat') ?>" placeholder="Kerabat/Ortu" required class="bg-transparent text-xs font-bold outline-none w-full text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- Info Keamanan Sandbox -->
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-2xl text-[11px] font-semibold space-y-1.5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-flask text-yellow-600 text-sm"></i>
                            <span class="uppercase tracking-wide">Midtrans Sandbox Active</span>
                        </div>
                        <p class="text-gray-600 font-medium">Pembayaran akan menggunakan mode simulasi Sandbox. Anda dapat menggunakan detail VA / QRIS testing yang disediakan Midtrans tanpa uang asli.</p>
                    </div>

                    <button type="submit" class="w-full bg-forest hover:bg-green-800 text-white py-4 rounded-2xl text-sm font-bold shadow-lg shadow-green-100 hover:shadow-green-200 transition-all active:scale-[0.98] mt-2 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-wallet"></i> Bayar Sekarang
                    </button>
                </form>
            </div>

        </div>

    </div>

    <!-- SCRIPT JAVASCRIPT INTEGRASI MIDTRANS SNAP POP-UP -->
    <?php if (!empty($snapToken)) : ?>
        <script type="text/javascript">
            window.onload = function() {
                snap.pay('<?= $snapToken ?>', {
                    onSuccess: function(result) {
                        // Jika pembayaran sukses, alihkan secara otomatis ke halaman sukses
                        window.location.href = "<?= base_url('tiket/success/' . $idTransaksi) ?>";
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran Anda! Silakan selesaikan transaksinya.");
                    },
                    onError: function(result) {
                        alert("Pembayaran terdeteksi gagal, silakan ulangi kembali.");
                    },
                    onClose: function() {
                        alert("Anda menutup halaman pembayaran sebelum menyelesaikan transaksi.");
                    }
                });
            };
        </script>
    <?php endif; ?>

</body>
</html>
