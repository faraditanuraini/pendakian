<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Pendaki.id</title>
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
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100 space-y-6">
        <div class="text-center space-y-2">
            <a href="<?= base_url('/') ?>" class="text-2xl font-black text-forest uppercase tracking-tighter">Pendaki.id</a>
            <h2 class="text-xl font-extrabold text-gray-800">Selamat Datang Kembali!</h2>
            <p class="text-sm text-gray-400">Silakan masuk dengan akun kamu</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/proses') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            
            <div class="space-y-2">
                <label class="text-xs font-black text-gray-400 uppercase tracking-wider block pl-2">Username / Email</label>
                <div class="flex items-center bg-gray-50 px-4 py-3.5 rounded-2xl gap-3 border border-transparent focus-within:border-forest/30 focus-within:bg-white transition-all">
                    <i class="fa-solid fa-user text-gray-400"></i>
                    <input type="text" name="username" placeholder="Masukkan username" required class="bg-transparent text-sm outline-none w-full text-gray-700">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-black text-gray-400 uppercase tracking-wider block pl-2">Password</label>
                <div class="flex items-center bg-gray-50 px-4 py-3.5 rounded-2xl gap-3 border border-transparent focus-within:border-forest/30 focus-within:bg-white transition-all">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                    <input type="password" name="password" placeholder="Masukkan password" required class="bg-transparent text-sm outline-none w-full text-gray-700">
                </div>
            </div>

            <button type="submit" class="w-full bg-forest text-white py-4 rounded-2xl text-sm font-bold shadow-md hover:bg-green-800 transition-all active:scale-[0.98] mt-2">
                Masuk Sekarang
            </button>
        </form>

        <div class="text-center">
            <a href="<?= base_url('/') ?>" class="text-xs font-bold text-gray-400 hover:text-forest transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>