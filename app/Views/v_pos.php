<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir Modern — Fitri Lopet Cell</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #0a0f1d;
            --bg-sidebar: #0f172a;
            --bg-card: rgba(255, 255, 255, 0.04);
            --bg-card-hover: rgba(255, 255, 255, 0.07);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
            --primary-accent: #6366f1;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top Header */
        .pos-header {
            background: rgba(10, 15, 29, 0.7);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        /* Layout Columns */
        .pos-layout {
            display: flex;
            flex-grow: 1;
            overflow: hidden;
        }

        .pos-catalog-panel {
            flex-grow: 1;
            padding: 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .pos-receipt-panel {
            width: 420px;
            background: var(--bg-sidebar);
            border-left: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        /* Category Selector */
        .category-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 1.5rem;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .category-pill {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 50px;
            padding: 8px 18px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .category-pill.active, .category-pill:hover {
            background: var(--primary-accent);
            color: white;
            border-color: var(--primary-accent);
        }

        /* Search input */
        .pos-search-box {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 10px 15px 10px 45px;
            color: white;
            width: 100%;
            max-width: 400px;
            transition: all 0.3s;
        }

        .pos-search-box:focus {
            background: var(--bg-card-hover);
            border-color: var(--primary-accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            color: white;
        }

        /* Product Grid Item */
        .pos-product-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .pos-product-card:hover {
            background: var(--bg-card-hover);
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-4px);
        }

        .pos-product-card.out-of-stock {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pos-product-card .badge-type {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,255,255,0.05);
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 50px;
        }

        /* Receipt Scroll Area */
        .receipt-cart-items {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .cart-item-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 3px;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-qty {
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--border-color);
            color: white;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .btn-qty:hover {
            background: rgba(255,255,255,0.12);
        }

        /* Total Panel */
        .receipt-totals {
            padding: 1.5rem;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        /* Payment Buttons */
        .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 1rem;
        }

        .btn-pay-method {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: white;
            border-radius: 12px;
            padding: 15px 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-pay-method:hover {
            background: var(--bg-card-hover);
            border-color: var(--primary-accent);
        }

        .btn-pay-method.active {
            background: var(--primary-accent);
            border-color: var(--primary-accent);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        /* Modal custom styling */
        .modal-content-glass {
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: 24px;
            color: white;
        }

        .receipt-container {
            font-family: monospace;
            background: white;
            color: black;
            padding: 20px;
            border-radius: 8px;
            font-size: 0.85rem;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.2);
        }

        /* ── Toast Notifications ──────────────────── */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .toast-custom {
            padding: 0.85rem 1.25rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            max-width: 350px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            animation: slideRight 0.3s ease;
        }

        .toast-custom-success { background: rgba(6, 95, 70, 0.85); border-color: rgba(165, 243, 208, 0.2); color: #a7f3d0; }
        .toast-custom-error { background: rgba(127, 29, 29, 0.85); border-color: rgba(252, 165, 165, 0.2); color: #fca5a5; }
        .toast-custom-info { background: rgba(30, 58, 95, 0.85); border-color: rgba(147, 197, 253, 0.2); color: #93c5fd; }

        @keyframes slideRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- POS Header -->
    <header class="pos-header">
        <div class="d-flex align-items-center gap-3">
            <span class="p-2 rounded-3 text-white d-flex align-items-center" style="background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);">
                <i class="fas fa-store"></i>
            </span>
            <div>
                <h4 class="m-0 fw-bold brand-gradient" style="font-size: 1.25rem;">Fitri Lopet Cell</h4>
                <small class="text-secondary">Dashboard Kasir POS v1.0</small>
            </div>
        </div>

        <div class="search-wrapper position-relative flex-grow-1 mx-4" style="max-width: 400px;">
            <i class="fas fa-search position-absolute text-secondary" style="left: 15px; top: 50%; transform: translateY(-50%)"></i>
            <input type="text" id="pos-search" class="pos-search-box" placeholder="Cari nama produk atau SKU (F3)..." onkeyup="filterPOSProducts()">
        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                <i class="fas fa-circle me-1 text-xs"></i> Online
            </span>
            <a href="<?= base_url('/') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">
                <i class="fas fa-store-alt me-1"></i> Ke Etalase Toko
            </a>
        </div>
    </header>

    <!-- POS Main Layout -->
    <div class="pos-layout">
        <!-- Left Catalog Panel -->
        <div class="pos-catalog-panel">
            <div class="category-filter">
                <div class="category-pill active" onclick="filterCategory('semua')">Semua Barang</div>
                <div class="category-pill" onclick="filterCategory('Fisik')">📦 Barang Fisik</div>
                <div class="category-pill" onclick="filterCategory('Digital')">📱 Produk Digital</div>
            </div>

            <!-- Product Grid -->
            <div class="row g-3" id="pos-product-grid">
                <?php foreach ($products as $p): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 product-card-wrapper" 
                         data-name="<?= strtolower($p['nama_produk']) ?>" 
                         data-sku="<?= strtolower($p['sku_code']) ?>"
                         data-category="<?= $p['kategori'] ?>">
                        <div class="pos-product-card <?= $p['stok'] <= 0 ? 'out-of-stock' : '' ?>" 
                             onclick="addToCart(<?= htmlspecialchars(json_encode($p)) ?>)">
                            <span class="badge-type"><?= $p['kategori'] ?></span>
                            
                            <div class="text-center py-3">
                                <i class="fas <?= $p['kategori'] == 'Digital' ? 'fa-mobile-alt' : 'fa-box' ?> fa-2x text-indigo-400 mb-2"></i>
                            </div>

                            <div class="fw-bold text-truncate" style="font-size: 0.85rem;" title="<?= sanitize($p['nama_produk']) ?>">
                                <?= sanitize($p['nama_produk']) ?>
                            </div>
                            <small class="text-secondary" style="font-size: 0.75rem;">SKU: <?= sanitize($p['sku_code']) ?></small>
                            
                            <div class="mt-auto pt-2 d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold" style="font-size: 0.85rem;">
                                    Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?>
                                </span>
                                <span class="badge rounded-pill bg-light bg-opacity-5 text-secondary" style="font-size: 0.7rem;">
                                    Stok: <?= $p['stok'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right Receipt Panel -->
        <aside class="pos-receipt-panel">
            <div class="p-3 border-bottom border-secondary d-flex justify-content-between align-items-center flex-shrink-0">
                <h5 class="m-0 fw-bold"><i class="fas fa-shopping-basket me-2"></i>Keranjang</h5>
                <button class="btn btn-xs btn-outline-danger" onclick="clearCart()"><i class="fas fa-trash-alt me-1"></i>Reset</button>
            </div>

            <!-- Cart Items Area -->
            <div class="receipt-cart-items" id="cart-list">
                <!-- Cart list will be drawn here dynamically -->
                <div class="text-center py-5 text-secondary">
                    <i class="fas fa-shopping-cart fa-3x mb-3 opacity-25"></i>
                    <p class="small">Keranjang kasir masih kosong.<br>Klik produk di kiri untuk menambahkan.</p>
                </div>
            </div>

            <!-- totals and checkout controls -->
            <div class="receipt-totals">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Subtotal</span>
                    <span class="fw-semibold" id="lbl-subtotal">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-3 border-bottom border-secondary pb-2">
                    <span class="text-secondary">Pajak (PPN 0%)</span>
                    <span class="fw-semibold">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <h5 class="m-0 fw-extrabold">Total Belanja</h5>
                    <h4 class="m-0 fw-extrabold text-primary" id="lbl-total">Rp 0</h4>
                </div>

                <div class="text-secondary small fw-bold mb-2">Pilih Metode Pembayaran:</div>
                <div class="payment-methods-grid">
                    <button class="btn-pay-method active" id="btn-method-tunai" onclick="selectMethod('tunai')">
                        <i class="fas fa-money-bill-wave fa-lg"></i> Tunai
                    </button>
                    <button class="btn-pay-method" id="btn-method-rfid" onclick="selectMethod('rfid')">
                        <i class="fas fa-credit-card fa-lg"></i> RFID
                    </button>
                    <button class="btn-pay-method" id="btn-method-qris" onclick="selectMethod('qris')">
                        <i class="fas fa-qrcode fa-lg"></i> QRIS
                    </button>
                </div>

                <button class="btn btn-primary w-100 py-3 mt-4 rounded-3 fw-bold" onclick="processPOSCheckout()">
                    <i class="fas fa-cash-register me-2"></i> Proses Pembayaran
                </button>
            </div>
        </aside>
    </div>

    <!-- MODAL: PEMBAYARAN TUNAI (CASH CHANGE CALCULATOR) -->
    <div class="modal fade" id="modalTunai" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-glass">
                <div class="modal-header border-0">
                    <h5 class="fw-bold"><i class="fas fa-money-bill-wave me-2 text-success"></i>Pembayaran Tunai</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-bold">Total Tagihan</label>
                        <h2 class="fw-extrabold text-primary" id="modal-tunai-total">Rp 0</h2>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-bold">Jumlah Uang Diterima</label>
                        <input type="number" id="cash-input" class="form-control form-control-lg bg-dark text-white border-secondary py-3 text-center fw-bold" placeholder="Masukkan jumlah uang..." oninput="calculateChange()">
                    </div>
                    <div class="price-box py-3 text-center mb-0" style="background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.3)">
                        <div class="price-title text-success">Uang Kembali (Kembalian)</div>
                        <h2 class="fw-extrabold text-success mb-0" id="lbl-change">Rp 0</h2>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success rounded-pill px-4" onclick="submitPOSCheckout('tunai')">Selesaikan Transaksi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: SCAN KARTU RFID -->
    <div class="modal fade" id="modalRfid" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-glass">
                <div class="modal-header border-0">
                    <h5 class="fw-bold"><i class="fas fa-credit-card me-2 text-indigo-400"></i>Pembayaran RFID</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="text-secondary small mb-4">Minta pelanggan menempelkan kartu RFID pada reader atau input UID kartu secara manual.</p>
                    
                    <div class="d-flex gap-2 mb-4">
                        <input type="text" id="pos-rfid-uid" class="form-control form-control-lg bg-dark text-white text-center py-3 font-monospace" placeholder="UID KARTU RFID" autocomplete="off" oninput="checkRfidCard()">
                        <button type="button" class="btn btn-outline-light d-flex align-items-center justify-content-center px-3" onclick="scanNfc('pos-rfid-uid')" style="border-radius: 10px; border: 1px solid var(--border-color); background: rgba(255, 255, 255, 0.05); color: white; white-space: nowrap;">
                            <i class="fas fa-mobile-alt me-2"></i>Scan NFC
                        </button>
                    </div>

                    <div id="rfid-user-info" class="p-3 rounded-4 mb-0 text-start" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); display: none;">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-secondary">Nama Pemilik:</small>
                                <div class="fw-bold text-white" id="pos-rfid-name">-</div>
                            </div>
                            <div class="col-6">
                                <small class="text-secondary">Sisa Saldo:</small>
                                <div class="fw-bold text-success" id="pos-rfid-saldo">Rp 0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btn-rfid-confirm" class="btn btn-primary rounded-pill px-4" disabled onclick="submitPOSCheckout('rfid')">Konfirmasi & Bayar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: DYNAMIC QRIS WIJAYAPAY -->
    <div class="modal fade" id="modalQris" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-glass">
                <div class="modal-header border-0">
                    <h5 class="fw-bold"><i class="fas fa-qrcode me-2 text-pink-400"></i>QRIS WijayaPay</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="stopQrisPolling()"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div id="pos-qris-loading" class="py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-secondary mt-3 small">Meminta QRIS ke WijayaPay...</p>
                    </div>

                    <div id="pos-qris-container" style="display: none;">
                        <div class="p-3 bg-white d-inline-block rounded-4 mb-3">
                            <img id="pos-qris-img" src="" alt="QRIS" style="width: 230px; height: 230px;">
                        </div>
                        <p class="text-warning small mb-3"><i class="fas fa-info-circle me-1"></i>Tunjukkan QR Code di atas pada layar customer</p>
                        <div class="d-flex align-items-center justify-content-center gap-2 text-secondary small">
                            <div class="spinner-grow spinner-grow-sm text-secondary" role="status"></div>
                            <span>Menunggu transaksi dibayar...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: STRUK STRIP VIRTUAL RECEIPT -->
    <div class="modal fade" id="modalStruk" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content border-0 bg-transparent">
                <div class="receipt-container" id="printable-receipt">
                    <div class="text-center mb-3">
                        <h5 class="fw-bold mb-1">FITRI LOPET CELL</h5>
                        <p class="small text-muted mb-0">Pekalongan, Jawa Tengah</p>
                        <p class="small text-muted mb-0">Telp: 081234567890</p>
                    </div>
                    <div class="border-bottom border-dark pb-2 mb-2">
                        <div class="small">Tgl: <span id="rec-date">-</span></div>
                        <div class="small">Nota: <span id="rec-order-id">-</span></div>
                        <div class="small">Metode: <span id="rec-method">-</span></div>
                    </div>
                    <div class="border-bottom border-dark pb-2 mb-2" id="rec-items-list">
                        <!-- Items will draw here -->
                    </div>
                    <div class="text-end mb-4">
                        <div>Subtotal: <span class="fw-bold" id="rec-subtotal">Rp 0</span></div>
                        <div>Total Akhir: <span class="fw-bold" id="rec-total">Rp 0</span></div>
                        <div id="rec-change-div">Bayar/Kembali: <span class="fw-bold" id="rec-change">Rp 0</span></div>
                    </div>
                    <div class="text-center small">
                        <p class="mb-0">--- TERIMA KASIH ---</p>
                        <p class="text-muted small">Barang yang dibeli tidak dapat ditukar.</p>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-primary rounded-pill px-4 me-2" onclick="printReceipt()"><i class="fas fa-print me-1"></i>Cetak</button>
                    <button class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = [];
        let activeMethod = 'tunai';
        let posPollingInterval = null;
        let selectedCategory = 'semua';

        // Shortcut keyboard
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F3') {
                e.preventDefault();
                document.getElementById('pos-search').focus();
            }
        });

        function addToCart(produk) {
            if (parseInt(produk.stok) <= 0) {
                alert('Stok barang habis!');
                return;
            }

            const exist = cart.find(item => item.id === produk.id);
            if (exist) {
                if (exist.qty >= parseInt(produk.stok)) {
                    alert('Tidak bisa menambah, stok terbatas!');
                    return;
                }
                exist.qty += 1;
            } else {
                cart.push({
                    id: produk.id,
                    nama_produk: produk.nama_produk,
                    sku_code: produk.sku_code,
                    harga_jual: parseFloat(produk.harga_jual),
                    qty: 1,
                    stok: parseInt(produk.stok)
                });
            }
            renderCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            renderCart();
        }

        function adjustQty(id, delta) {
            const item = cart.find(item => item.id === id);
            if (item) {
                item.qty += delta;
                if (item.qty <= 0) {
                    removeFromCart(id);
                } else if (item.qty > item.stok) {
                    alert('Jumlah beli melebihi stok tersedia!');
                    item.qty = item.stok;
                }
            }
            renderCart();
        }

        function clearCart() {
            cart = [];
            renderCart();
        }

        function renderCart() {
            const list = document.getElementById('cart-list');
            if (cart.length === 0) {
                list.innerHTML = `
                    <div class="text-center py-5 text-secondary">
                        <i class="fas fa-shopping-cart fa-3x mb-3 opacity-25"></i>
                        <p class="small">Keranjang kasir masih kosong.<br>Klik produk di kiri untuk menambahkan.</p>
                    </div>
                `;
                document.getElementById('lbl-subtotal').textContent = 'Rp 0';
                document.getElementById('lbl-total').textContent = 'Rp 0';
                return;
            }

            let html = '';
            let total = 0;

            cart.forEach(item => {
                const subtotal = item.harga_jual * item.qty;
                total += subtotal;

                html += `
                    <div class="cart-item">
                        <div style="max-width: 200px;">
                            <div class="cart-item-title text-truncate" title="${item.nama_produk}">${item.nama_produk}</div>
                            <small class="text-secondary font-monospace" style="font-size: 0.75rem;">${item.sku_code} @ Rp ${formatRupiah(item.harga_jual)}</small>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="qty-controls">
                                <button class="btn-qty" onclick="adjustQty(${item.id}, -1)">-</button>
                                <span class="fw-bold" style="font-size: 0.9rem; min-width: 20px; text-align: center;">${item.qty}</span>
                                <button class="btn-qty" onclick="adjustQty(${item.id}, 1)">+</button>
                            </div>
                            <div class="fw-bold text-end" style="min-width: 80px; font-size: 0.9rem;">
                                Rp ${formatRupiah(subtotal)}
                            </div>
                        </div>
                    </div>
                `;
            });

            list.innerHTML = html;
            document.getElementById('lbl-subtotal').textContent = 'Rp ' + formatRupiah(total);
            document.getElementById('lbl-total').textContent = 'Rp ' + formatRupiah(total);
        }

        function selectMethod(method) {
            activeMethod = method;
            document.querySelectorAll('.btn-pay-method').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`btn-method-${method}`).classList.add('active');
        }

        function filterPOSProducts() {
            const query = document.getElementById('pos-search').value.toLowerCase();
            const cards = document.querySelectorAll('.product-card-wrapper');
            
            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const sku = card.getAttribute('data-sku');
                const category = card.getAttribute('data-category');
                
                const matchesQuery = name.includes(query) || sku.includes(query);
                const matchesCategory = selectedCategory === 'semua' || category === selectedCategory;

                if (matchesQuery && matchesCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function filterCategory(cat) {
            selectedCategory = cat;
            document.querySelectorAll('.category-pill').forEach(pill => pill.classList.remove('active'));
            event.target.classList.add('active');
            filterPOSProducts();
        }

        function processPOSCheckout() {
            if (cart.length === 0) {
                alert('Pilih minimal 1 produk!');
                return;
            }

            const total = calculateTotalCart();

            if (activeMethod === 'tunai') {
                document.getElementById('modal-tunai-total').textContent = 'Rp ' + formatRupiah(total);
                document.getElementById('cash-input').value = '';
                document.getElementById('lbl-change').textContent = 'Rp 0';
                new bootstrap.Modal(document.getElementById('modalTunai')).show();
            } else if (activeMethod === 'rfid') {
                document.getElementById('pos-rfid-uid').value = '';
                document.getElementById('rfid-user-info').style.display = 'none';
                document.getElementById('btn-rfid-confirm').disabled = true;
                new bootstrap.Modal(document.getElementById('modalRfid')).show();
                setTimeout(() => document.getElementById('pos-rfid-uid').focus(), 500);
            } else if (activeMethod === 'qris') {
                new bootstrap.Modal(document.getElementById('modalQris')).show();
                submitPOSCheckout('qris');
            }
        }

        function calculateTotalCart() {
            return cart.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);
        }

        function calculateChange() {
            const total = calculateTotalCart();
            const cash = parseFloat(document.getElementById('cash-input').value || 0);
            const change = Math.max(0, cash - total);
            document.getElementById('lbl-change').textContent = 'Rp ' + formatRupiah(change);
        }

        // AJAX cek RFID Card di Kasir
        async function checkRfidCard() {
            const uid = document.getElementById('pos-rfid-uid').value.trim();
            if (uid.length < 4) {
                document.getElementById('rfid-user-info').style.display = 'none';
                document.getElementById('btn-rfid-confirm').disabled = true;
                return;
            }

            try {
                const res = await fetch(`<?= base_url('pos/check-card') ?>/${uid}`);
                const data = await res.json();
                
                if (data.status === 'success') {
                    document.getElementById('pos-rfid-name').textContent = data.nama;
                    document.getElementById('pos-rfid-saldo').textContent = 'Rp ' + formatRupiah(data.saldo);
                    document.getElementById('rfid-user-info').style.display = 'block';
                    
                    const total = calculateTotalCart();
                    if (data.saldo >= total) {
                        document.getElementById('btn-rfid-confirm').disabled = false;
                        document.getElementById('pos-rfid-saldo').className = 'fw-bold text-success';
                    } else {
                        document.getElementById('btn-rfid-confirm').disabled = true;
                        document.getElementById('pos-rfid-saldo').className = 'fw-bold text-danger';
                    }
                } else {
                    document.getElementById('rfid-user-info').style.display = 'none';
                    document.getElementById('btn-rfid-confirm').disabled = true;
                }
            } catch (err) {
                console.error(err);
            }
        }

        // Selesaikan/Submit checkout via AJAX
        async function submitPOSCheckout(method) {
            const total = calculateTotalCart();
            const rfidUid = document.getElementById('pos-rfid-uid').value.trim();

            const form = new FormData();
            form.append('metode_bayar', method);
            form.append('cart', JSON.stringify(cart));
            if (method === 'rfid') {
                form.append('rfid_uid', rfidUid);
            }

            // Hide previous active checkout modals
            if (method === 'tunai') {
                bootstrap.Modal.getInstance(document.getElementById('modalTunai')).hide();
            } else if (method === 'rfid') {
                bootstrap.Modal.getInstance(document.getElementById('modalRfid')).hide();
            }

            try {
                const response = await fetch("<?= base_url('pos/checkout') ?>", {
                    method: 'POST',
                    body: form
                });
                const data = await response.json();

                if (data.status === 'success') {
                    showReceiptModal(method, data.order_id);
                    clearCart();
                } else if (data.status === 'qris_pending') {
                    // Show QRIS screen
                    document.getElementById('pos-qris-loading').style.display = 'none';
                    document.getElementById('pos-qris-container').style.display = 'block';
                    document.getElementById('pos-qris-img').src = `https://api.qrserver.com/v1/create-qr-code/?size=230x230&data=${encodeURIComponent(data.qr_data)}`;
                    startQrisPolling(data.order_id);
                } else {
                    alert(data.message || 'Transaksi gagal');
                }
            } catch (err) {
                alert('Gagal memproses ke server kasir');
            }
        }

        // QRIS Polling for Cashier screen
        function startQrisPolling(orderId) {
            if (posPollingInterval) clearInterval(posPollingInterval);
            
            posPollingInterval = setInterval(async () => {
                try {
                    const res = await fetch(`<?= base_url('pos/poll-status') ?>/${orderId}`);
                    const data = await res.json();
                    
                    if (data.status === 'success') {
                        clearInterval(posPollingInterval);
                        bootstrap.Modal.getInstance(document.getElementById('modalQris')).hide();
                        showReceiptModal('qris', orderId);
                        clearCart();
                    } else if (data.status === 'failed' || data.status === 'expired') {
                        clearInterval(posPollingInterval);
                        bootstrap.Modal.getInstance(document.getElementById('modalQris')).hide();
                        alert('Pembayaran QRIS Kedaluwarsa atau Gagal');
                    }
                } catch (err) {
                    console.error(err);
                }
            }, 3000);
        }

        function stopQrisPolling() {
            if (posPollingInterval) clearInterval(posPollingInterval);
        }

        // Render Struk/Virtual Receipt
        function showReceiptModal(method, orderId) {
            document.getElementById('rec-date').textContent = new Date().toLocaleString('id-ID');
            document.getElementById('rec-order-id').textContent = orderId;
            document.getElementById('rec-method').textContent = method.toUpperCase();

            const list = document.getElementById('rec-items-list');
            list.innerHTML = '';
            
            let total = 0;
            cart.forEach(item => {
                const sub = item.harga_jual * item.qty;
                total += sub;

                const div = document.createElement('div');
                div.className = 'd-flex justify-content-between text-dark';
                div.innerHTML = `
                    <span>${item.nama_produk} x${item.qty}</span>
                    <span>Rp ${formatRupiah(sub)}</span>
                `;
                list.appendChild(div);
            });

            document.getElementById('rec-subtotal').textContent = 'Rp ' + formatRupiah(total);
            document.getElementById('rec-total').textContent = 'Rp ' + formatRupiah(total);

            const changeDiv = document.getElementById('rec-change-div');
            if (method === 'tunai') {
                const cashInput = parseFloat(document.getElementById('cash-input').value || 0);
                const change = Math.max(0, cashInput - total);
                changeDiv.style.display = 'block';
                document.getElementById('rec-change').textContent = `Rp ${formatRupiah(cashInput)} / Rp ${formatRupiah(change)}`;
            } else {
                changeDiv.style.display = 'none';
            }

            new bootstrap.Modal(document.getElementById('modalStruk')).show();
        }

        function printReceipt() {
            const printContent = document.getElementById('printable-receipt').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = `
                <div style="display:flex; justify-content:center; align-items:center; min-height:100vh; background:white;">
                    <div style="width: 300px; padding: 20px; font-family: monospace;">
                        ${printContent}
                    </div>
                </div>
            `;
            window.print();
            
            // Reload to restore POS screen
            window.location.reload();
        }

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // ── Toast Notifications Helper ──
        function showCustomToast(message, type = 'info') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'toast-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = `toast-custom toast-custom-${type}`;
            
            const icons = { success: '✅', error: '❌', info: 'ℹ️' };
            toast.innerHTML = `<span>${icons[type] || 'ℹ️'}</span> <span>${message}</span>`;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(50px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // ── Web NFC API Scanner ──
        async function scanNfc(inputElementId) {
            if (!('NDEFReader' in window)) {
                showCustomToast('Web NFC tidak didukung di browser ini. Gunakan Chrome di Android dengan koneksi HTTPS.', 'error');
                return;
            }

            try {
                const ndef = new NDEFReader();
                showCustomToast('Mulai memindai NFC... Dekatkan kartu RFID/NFC ke bagian belakang HP Anda.', 'info');
                await ndef.scan();
                
                ndef.addEventListener("readingerror", () => {
                    showCustomToast("Gagal membaca tag NFC. Silakan coba lagi.", "error");
                });

                ndef.addEventListener("reading", ({ message, serialNumber }) => {
                    if (serialNumber) {
                        const cleanedSerial = serialNumber.replace(/:/g, "").toUpperCase();
                        const inputEl = document.getElementById(inputElementId);
                        if (inputEl) {
                            inputEl.value = cleanedSerial;
                            showCustomToast(`Berhasil membaca NFC: ${cleanedSerial}`, 'success');
                            inputEl.dispatchEvent(new Event('input', { bubbles: true }));
                            inputEl.dispatchEvent(new Event('change', { bubbles: true }));
                        } else {
                            showCustomToast(`Hasil Scan NFC: ${cleanedSerial}`, 'success');
                        }
                    } else {
                        showCustomToast("Tag NFC terbaca tetapi tidak memiliki serial number.", "error");
                    }
                });
            } catch (error) {
                showCustomToast("Gagal mengaktifkan NFC: " + error.message, "error");
            }
        }
    </script>
</body>
</html>
