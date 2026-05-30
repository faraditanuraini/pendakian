<?php
$transactions = $transactions ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f6] text-slate-900 min-h-screen font-sans">
    <?= $this->include('admin/sidebar') ?>

    <div class="ml-64 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col gap-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900">Kelola Transaksi</h1>
                        <p class="text-sm text-slate-500 mt-1">Pantau status pembayaran dan kehadiran pendaki secara real-time.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="<?= base_url('admin/dashboard') ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            <i class="fa-solid fa-arrow-left"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-black text-slate-900">Daftar Transaksi</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 text-xs uppercase tracking-widest text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-semibold">ID Booking</th>
                                <th class="px-6 py-4 font-semibold">Nama Ketua</th>
                                <th class="px-6 py-4 font-semibold">Gunung/Jalur</th>
                                <th class="px-6 py-4 font-semibold">Tanggal Mendaki</th>
                                <th class="px-6 py-4 font-semibold">Jumlah Anggota</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <?php if (!empty($transactions)) : ?>
                                <?php foreach ($transactions as $transaction) : ?>
                                    <?php
                                        $statusPayment = strtolower($transaction['status_pembayaran'] ?? 'pending');
                                        $statusPresence = strtolower($transaction['status_kehadiran'] ?? 'pending');
                                        $badgeText = 'Menunggu';
                                        $badgeClass = 'bg-amber-100 text-amber-700';

                                        if (in_array($statusPresence, ['di atas gunung', 'di atas gunung'])) {
                                            $badgeText = 'Di Atas Gunung';
                                            $badgeClass = 'bg-sky-100 text-sky-700';
                                        } elseif (in_array($statusPresence, ['sudah turun', 'sudah turun'])) {
                                            $badgeText = 'Sudah Turun';
                                            $badgeClass = 'bg-slate-100 text-slate-700';
                                        } elseif (in_array($statusPayment, ['lunas', 'sudah bayar', 'berhasil'])) {
                                            $badgeText = 'Lunas';
                                            $badgeClass = 'bg-emerald-100 text-emerald-700';
                                        }

                                        $transactionId = esc($transaction['id'] ?? '');
                                    ?>
                                    <tr id="transaction-row-<?= $transactionId ?>">
                                        <td class="px-6 py-4 font-semibold text-slate-900"><?= $transactionId ?></td>
                                        <td class="px-6 py-4"><?= esc($transaction['nama_ketua'] ?? '-') ?></td>
                                        <td class="px-6 py-4"><?= esc($transaction['gunung'] ?? '-') ?></td>
                                        <td class="px-6 py-4"><?= esc($transaction['tanggal_mendaki'] ?? '-') ?></td>
                                        <td class="px-6 py-4"><?= number_format((int) ($transaction['jumlah_anggota'] ?? 1), 0, ',', '.') ?> Orang</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold <?= $badgeClass ?>"><?= $badgeText ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button type="button" data-id="<?= $transactionId ?>" class="view-detail inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">
                                                <i class="fa-solid fa-eye"></i> Lihat Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center text-sm text-slate-500">Belum ada transaksi tersedia.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-8">
        <div class="w-full max-w-3xl overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h3 class="text-xl font-black text-slate-900">Detail Transaksi</h3>
                    <p class="text-sm text-slate-500" id="modalBookingId"></p>
                </div>
                <button id="closeModal" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="space-y-6 p-6">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-500">Nama Ketua</p>
                        <p class="text-lg font-black text-slate-900 mt-2" id="modalNamaKetua"></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-500">Gunung/Jalur</p>
                        <p class="text-lg font-black text-slate-900 mt-2" id="modalGunung"></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-500">Tanggal Mendaki</p>
                        <p class="text-lg font-black text-slate-900 mt-2" id="modalTanggal"></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-500">Jumlah Anggota</p>
                        <p class="text-lg font-black text-slate-900 mt-2" id="modalJumlahAnggota"></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-500">Harga Tiket / Orang</p>
                        <p class="text-lg font-black text-slate-900 mt-2" id="modalMountainPrice"></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-500">Sumber Jumlah</p>
                        <p class="text-lg font-black text-slate-900 mt-2" id="modalMemberSource"></p>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-500">Status Pembayaran</p>
                            <p class="mt-2 font-black text-slate-900" id="modalStatusBayar"></p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-500">Status Kehadiran</p>
                            <p class="mt-2 font-black text-slate-900" id="modalStatusKehadiran"></p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <h4 class="text-lg font-black text-slate-900">Daftar Anggota</h4>
                    <div class="mt-4 space-y-3" id="modalAnggotaList">
                        <p class="text-sm text-slate-500">Belum ada data anggota.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button id="checkInBtn" class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-5 py-3 text-sm font-bold text-white hover:bg-sky-700 transition">Validasi Check-In</button>
                    <button id="checkOutBtn" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">Validasi Check-Out</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const detailButtons = document.querySelectorAll('.view-detail');
        const modal = document.getElementById('detailModal');
        const closeModal = document.getElementById('closeModal');
        const modalBookingId = document.getElementById('modalBookingId');
        const modalNamaKetua = document.getElementById('modalNamaKetua');
        const modalGunung = document.getElementById('modalGunung');
        const modalTanggal = document.getElementById('modalTanggal');
        const modalJumlahAnggota = document.getElementById('modalJumlahAnggota');
        const modalStatusBayar = document.getElementById('modalStatusBayar');
        const modalStatusKehadiran = document.getElementById('modalStatusKehadiran');
        const modalMountainPrice = document.getElementById('modalMountainPrice');
        const modalMemberSource = document.getElementById('modalMemberSource');
        const modalAnggotaList = document.getElementById('modalAnggotaList');
        const checkInBtn = document.getElementById('checkInBtn');
        const checkOutBtn = document.getElementById('checkOutBtn');
        let currentTransactionId = null;

        const updateBadgeInRow = (id, status) => {
            const row = document.getElementById(`transaction-row-${id}`);
            if (!row) return;
            const badge = row.querySelector('td:nth-child(6) span');
            if (!badge) return;
            const lower = status.toLowerCase();
            if (lower.includes('di atas gunung')) {
                badge.textContent = 'Di Atas Gunung';
                badge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-sky-100 text-sky-700';
            } else if (lower.includes('sudah turun')) {
                badge.textContent = 'Sudah Turun';
                badge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700';
            } else {
                badge.textContent = 'Lunas';
                badge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700';
            }
        };

        const fillModal = (data) => {
            currentTransactionId = data.id;
            modalBookingId.textContent = `ID Booking: ${data.id}`;
            modalNamaKetua.textContent = data.nama_ketua || '-';
            modalGunung.textContent = data.gunung || '-';
            modalTanggal.textContent = data.tanggal_mendaki || '-';
            modalJumlahAnggota.textContent = `${Number(data.jumlah_anggota || 1)} Orang`;
            modalStatusBayar.textContent = data.status_pembayaran || 'Belum Bayar';
            modalStatusKehadiran.textContent = data.status_kehadiran || 'Pending';
            modalMountainPrice.textContent = data.mountain_price ? `Rp ${Number(data.mountain_price).toLocaleString('id-ID')}` : '-';
            modalMemberSource.textContent = data.member_source ? data.member_source.replace('_', ' ') : 'fallback';

            const members = Array.isArray(data.members) ? data.members : [];
            if (members.length) {
                modalAnggotaList.innerHTML = '';
                members.forEach(member => {
                    const card = document.createElement('div');
                    card.className = 'rounded-3xl border border-slate-200 bg-white p-4 shadow-sm';
                    card.innerHTML = `
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-black text-slate-900">${member.nama ?? 'Nama tidak tersedia'}</p>
                                <p class="text-xs text-slate-500">KTP: ${member.ktp ?? '-'}</p>
                            </div>
                            <a href="${member.surat_sehat ?? '#'}" target="_blank" class="text-xs font-bold text-slate-900 hover:text-slate-700">Lihat Surat Sehat</a>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">Kontak Darurat: ${member.kontak_darurat ?? '-'}</p>
                    `;
                    modalAnggotaList.appendChild(card);
                });
            } else {
                modalAnggotaList.innerHTML = '<p class="text-sm text-slate-500">Belum ada data anggota tersedia.</p>';
            }
        };

        const openModal = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        const closeModalFn = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

        detailButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                fetch(`<?= base_url('admin/transaksi/detail') ?>/${id}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(result => {
                    if (!result.success) throw new Error(result.message || 'Gagal mengambil data');
                    fillModal(result.data);
                    openModal();
                })
                .catch(() => alert('Gagal memuat detail transaksi.'));
            });
        });

        closeModal.addEventListener('click', closeModalFn);
        modal.addEventListener('click', (event) => {
            if (event.target === modal) closeModalFn();
        });

        const updateTransactionStatus = (type) => {
            if (!currentTransactionId) return;
            const action = type === 'checkin' ? 'check-in' : 'check-out';
            fetch(`<?= base_url('admin/transaksi') ?>/${action}/${currentTransactionId}`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(result => {
                if (!result.success) throw new Error(result.message || 'Gagal memperbarui status');
                const newStatus = type === 'checkin' ? 'Di Atas Gunung' : 'Sudah Turun';
                modalStatusKehadiran.textContent = newStatus;
                updateBadgeInRow(currentTransactionId, newStatus);
            })
            .catch(() => alert('Gagal memperbarui status pendaki.'));
        };

        checkInBtn.addEventListener('click', () => updateTransactionStatus('checkin'));
        checkOutBtn.addEventListener('click', () => updateTransactionStatus('checkout'));
    </script>
</body>
</html>
