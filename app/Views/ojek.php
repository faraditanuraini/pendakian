<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $pageTitle = isset($gunung) ? 'Ojek Gunung - ' . $gunung['NAMA_GUNUNG'] : 'Ojek Gunung'; ?>
    <title><?= $pageTitle ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: '#2D5A27',
                    }
                }
            }
        }
    </script>
    <!-- Midtrans Snap.js Sandbox -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= env('midtrans.clientKey') ?? 'Mid-client-5DWPhj0CBsPVQy8Q' ?>"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f7faf6] min-h-screen pb-10">

    <header class="bg-[#2D5A27] text-white p-5 sticky top-0 z-50 shadow-lg">
        <div class="max-w-screen-md mx-auto flex items-center gap-4">
            <a href="<?= isset($gunung) ? base_url('ojek') : base_url('/') ?>" class="p-2 rounded-full bg-white/10 hover:bg-white/20 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-xs uppercase tracking-[0.35em] opacity-80">Layanan Ojek</p>
                <h1 class="text-xl font-black leading-snug"><?= isset($gunung) ? esc($gunung['NAMA_GUNUNG']) : 'Pilih Destinasi' ?></h1>
                <p class="text-sm text-slate-200"><?= isset($gunung) ? esc($gunung['LOKASI']) : 'Pilih gunung untuk layanan ojek' ?></p>
            </div>
        </div>
    </header>

    <main class="max-w-screen-md mx-auto px-5 pt-6 space-y-6">
        <?php if (! isset($gunung)) : ?>
            <section class="bg-white rounded-[2rem] p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-xs uppercase font-bold tracking-[0.35em] text-orange-600">Ojek Gunung</p>
                        <h2 class="text-2xl font-black text-slate-900">Pilih Gunung</h2>
                        <p class="text-sm text-slate-600 mt-1">Layanan ojek (taksi motor) dari basecamp ke pos pendakian awal.</p>
                    </div>
                </div>

                <div class="grid gap-4">
                    <?php foreach ($daftar_gunung as $g) : ?>
                        <a href="<?= base_url('ojek/' . $g['ID_GUNUNG']) ?>" class="block overflow-hidden rounded-[2rem] border border-gray-200 bg-slate-50 shadow-sm hover:border-orange-500 hover:shadow-lg transition">
                            <div class="relative overflow-hidden aspect-[4/2]">
                                <img src="<?= base_url('uploads/' . $g['GAMBAR']) ?>" 
                                     class="w-full h-full object-cover" 
                                     alt="<?= esc($g['NAMA_GUNUNG']) ?>"
                                     onerror="this.src='<?= base_url('uploads/placeholder.jpg') ?>';">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 text-white">
                                    <h3 class="text-lg font-bold uppercase"><?= esc($g['NAMA_GUNUNG']) ?></h3>
                                    <p class="text-xs opacity-90"><?= esc($g['LOKASI']) ?></p>
                                </div>
                            </div>
                            <div class="p-4 flex items-center justify-between text-xs uppercase font-bold text-slate-500">
                                <span class="flex items-center gap-2"><i class="fa-solid fa-motorcycle text-orange-500 text-lg"></i> Cek Rute Ojek</span>
                                <span><i class="fa-solid fa-arrow-right"></i></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else : ?>
            <section class="bg-white rounded-[2rem] p-6 shadow-lg border border-gray-100">
                <div class="space-y-3">
                    <p class="text-xs uppercase font-bold tracking-[0.35em] text-orange-600">Form Pesanan</p>
                    <h2 class="text-2xl font-black text-slate-900"><?= esc($gunung['NAMA_GUNUNG']) ?></h2>
                    <p class="text-sm text-slate-600">Pastikan basecamp dan pos tujuan sudah benar.</p>
                </div>

                <form class="space-y-5 mt-6" onsubmit="event.preventDefault();">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Titik Jemput (Basecamp)</label>
                        <div class="relative">
                            <input type="text" id="titik-jemput" placeholder="Ex: Basecamp Sembalun" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" required>
                            <i class="fa-solid fa-map-pin absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Pos Tujuan (Drop Off)</label>
                        <div class="relative">
                            <input type="text" id="pos-tujuan" placeholder="Ex: Pos 1 Sembalun" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" required>
                            <i class="fa-solid fa-location-dot absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-800 uppercase">Tanggal Keberangkatan</label>
                            <input type="date" id="tgl-keberangkatan" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-800 uppercase">Jumlah Penumpang</label>
                            <input type="number" id="jml-penumpang" min="1" placeholder="Ex: 2 Orang" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" required>
                        </div>
                    </div>
                </form>
            </section>

            <!-- PRICING SUMMARY CARD (REAL-TIME ESTIMATION) -->
            <section id="pricing-summary-section" class="bg-orange-50 border border-orange-100 rounded-[2rem] p-6 shadow-sm flex flex-col gap-2 select-none">
                <div class="flex justify-between items-center text-slate-600 text-sm">
                    <span>Tarif per Penumpang/Motor</span>
                    <span class="font-bold">Rp 35.000</span>
                </div>
                <div class="flex justify-between items-center text-slate-600 text-sm">
                    <span>Jumlah Penumpang</span>
                    <span id="summary-penumpang" class="font-bold">0 Orang</span>
                </div>
                <hr class="border-orange-200 my-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-800 font-bold">Total Tarif Layanan</span>
                    <span id="summary-total-tarif" class="text-xl font-black text-slate-900">Rp 0</span>
                </div>
            </section>

            <button type="button" onclick="handleOjekCheckout()" class="w-full bg-forest text-white font-black py-4 rounded-3xl shadow-xl hover:bg-[#1f3f1d] transition-all flex justify-center items-center gap-2 active:scale-[0.99] select-none">
                <i class="fa-solid fa-motorcycle"></i> Pesan Ojek Sekarang
            </button>
        <?php endif; ?>
    </main>

    <!-- MODAL NAMA TEAM LEADER (POP-UP PREMIUM) -->
    <div id="team-leader-modal" class="fixed inset-0 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 z-[100] transition-all duration-300 select-none">
        <div class="w-full max-w-md rounded-[2.5rem] bg-white p-8 shadow-2xl relative border border-gray-100 flex flex-col space-y-6">
            <div class="space-y-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-forest">Pemesanan Ojek Gunung</span>
                <h3 class="text-xl font-extrabold text-slate-900 leading-tight">Konfirmasi Penanggung Jawab</h3>
                <p class="text-xs text-slate-400">Silakan masukkan nama lengkap penanggung jawab (Team Leader) rombongan Anda.</p>
            </div>
            
            <div class="space-y-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Nama Lengkap Team Leader</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-user-group"></i></span>
                    <input type="text" id="modal-nama-leader" placeholder="Contoh: Faradita Nuraini" class="w-full rounded-2xl border border-slate-200 pl-11 pr-4 py-4 text-sm text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10 transition">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeTeamLeaderModal()" class="flex-1 rounded-2xl border border-slate-200 py-3.5 text-xs font-bold text-slate-500 hover:bg-slate-50 transition active:scale-[0.98]">
                    Batal
                </button>
                <button type="button" onclick="submitOjekCheckout()" class="flex-1 rounded-2xl bg-forest hover:bg-emerald-800 py-3.5 text-xs font-bold text-white shadow-md transition active:scale-[0.98]">
                    Pesan & Bayar
                </button>
            </div>
        </div>
    </div>

    <!-- CORE JAVASCRIPT LOGIC -->
    <script>
        // Perhitungan Tarif Dinamis Real-Time
        const jmlPenumpangInput = document.getElementById('jml-penumpang');
        const summaryPenumpang = document.getElementById('summary-penumpang');
        const summaryTotalTarif = document.getElementById('summary-total-tarif');

        if (jmlPenumpangInput) {
            jmlPenumpangInput.addEventListener('input', function() {
                const qty = Math.max(0, parseInt(this.value) || 0);
                summaryPenumpang.innerText = qty + ' Orang';
                summaryTotalTarif.innerText = 'Rp ' + (qty * 35000).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            });
        }

        // Buka modal input nama Team Leader
        function handleOjekCheckout() {
            const titikJemput = document.getElementById('titik-jemput').value.trim();
            const posTujuan = document.getElementById('pos-tujuan').value.trim();
            const tglKeberangkatan = document.getElementById('tgl-keberangkatan').value;
            const jmlPenumpang = parseInt(document.getElementById('jml-penumpang').value) || 0;

            if (!titikJemput || !posTujuan || !tglKeberangkatan || jmlPenumpang <= 0) {
                alert('Silakan lengkapi seluruh detail pemesanan ojek terlebih dahulu!');
                return;
            }

            // Tampilkan modal
            document.getElementById('modal-nama-leader').value = '';
            const modal = document.getElementById('team-leader-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Tutup modal
        function closeTeamLeaderModal() {
            const modal = document.getElementById('team-leader-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Kirim transaksi ke backend dan panggil Midtrans Snap.js
        function submitOjekCheckout() {
            const namaLeader = document.getElementById('modal-nama-leader').value.trim();
            if (!namaLeader) {
                alert('Silakan masukkan nama lengkap penanggung jawab (Team Leader)!');
                return;
            }

            closeTeamLeaderModal();

            const idGunung = '<?= isset($gunung) ? esc($gunung['ID_GUNUNG']) : '' ?>';
            const titikJemput = document.getElementById('titik-jemput').value.trim();
            const posTujuan = document.getElementById('pos-tujuan').value.trim();
            const tglKeberangkatan = document.getElementById('tgl-keberangkatan').value;
            const jmlPenumpang = document.getElementById('jml-penumpang').value;

            const formData = new FormData();
            formData.append('id_gunung', idGunung);
            formData.append('nama_leader', namaLeader);
            formData.append('titik_jemput', titikJemput);
            formData.append('pos_tujuan', posTujuan);
            formData.append('tgl_keberangkatan', tglKeberangkatan);
            formData.append('jml_penumpang', jmlPenumpang);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('ojek/proses-pesan') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '<?= base_url('ojek/sukses') ?>/' + data.id_pesanan;
                        },
                        onPending: function(result) {
                            alert('Pemesanan ojek ditunda. Silakan selesaikan pembayaran.');
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert('Pembayaran ojek gagal atau dibatalkan.');
                            window.location.reload();
                        },
                        onClose: function() {
                            alert('Anda menutup panel pembayaran ojek sebelum selesai.');
                        }
                    });
                } else {
                    alert('Gagal mendaftarkan pesanan ojek: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi saat memproses pesanan ojek.');
            });
        }
    </script>
</body>
</html>