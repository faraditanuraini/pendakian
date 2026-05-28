<?php $daftar_gunung = $daftar_gunung ?? []; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Alat - Pendaki.id</title>
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
    </style>
</head>
<body class="min-h-screen text-slate-800">
    <nav class="bg-white/95 backdrop-blur-md shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-screen-xl mx-auto px-5 py-4 flex items-center gap-4">
            <a href="<?= base_url('/') ?>" class="inline-flex items-center justify-center w-11 h-11 rounded-3xl bg-slate-100 text-slate-700 shadow-sm hover:bg-slate-200 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-sm text-slate-400">Sewa Alat</p>
                <h1 class="text-xl font-black text-slate-900">Pilih perlengkapan untuk pendakian</h1>
            </div>
            <div class="ml-auto text-slate-500 text-xs">
                <?php
                    date_default_timezone_set('Asia/Jakarta');
                    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    $nama_hari = $hari[date('w')];
                    $tanggal = date('j');
                    $nama_bulan = $bulan[date('n')];
                    $tahun = date('Y');
                    $waktu = date('H.i');
                    echo "$nama_hari, $tanggal $nama_bulan $tahun | $waktu";
                ?>
            </div>
        </div>
    </nav>

    <main class="max-w-screen-xl mx-auto px-5 py-6 space-y-8">
        <!-- Tampilan Pesan Error (Flashdata) -->
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-rose-50 border border-rose-100 rounded-3xl p-5 text-rose-800 flex items-start gap-4 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-xl mt-0.5 text-rose-500"></i>
                <div>
                    <h4 class="font-bold">Terjadi Kesalahan</h4>
                    <p class="text-sm text-rose-600 mt-1"><?= session()->getFlashdata('error') ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Pencarian Alat -->
        <form action="<?= base_url('sewa-alat/cari') ?>" method="POST" id="search-form" class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6 space-y-6">
            <?= csrf_field() ?>
            
            <div class="space-y-2">
                <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Lokasi Gunung</p>
                <select id="mountain" name="id_gunung" class="w-full rounded-3xl border border-slate-200 px-4 py-4 text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10" required>
                    <option value="" disabled selected>Pilih Gunung</option>
                    <?php if (!empty($daftar_gunung)) : ?>
                        <?php foreach ($daftar_gunung as $gunung) : ?>
                            <option value="<?= esc($gunung['ID_GUNUNG']) ?>"><?= esc($gunung['NAMA_GUNUNG']) ?></option>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <option value="1">Gunung Rinjani</option>
                        <option value="2">Gunung Merapi</option>
                        <option value="3">Gunung Semeru</option>
                        <option value="4">Bukit Lincing</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Waktu Penyewaan</p>
                    <input id="rent-date" name="tanggal_sewa" type="date" class="w-full rounded-3xl border border-slate-200 px-4 py-4 text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10" required />
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Pos Perizinan Masuk</p>
                    <select id="pos-in" name="pos_masuk" class="w-full rounded-3xl border border-slate-200 px-4 py-4 text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10" disabled required>
                        <option value="">Pilih gunung dulu</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Pos Perizinan Keluar</p>
                <select id="pos-out" name="pos_keluar" class="w-full rounded-3xl border border-slate-200 px-4 py-4 text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10" disabled required>
                    <option value="">Pilih gunung dulu</option>
                </select>
            </div>

            <div class="grid gap-3">
                <div class="rounded-3xl bg-blue-50 border border-blue-100 p-4 text-sm text-slate-700">
                    <div class="font-semibold mb-1">Jika kamu memilih Pos Perizinan keluar berbeda maka akan di kenakan <span class="text-blue-700">Biaya Tambahan Pengambilan Barang</span></div>
                    <div class="text-slate-500 text-sm">Pilih pos masuk dan keluar dengan benar supaya biaya akomodasi lebih terencana.</div>
                </div>
                <div class="rounded-3xl bg-yellow-50 border border-yellow-100 p-4 text-sm text-slate-700">
                    <div class="font-semibold mb-1">Barang yang disewa oleh pendaki diwajibkan dijaga dengan baik.</div>
                    <div class="text-slate-500 text-sm">Kerusakan menjadi tanggung jawab pendaki selama masa pinjam.</div>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#2D5A27] text-white font-bold py-4 rounded-xl shadow-md hover:bg-[#1f3f1d] transition-all">Ayo Cari</button>
        </form>
    </main>

    <script>
        const mountains = {
            '1': { name: 'Gunung Rinjani', posIn: ['Sembalun', 'Senaru', 'Aik Berik'], posOut: ['Sembalun', 'Senaru'] },
            '2': { name: 'Gunung Merapi', posIn: ['Babadan', 'Selo', 'Kaliadem'], posOut: ['Babadan', 'Kaliadem'] },
            '3': { name: 'Gunung Semeru', posIn: ['Ranu Pani', 'Arcopodo'], posOut: ['Ranu Pani', 'Arcopodo'] },
            '4': { name: 'Bukit Lincing', posIn: ['Jalur Utama', 'Jalur Alternatif'], posOut: ['Jalur Utama', 'Jalur Alternatif'] }
        };

        const mountainEl = document.getElementById('mountain');
        const posInEl = document.getElementById('pos-in');
        const posOutEl = document.getElementById('pos-out');
        const searchForm = document.getElementById('search-form');

        function updatePosOptions(key) {
            const data = mountains[key] || {
                name: key,
                posIn: ['Pos Utama', 'Pos Alternatif'],
                posOut: ['Pos Utama', 'Pos Alternatif']
            };
            posInEl.innerHTML = '';
            posOutEl.innerHTML = '';
            posInEl.disabled = false;
            posOutEl.disabled = false;
            data.posIn.forEach(pos => {
                posInEl.insertAdjacentHTML('beforeend', `<option value="${pos}">${pos}</option>`);
            });
            data.posOut.forEach(pos => {
                posOutEl.insertAdjacentHTML('beforeend', `<option value="${pos}">${pos}</option>`);
            });
        }

        mountainEl.addEventListener('change', () => {
            updatePosOptions(mountainEl.value);
        });

        // Sebelum submit, aktifkan select agar datanya ikut terkirim ke server
        searchForm.addEventListener('submit', () => {
            posInEl.disabled = false;
            posOutEl.disabled = false;
        });
    </script>
</body>
</html>