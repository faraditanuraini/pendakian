<?php $gunungs = $daftar_gunung ?? []; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Alat - Pendaki.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f7f6f1; }
        .modal-bg { background: rgba(15, 23, 42, 0.65); }
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
            <div class="ml-auto text-slate-500 text-xs">Selasa, 26 Mei 2026 | 12.51</div>
        </div>
    </nav>

    <main class="max-w-screen-xl mx-auto px-5 py-6 space-y-8">
        <section id="search-form-section" class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6 space-y-6">
            <div class="space-y-2">
                <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Lokasi Gunung</p>
                <select id="mountain" class="w-full rounded-3xl border border-slate-200 px-4 py-4 text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10">
                    <option value="" disabled selected>Pilih Gunung</option>
                    <?php if (!empty($gunungs)) : ?>
                        <?php foreach ($gunungs as $gunung) : ?>
                            <option value="<?= esc($gunung['NAMA_GUNUNG']) ?>"><?= esc($gunung['NAMA_GUNUNG']) ?></option>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <option value="rinjani">Gunung Rinjani</option>
                        <option value="merapi">Gunung Merapi</option>
                        <option value="semeru">Gunung Semeru</option>
                        <option value="lincing">Bukit Lincing</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Waktu Penyewaan</p>
                    <input id="rent-date" type="date" class="w-full rounded-3xl border border-slate-200 px-4 py-4 text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10" />
                </div>
                <div class="space-y-2">
                    <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Pos Perizinan Masuk</p>
                    <select id="pos-in" class="w-full rounded-3xl border border-slate-200 px-4 py-4 text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10" disabled>
                        <option value="">Pilih gunung dulu</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-sm text-slate-500 uppercase tracking-[0.2em]">Pos Perizinan Keluar</p>
                <select id="pos-out" class="w-full rounded-3xl border border-slate-200 px-4 py-4 text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10" disabled>
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

            <button id="search-button" class="w-full rounded-3xl bg-forest text-white py-4 text-sm font-black shadow-xl hover:bg-[#22491d] transition duration-300 focus:outline-none focus:ring-4 focus:ring-forest/20">Ayo Cari</button>
        </section>

        <section id="results-section" class="hidden space-y-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Ditemukan berdasarkan pilihanmu</p>
                    <h2 class="text-2xl font-black text-slate-900">Perlengkapan Tersedia</h2>
                </div>
                <button id="show-cart-button" class="rounded-3xl bg-forest text-white px-5 py-3 text-sm font-bold shadow-xl hover:bg-[#22491d] transition duration-300">Lihat Keranjang</button>
            </div>

            <div class="space-y-8">
                <div>
                    <div class="flex items-center gap-3 overflow-x-auto pb-2">
                        <button data-category="all" class="category-btn rounded-full border border-forest bg-forest/10 px-5 py-3 text-sm font-bold text-forest">Semua</button>
                        <button data-category="masak" class="category-btn rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600">Perlengkapan Masak</button>
                        <button data-category="tidur" class="category-btn rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600">Perlengkapan Tidur</button>
                        <button data-category="lampu" class="category-btn rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600">Pencahayaan / Lampu</button>
                        <button data-category="lain" class="category-btn rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600">Lain-lain</button>
                    </div>
                </div>

                <div id="equipment-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6"></div>
            </div>
        </section>

        <section id="cart-section" class="hidden bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400 uppercase tracking-[0.25em]">Keranjang</p>
                    <h2 class="text-2xl font-black text-slate-900">Alat yang dipilih</h2>
                </div>
                <button id="close-cart" class="text-sm text-forest font-bold hover:text-emerald-900">Tutup</button>
            </div>

            <div id="cart-items" class="space-y-4"></div>

            <div class="rounded-3xl bg-slate-50 border border-slate-200 p-5 flex flex-col gap-3">
                <div class="flex justify-between text-slate-500">Total Barang <span id="cart-count">0</span></div>
                <div class="flex justify-between text-lg font-black text-slate-900">Total Harga <span id="cart-total">Rp 0</span></div>
                <button id="pay-button" class="w-full rounded-3xl bg-forest text-white py-4 font-black shadow-xl hover:bg-[#22491d] transition duration-300">Bayar Sekarang</button>
            </div>
        </section>
    </main>

    <div id="item-modal" class="fixed inset-0 hidden items-center justify-center modal-bg p-4 z-50">
        <div class="w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl max-h-[calc(100vh-4rem)] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-sm text-slate-400">Masukan Keranjang</p>
                    <h3 class="text-xl font-black text-slate-900" id="modal-item-name">Nama Item</h3>
                </div>
                <button id="close-modal" class="text-slate-500 hover:text-slate-900"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="flex items-center gap-4 mb-5">
                <div class="w-24 h-24 rounded-3xl overflow-hidden bg-slate-100 flex items-center justify-center text-slate-300" id="modal-item-image">
                    <i class="fa-solid fa-image text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500" id="modal-item-subtitle">Deskripsi singkat</p>
                    <p class="text-2xl font-black text-slate-900" id="modal-item-price">Rp 0</p>
                </div>
            </div>
            <div class="rounded-3xl border border-slate-200 p-4 mb-5">
                <div class="flex items-center justify-between text-sm text-slate-500 mb-3">Jumlah Pesanan</div>
                <div class="flex items-center justify-between gap-4">
                    <button id="decrease-qty" class="w-12 h-12 rounded-3xl border border-slate-200 text-slate-700 text-xl">–</button>
                    <span id="modal-qty" class="text-2xl font-black">1</span>
                    <button id="increase-qty" class="w-12 h-12 rounded-3xl border border-slate-200 text-slate-700 text-xl">+</button>
                </div>
            </div>
            <div class="space-y-2 mb-6">
                <label class="text-sm font-bold text-slate-700">Catatan (Optional)</label>
                <textarea id="modal-note" rows="3" placeholder="Ex : Nasinya yang banyaaaak" class="w-full rounded-3xl border border-slate-200 p-4 text-slate-700 outline-none focus:border-forest/70 focus:ring-2 focus:ring-forest/10"></textarea>
            </div>
            <button id="add-to-cart" class="w-full rounded-3xl bg-forest text-white py-4 font-black hover:bg-[#22491d] transition">Masukan Keranjang</button>
        </div>
    </div>

    <div id="added-overlay" class="fixed inset-0 hidden items-center justify-center modal-bg p-4 z-50">
        <div class="w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl text-center max-h-[calc(100vh-4rem)] overflow-y-auto">
            <div class="mb-6">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-3xl text-emerald-700"><i class="fa-solid fa-shopping-cart"></i></div>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-2">Produk Telah Ditambahkan Ke Keranjang</h3>
            <p class="text-sm text-slate-500 mb-6">Belanjaanmu telah disimpan di keranjang, buruan lakukan pemesanan biar pesananmu gak di ambil orang.</p>
            <div class="grid grid-cols-2 gap-4">
                <button id="back-to-shop" class="rounded-3xl border border-forest py-4 text-forest font-black hover:bg-forest/10 transition">Beli Lagi</button>
                <button id="go-to-cart" class="rounded-3xl bg-forest text-white py-4 font-black hover:bg-[#22491d] transition">Lihat Keranjang</button>
            </div>
        </div>
    </div>

    <div id="payment-modal" class="fixed inset-0 hidden items-center justify-center modal-bg p-4 z-50">
        <div class="w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl max-h-[calc(100vh-4rem)] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-sm text-slate-500 uppercase tracking-[0.3em]">Pembayaran</p>
                    <h3 class="text-2xl font-black text-slate-900">Scan QRIS untuk bayar</h3>
                </div>
                <button id="close-payment" class="text-slate-500 hover:text-slate-900"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="rounded-[2rem] border border-slate-200 p-6 text-center mb-6">
                <div class="mx-auto mb-5 h-52 w-52 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-qrcode text-5xl"></i>
                </div>
                <p class="text-slate-500 mb-3">QRIS placeholder. Scan dengan aplikasimu untuk menyelesaikan pembayaran.</p>
                <p class="text-slate-400 text-sm">Total <span class="font-black text-slate-900" id="payment-total">Rp 0</span></p>
            </div>
            <div class="space-y-4">
                <div class="rounded-3xl bg-slate-50 border border-slate-200 p-4">
                    <p class="text-sm font-semibold text-slate-600">Nama Pemesan</p>
                    <p id="payment-name" class="text-lg font-black text-slate-900">Faradita</p>
                </div>
                <div class="rounded-3xl bg-slate-50 border border-slate-200 p-4">
                    <p class="text-sm font-semibold text-slate-600">Detail Penyewaan</p>
                    <div id="payment-details" class="text-sm text-slate-600"></div>
                </div>
                <div class="rounded-3xl bg-slate-50 border border-slate-200 p-4">
                    <p class="text-sm font-semibold text-slate-600">Alat Disewa</p>
                    <div id="payment-items" class="text-sm text-slate-600"></div>
                </div>
            </div>
            <button id="confirm-close" class="mt-6 w-full rounded-3xl bg-forest text-white py-4 font-black hover:bg-[#22491d] transition">Tutup</button>
        </div>
    </div>

    <script>
        const mountains = {
            'Gunung Rinjani': {
                name: 'Gunung Rinjani',
                posIn: ['Sembalun', 'Senaru', 'Aik Berik'],
                posOut: ['Sembalun', 'Senaru']
            },
            'Gunung Merapi': {
                name: 'Gunung Merapi',
                posIn: ['Babadan', 'Selo', 'Kaliadem'],
                posOut: ['Babadan', 'Kaliadem']
            },
            'Gunung Semeru': {
                name: 'Gunung Semeru',
                posIn: ['Ranu Pani', 'Arcopodo'],
                posOut: ['Ranu Pani', 'Arcopodo']
            },
            'Bukit Lincing': {
                name: 'Bukit Lincing',
                posIn: ['Jalur Utama', 'Jalur Alternatif'],
                posOut: ['Jalur Utama', 'Jalur Alternatif']
            }
        };

        const equipment = [
            { id: 1, category: 'masak', title: 'Cooking Set', subtitle: 'Cooking Set', price: 8000, image: '🍳' },
            { id: 2, category: 'masak', title: 'Kompor Kembang', subtitle: 'Kompor Bulat/Kembang', price: 7000, image: '🔥' },
            { id: 3, category: 'lain', title: 'Tabung Gas Portable', subtitle: 'Tabung Gas Portable', price: 35000, image: '🛢️' },
            { id: 4, category: 'lain', title: 'Trekking Pole', subtitle: 'Trekking Pole / Tongkat', price: 5000, image: '🥾' },
            { id: 5, category: 'tidur', title: 'Matras Aluminium Alloy', subtitle: 'Matras Aluminium Alloy', price: 5000, image: '🛌' },
            { id: 6, category: 'tidur', title: 'Matras Karet', subtitle: 'Matras Karet', price: 4000, image: '🛏️' },
            { id: 7, category: 'lampu', title: 'Headlamp', subtitle: 'Headlamp praktis & ringan', price: 5000, image: '🔦' }
        ];

        const cart = [];
        let currentItem = null;
        let currentQty = 1;

        const mountainEl = document.getElementById('mountain');
        const posInEl = document.getElementById('pos-in');
        const posOutEl = document.getElementById('pos-out');
        const searchButton = document.getElementById('search-button');
        const searchFormSection = document.getElementById('search-form-section');
        const resultsSection = document.getElementById('results-section');
        const equipmentGrid = document.getElementById('equipment-grid');
        const showCartButton = document.getElementById('show-cart-button');
        const cartSection = document.getElementById('cart-section');
        const cartItemsEl = document.getElementById('cart-items');
        const cartCountEl = document.getElementById('cart-count');
        const cartTotalEl = document.getElementById('cart-total');
        const payButton = document.getElementById('pay-button');
        const itemModal = document.getElementById('item-modal');
        const addedOverlay = document.getElementById('added-overlay');
        const paymentModal = document.getElementById('payment-modal');

        const categoryButtons = document.querySelectorAll('.category-btn');

        const modalName = document.getElementById('modal-item-name');
        const modalSubtitle = document.getElementById('modal-item-subtitle');
        const modalPrice = document.getElementById('modal-item-price');
        const modalQty = document.getElementById('modal-qty');
        const modalNote = document.getElementById('modal-note');
        const modalImage = document.getElementById('modal-item-image');

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

        function formatCurrency(value) {
            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function renderEquipment(items) {
            equipmentGrid.innerHTML = items.map(item => `
                <button type="button" class="group block rounded-[2rem] border border-slate-200 bg-white p-5 text-left shadow-sm hover:shadow-xl transition" data-id="${item.id}">
                    <div class="mb-5 flex h-52 items-center justify-center rounded-[2rem] bg-slate-100 text-5xl">${item.image}</div>
                    <div class="space-y-2">
                        <h3 class="text-lg font-black text-slate-900">${item.title}</h3>
                        <p class="text-sm text-slate-500">${item.subtitle}</p>
                    </div>
                    <div class="mt-5 flex items-center justify-between">
                        <span class="text-slate-900 font-black text-base">${formatCurrency(item.price)}/Pcs</span>
                        <span class="text-green-700 font-bold tracking-[0.18em] uppercase text-xs">Tambah</span>
                    </div>
                </button>
            `).join('');
        }

        function renderCart() {
            if (!cart.length) {
                cartItemsEl.innerHTML = `<div class="rounded-[2rem] border border-dashed border-slate-300 p-8 text-center text-slate-500">Belum ada barang di keranjang.</div>`;
                cartCountEl.textContent = '0';
                cartTotalEl.textContent = 'Rp 0';
                return;
            }
            cartItemsEl.innerHTML = cart.map(item => `
                <div class="rounded-[2rem] border border-slate-200 p-4 flex gap-4 items-center">
                    <div class="w-20 h-20 rounded-3xl bg-slate-100 flex items-center justify-center text-3xl">${item.image}</div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h4 class="font-black text-slate-900">${item.title}</h4>
                                <p class="text-sm text-slate-500">${item.qty} Pcs · ${item.note || '-'} </p>
                            </div>
                            <button data-remove="${item.id}" class="text-slate-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-slate-900 font-black">
                            <span>${formatCurrency(item.price * item.qty)}</span>
                        </div>
                    </div>
                </div>
            `).join('');
            const total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
            cartCountEl.textContent = cart.length;
            cartTotalEl.textContent = formatCurrency(total);
        }

        function openModal(itemId) {
            currentItem = equipment.find(item => item.id === itemId);
            currentQty = 1;
            modalName.textContent = currentItem.title;
            modalSubtitle.textContent = currentItem.subtitle;
            modalPrice.textContent = formatCurrency(currentItem.price);
            modalQty.textContent = currentQty;
            modalNote.value = '';
            modalImage.innerHTML = currentItem.image;
            itemModal.classList.remove('hidden');
        }

        function closeAllModals() {
            itemModal.classList.add('hidden');
            addedOverlay.classList.add('hidden');
            paymentModal.classList.add('hidden');
        }

        mountainEl.addEventListener('change', () => {
            updatePosOptions(mountainEl.value);
        });

        searchButton.addEventListener('click', () => {
            if (!mountainEl.value || !document.getElementById('rent-date').value || !posInEl.value || !posOutEl.value) {
                alert('Silakan pilih gunung, tanggal, dan pos perizinan terlebih dahulu.');
                return;
            }
            searchFormSection.classList.add('hidden');
            cartSection.classList.add('hidden');
            resultsSection.classList.remove('hidden');
            renderEquipment(equipment);
        });

        equipmentGrid.addEventListener('click', event => {
            const button = event.target.closest('[data-id]');
            if (!button) return;
            openModal(Number(button.dataset.id));
        });

        document.getElementById('close-modal').addEventListener('click', closeAllModals);
        document.getElementById('back-to-shop').addEventListener('click', () => {
            addedOverlay.classList.add('hidden');
        });
        document.getElementById('go-to-cart').addEventListener('click', () => {
            addedOverlay.classList.add('hidden');
            resultsSection.classList.add('hidden');
            cartSection.classList.remove('hidden');
        });
        document.getElementById('close-payment').addEventListener('click', closeAllModals);
        document.getElementById('confirm-close').addEventListener('click', closeAllModals);
        document.getElementById('increase-qty').addEventListener('click', () => {
            currentQty++;
            modalQty.textContent = currentQty;
        });
        document.getElementById('decrease-qty').addEventListener('click', () => {
            if (currentQty > 1) currentQty--;
            modalQty.textContent = currentQty;
        });

        document.getElementById('add-to-cart').addEventListener('click', () => {
            const note = modalNote.value.trim();
            const exist = cart.find(item => item.id === currentItem.id);
            if (exist) {
                exist.qty += currentQty;
                exist.note = note || exist.note;
            } else {
                cart.push({ ...currentItem, qty: currentQty, note });
            }
            renderCart();
            itemModal.classList.add('hidden');
            addedOverlay.classList.remove('hidden');
        });

        showCartButton.addEventListener('click', () => {
            resultsSection.classList.add('hidden');
            cartSection.classList.remove('hidden');
        });

        cartItemsEl.addEventListener('click', event => {
            const removeButton = event.target.closest('[data-remove]');
            if (!removeButton) return;
            const id = Number(removeButton.dataset.remove);
            const index = cart.findIndex(item => item.id === id);
            if (index !== -1) {
                cart.splice(index, 1);
                renderCart();
            }
        });

        document.getElementById('close-cart').addEventListener('click', () => {
            cartSection.classList.add('hidden');
            if (!searchFormSection.classList.contains('hidden')) {
                resultsSection.classList.remove('hidden');
            } else {
                resultsSection.classList.remove('hidden');
            }
        });

        payButton.addEventListener('click', () => {
            if (!cart.length) {
                alert('Keranjang masih kosong. Tambahkan alat dulu.');
                return;
            }
            paymentModal.classList.remove('hidden');
            document.getElementById('payment-total').textContent = cartTotalEl.textContent;
            document.getElementById('payment-name').textContent = 'Faradita';
            const selectedMountain = mountainEl.options[mountainEl.selectedIndex]?.text || mountainEl.value;
            document.getElementById('payment-details').innerHTML = `Gunung: ${selectedMountain}<br>Tanggal: ${document.getElementById('rent-date').value}<br>Pos Masuk: ${posInEl.value}<br>Pos Keluar: ${posOutEl.value}`;
            document.getElementById('payment-items').innerHTML = cart.length
                ? cart.map(item => `<div class="mb-2"><strong>${item.title}</strong> x${item.qty} - ${formatCurrency(item.price * item.qty)}<br><span class="text-slate-500 text-sm">${item.note ? item.note : 'Tanpa catatan'}</span></div>`).join('')
                : '<div class="text-slate-500">Belum ada alat yang dipilih.</div>';
        });

        categoryButtons.forEach(button => {
            button.addEventListener('click', () => {
                categoryButtons.forEach(btn => btn.classList.remove('bg-forest', 'text-white', 'border-forest'));
                categoryButtons.forEach(btn => btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200'));
                button.classList.add('bg-forest', 'text-white', 'border-forest');
                const category = button.dataset.category;
                if (category === 'all') {
                    renderEquipment(equipment);
                } else {
                    renderEquipment(equipment.filter(item => item.category === category));
                }
            });
        });

        renderCart();
    </script>
</body>
</html>
