<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $pageTitle = isset($gunung) ? 'Porter & Guide - ' . $gunung['NAMA_GUNUNG'] : 'Porter & Guide'; ?>
    <title><?= $pageTitle ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
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

                <form class="space-y-5 mt-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Pos Perizinan Masuk</label>
                        <div class="relative">
                            <input type="text" placeholder="Ex: Jalur Senaru" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                            <i class="fa-solid fa-magnifying-glass absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Pos Perizinan Keluar</label>
                        <div class="relative">
                            <input type="text" placeholder="Ex: Jalur Sembalun" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                            <i class="fa-solid fa-map-pin absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Tipe Layanan</label>
                        <div class="relative">
                            <input type="text" placeholder="Ex: Guide" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                            <i class="fa-solid fa-user-tie absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-800 uppercase">Tanggal Masuk</label>
                            <input type="date" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-800 uppercase">Tanggal Keluar</label>
                            <input type="date" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Jumlah Pesanan</label>
                        <input type="number" min="1" placeholder="Ex: 2 porter" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-forest focus:ring-2 focus:ring-forest/20">
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

            <button class="w-full bg-forest text-white font-black py-4 rounded-3xl shadow-xl hover:bg-[#1f3f1d] transition-all">Pesan Sekarang</button>
        <?php endif; ?>
    </main>

</body>
</html>
