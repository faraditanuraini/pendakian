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
</head>
<body class="bg-[#f7faf6] min-h-screen pb-10">

    <header class="bg-[#2D5A27] text-white p-5 sticky top-0 z-50 shadow-lg">
        <div class="max-w-screen-md mx-auto flex items-center gap-4">
            <a href="<?= isset($gunung) ? base_url('ojek') : base_url('/') ?>" class="p-2 rounded-full bg-white/10 hover:bg-white/20 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-xs uppercase tracking-[0.35em] opacity-80">Layanan Ojek</p>
                <h1 class="text-xl font-black leading-snug"><?= isset($gunung) ? $gunung['NAMA_GUNUNG'] : 'Pilih Destinasi' ?></h1>
                <p class="text-sm text-slate-200"><?= isset($gunung) ? $gunung['LOKASI'] : 'Pilih gunung untuk layanan ojek' ?></p>
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
                                     alt="<?= $g['NAMA_GUNUNG'] ?>"
                                     onerror="this.src='<?= base_url('uploads/placeholder.jpg') ?>';">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 text-white">
                                    <h3 class="text-lg font-bold uppercase"><?= $g['NAMA_GUNUNG'] ?></h3>
                                    <p class="text-xs opacity-90"><?= $g['LOKASI'] ?></p>
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
                    <h2 class="text-2xl font-black text-slate-900"><?= $gunung['NAMA_GUNUNG'] ?></h2>
                    <p class="text-sm text-slate-600">Pastikan basecamp dan pos tujuan sudah benar.</p>
                </div>

                <form class="space-y-5 mt-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Titik Jemput (Basecamp)</label>
                        <div class="relative">
                            <input type="text" placeholder="Ex: Basecamp Sembalun" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                            <i class="fa-solid fa-map-pin absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-800 uppercase">Pos Tujuan (Drop Off)</label>
                        <div class="relative">
                            <input type="text" placeholder="Ex: Pos 1 Sembalun" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                            <i class="fa-solid fa-location-dot absolute right-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-800 uppercase">Tanggal Keberangkatan</label>
                            <input type="date" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-800 uppercase">Jumlah Penumpang</label>
                            <input type="number" min="1" placeholder="Ex: 2 Orang" class="w-full rounded-3xl border border-gray-200 bg-slate-50 px-5 py-4 text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                        </div>
                    </div>
                </form>
            </section>

            <section class="grid gap-4">
                <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-orange-100 flex flex-col gap-4 md:flex-row md:items-center bg-orange-50/50">
                    <div class="min-w-[42px] min-h-[42px] rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800">Info Harga Ojek</p>
                        <p class="text-sm text-slate-500">Harga ojek dapat bervariasi tergantung jarak pos awal ke pos tujuan. Admin akan mengkonfirmasi total harga setelah pesanan masuk.</p>
                    </div>
                </div>
            </section>

            <button class="w-full bg-forest text-white font-black py-4 rounded-3xl shadow-xl hover:bg-[#1f3f1d] transition-all flex justify-center items-center gap-2">
                <i class="fa-solid fa-motorcycle"></i> Pesan Ojek Sekarang
            </button>
        <?php endif; ?>
    </main>

</body>
</html>