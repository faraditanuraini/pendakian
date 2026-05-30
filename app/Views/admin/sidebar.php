<?php
$role = session()->get('role');
$currentUri = uri_string();
function navItem($url, $icon, $label, $isActive)
{
    return sprintf(
        '<a href="%s" class="block %s px-4 py-3 rounded-2xl text-sm font-bold flex items-center gap-3 transition-all"><i class="fa-solid %s w-5"></i> %s</a>',
        base_url($url),
        $isActive ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white',
        $icon,
        $label
    );
}
?>
<div class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-white p-6 flex flex-col justify-between z-20">
    <div>
        <div class="text-2xl font-black uppercase tracking-wider text-emerald-400 mb-8 text-center">
            <i class="fa-solid fa-mountain-sun mr-2"></i>Pendaki.id
        </div>
        <nav class="space-y-2">
            <?= navItem('admin/dashboard', 'fa-chart-pie', 'Dashboard', $currentUri === 'admin/dashboard' || $currentUri === '') ?>
            <?= navItem('admin/gunung', 'fa-mountain', 'Kelola Gunung', strpos($currentUri, 'admin/gunung') !== false || strpos($currentUri, 'admin/mountains') !== false) ?>
            <?= navItem('admin/transactions', 'fa-ticket', 'Kelola Transaksi', strpos($currentUri, 'admin/transactions') !== false || strpos($currentUri, 'admin/transaksi') !== false) ?>
            <?= navItem('admin/partners', 'fa-users', 'Kelola Mitra', strpos($currentUri, 'admin/partners') !== false) ?>
            <?= navItem('admin/equipments', 'fa-boxes-stacked', 'Sewa Alat', strpos($currentUri, 'admin/equipments') !== false) ?>
            <?php if ($role === 'Admin Utama') : ?>
                <?= navItem('admin/finance', 'fa-wallet', 'Keuangan', strpos($currentUri, 'admin/finance') !== false) ?>
            <?php endif; ?>
        </nav>
    </div>

    <div>
        <a href="<?= base_url('logout') ?>" class="block w-full rounded-2xl border border-red-500/20 bg-red-500/10 text-red-500 px-4 py-3 text-sm font-bold text-center hover:bg-red-500 hover:text-white transition">
            <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
        </a>
    </div>
</div>
