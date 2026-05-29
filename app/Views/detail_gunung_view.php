<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking <?= $gunung['NAMA_GUNUNG'] ?></title>
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
<body class="bg-white pb-10">

    <!-- Header Hijau sesuai image_557d54.png -->
    <header class="bg-[#2D5A27] text-white p-5 sticky top-0 z-50 flex items-center gap-4">
        <a href="javascript:history.back()"><i class="fa-solid fa-arrow-left text-lg"></i></a>
        <h1 class="font-bold text-base">Pendakian <?= $gunung['NAMA_GUNUNG'] ?></h1>
    </header>

    <div class="max-w-md mx-auto px-6 py-8 space-y-6">
        <!-- Judul & Info -->
        <div>
            <span class="text-xs text-gray-400 font-medium">Tiket Pendakian</span>
            <h2 class="text-2xl font-bold text-gray-800 mt-1"><?= $gunung['NAMA_GUNUNG'] ?></h2>
            <p class="text-sm text-gray-400 font-semibold uppercase tracking-wide"><?= $gunung['LOKASI'] ?></p>
        </div>

        <!-- Deskripsi -->
        <div class="space-y-2">
            <h3 class="font-bold text-gray-800">Deskripsi</h3>
            <p class="text-sm text-gray-600 leading-relaxed italic">
                "Nikmati pendakian seru di <?= $gunung['NAMA_GUNUNG'] ?> dengan pemandangan yang memanjakan mata."
            </p>
        </div>

        <div class="pt-4">
            <a href="<?= base_url('porter-guide/' . $gunung['ID_GUNUNG']) ?>" class="inline-flex items-center justify-center w-full rounded-3xl bg-[#2D5A27] px-5 py-4 text-sm font-black text-white shadow-lg hover:bg-[#203f1e] transition">Pesan Porter & Guide</a>
        </div>

        <!-- Alert Info -->
        <div class="bg-blue-100/50 border border-blue-200 p-4 rounded-xl flex items-center gap-4 text-blue-700">
            <div class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-[10px]">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <span class="text-xs font-bold uppercase tracking-tight">Satu Transaksi Maksimal 8 Orang</span>
        </div>

        <!-- Form Pemesanan -->
        <form action="<?= base_url('tiket/proses_tahap1') ?>" method="POST" class="space-y-5 pt-4">
            <?= csrf_field() ?>
            <input type="hidden" name="id_gunung" value="<?= $gunung['ID_GUNUNG'] ?>">

            <div class="space-y-2">
                <label class="text-sm font-black text-gray-700 uppercase">Pos Perizinan Masuk</label>
                <div class="relative">
                    <input type="text" name="pos_masuk" placeholder="Contoh: Sembalun, Ranu Pane, dll." required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm">
                    <i class="fa-solid fa-map-pin absolute right-4 top-4 text-gray-300"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-black text-gray-700 uppercase">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" id="tgl_mendaki" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm text-gray-700">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-black text-gray-700 uppercase">Tanggal Keluar</label>
                    <input type="date" name="tanggal_keluar" id="tgl_keluar" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm text-gray-700">
                </div>
            </div>

            <!-- Dynamic Live Quota Info Container -->
            <div id="info-kuota-container" class="hidden p-4 rounded-2xl text-xs font-bold transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div id="info-kuota-badge" class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 text-white bg-emerald-500">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="leading-relaxed">
                        <p class="font-extrabold uppercase tracking-wider text-[10px] text-gray-900">Informasi Kuota Real-time</p>
                        <p id="info-kuota-text" class="mt-0.5 font-medium"></p>
                    </div>
                </div>
            </div>

            <div class="space-y-2 pb-10">
                <label class="text-sm font-black text-gray-700 uppercase">Jumlah Pemesan</label>
                <input type="number" name="jumlah_pemesan" min="1" max="8" placeholder="Minimal Pemesan 1" required class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm">
            </div>

            <!-- Button Selanjutnya -->
            <button type="submit" class="w-full bg-[#8CAF88] hover:bg-[#2D5A27] text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-gray-200">
                Selanjutnya
            </button>
        </form>
    </div>

    <!-- SCRIPT JAVASCRIPT PEMBATASAN KALENDER & CEK KUOTA AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const minDate = `${yyyy}-${mm}-${dd}`;

            const maxDateObj = new Date();
            maxDateObj.setDate(today.getDate() + 30);
            const max_yyyy = maxDateObj.getFullYear();
            const max_mm = String(maxDateObj.getMonth() + 1).padStart(2, '0');
            const max_dd = String(maxDateObj.getDate()).padStart(2, '0');
            const maxDate = `${max_yyyy}-${max_mm}-${max_dd}`;

            const dateInput = document.getElementById('tgl_mendaki');
            const dateInputKeluar = document.getElementById('tgl_keluar');

            if (dateInput) {
                dateInput.min = minDate;
                dateInput.max = maxDate;
                
                dateInput.addEventListener('change', function() {
                    if (dateInputKeluar) {
                        dateInputKeluar.min = this.value;
                    }
                    
                    const idGunung = document.querySelector('input[name="id_gunung"]').value;
                    const tglMendaki = this.value;
                    
                    if (idGunung && tglMendaki) {
                        fetch(`<?= base_url('tiket/cek_kuota') ?>?id_gunung=${idGunung}&tgl_mendaki=${tglMendaki}`)
                            .then(response => response.json())
                            .then(data => {
                                const container = document.getElementById('info-kuota-container');
                                const badge = document.getElementById('info-kuota-badge');
                                const textEl = document.getElementById('info-kuota-text');
                                
                                if (data.success) {
                                    container.classList.remove('hidden');
                                    if (data.sisa_kuota <= 0) {
                                        container.className = 'bg-red-50 border border-red-200 p-4 rounded-2xl text-red-800 text-xs font-bold transition-all duration-300';
                                        badge.className = 'w-6 h-6 rounded-full flex items-center justify-center shrink-0 text-white bg-red-500';
                                        textEl.innerHTML = `Maaf, kuota untuk tanggal terpilih sudah <span class="font-extrabold text-red-950">HABIS</span> (Tersisa 0 dari ${data.kapasitas_max} kuota). Silakan reschedule tanggal pendakian Anda!`;
                                    } else {
                                        container.className = 'bg-emerald-50 border border-emerald-200 p-4 rounded-2xl text-emerald-800 text-xs font-bold transition-all duration-300';
                                        badge.className = 'w-6 h-6 rounded-full flex items-center justify-center shrink-0 text-white bg-emerald-500';
                                        textEl.innerHTML = `Kuota masih tersedia! Tersisa <span class="font-extrabold text-emerald-950">${data.sisa_kuota}</span> dari <span class="font-extrabold text-emerald-950">${data.kapasitas_max}</span> kuota total pada tanggal terpilih.`;
                                    }
                                }
                            })
                            .catch(err => console.error('Error fetching quota:', err));
                    }
                });
            }
        });
    </script>

</body>
</html>