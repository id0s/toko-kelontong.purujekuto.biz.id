<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran — Fitri Lopet Cell</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #0a0f1d;
            --bg-card: rgba(255, 255, 255, 0.04);
            --bg-card-hover: rgba(255, 255, 255, 0.08);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
            --primary-accent: #6366f1;
            --primary-accent-hover: #4f46e5;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.1) 0px, transparent 50%);
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .payment-container {
            width: 100%;
            max-width: 500px;
        }

        /* Glassmorphism Card */
        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .price-box {
            background: rgba(99, 102, 241, 0.08);
            border: 1px dashed rgba(99, 102, 241, 0.3);
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 2rem;
        }

        .price-title {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .price-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: #818cf8;
        }

        /* Nav Tabs Custom */
        .nav-pills {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            padding: 6px;
            border-radius: 50px;
            margin-bottom: 2rem;
        }

        .nav-pills .nav-link {
            border-radius: 50px;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 20px;
            transition: all 0.3s;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, var(--primary-accent) 0%, #ec4899 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .qr-container {
            background: white;
            padding: 1.5rem;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .qr-image {
            width: 200px;
            height: 200px;
            display: block;
        }

        .input-rfid {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 15px;
            color: white;
            font-size: 1.1rem;
            text-align: center;
            letter-spacing: 2px;
            font-family: monospace;
            transition: all 0.3s;
        }

        .input-rfid:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-accent);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            color: white;
        }

        /* Styler loading */
        .loading-pulse {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--primary-accent);
            display: inline-block;
            animation: pulse 1.4s infinite ease-in-out both;
        }
        
        .pulse-1 { animation-delay: -0.32s; }
        .pulse-2 { animation-delay: -0.16s; }

        @keyframes pulse {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1.0); }
        }

        .btn-pay {
            border-radius: 14px;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s;
            border: none;
            width: 100%;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-accent) 0%, #ec4899 100%);
            color: white;
        }

        .btn-gradient:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
            color: white;
        }

        /* Success & Fail Overlays */
        .status-overlay {
            display: none;
            text-align: center;
            padding: 2rem 1rem;
        }

        .status-icon {
            font-size: 4.5rem;
            margin-bottom: 1.5rem;
        }

        .icon-success {
            color: var(--success-color);
            animation: bounceIn 0.8s;
        }

        .icon-error {
            color: var(--danger-color);
            animation: shake 0.5s;
        }

        @keyframes bounceIn {
            from, 20%, 40%, 60%, 80%, to { animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1); }
            0% { opacity: 0; transform: scale3d(0.3, 0.3, 0.3); }
            20% { transform: scale3d(1.1, 1.1, 1.1); }
            40% { transform: scale3d(0.9, 0.9, 0.9); }
            60% { opacity: 1; transform: scale3d(1.03, 1.03, 1.03); }
            80% { transform: scale3d(0.97, 0.97, 0.97); }
            to { opacity: 1; transform: scale3d(1, 1, 1); }
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

    <div class="payment-container">
        <!-- Main Payment Area -->
        <div class="glass-card" id="payment-card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="fas fa-wallet me-2"></i>Pembayaran</h5>
                <a href="<?= base_url('/') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3" style="border-color: var(--border-color)">
                    <i class="fas fa-arrow-left me-1"></i> Batal
                </a>
            </div>

            <!-- Product Summary -->
            <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color);">
                <div class="p-3 bg-gradient rounded-3" style="background: rgba(99, 102, 241, 0.2)">
                    <i class="fas <?= $produk['kategori'] == 'Digital' ? 'fa-mobile-alt' : 'fa-box-open' ?> text-indigo-400 fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size: 1.05rem;"><?= sanitize($produk['nama_produk']) ?></div>
                    <span class="text-secondary small">SKU: <?= sanitize($produk['sku_code']) ?></span>
                </div>
            </div>

            <div class="price-box text-center">
                <div class="price-title">Total Pembayaran</div>
                <div class="price-value">Rp <?= number_format($total_harga, 0, ',', '.') ?></div>
            </div>

            <!-- Payment Methods Nav Tabs -->
            <ul class="nav nav-pills nav-fill" id="paymentTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="qris-tab" data-bs-toggle="tab" data-bs-target="#qris-pay" type="button" role="tab" onclick="initQris()">
                        <i class="fas fa-qrcode me-2"></i>Scan QRIS
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rfid-tab" data-bs-toggle="tab" data-bs-target="#rfid-pay" type="button" role="tab">
                        <i class="fas fa-credit-card me-2"></i>Tap RFID Card
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content text-center" id="paymentTabContent">
                <!-- QRIS Tab -->
                <div class="tab-pane fade show active" id="qris-pay" role="tabpanel">
                    <div id="qris-loading" class="py-4">
                        <div class="loading-pulse pulse-1"></div>
                        <div class="loading-pulse"></div>
                        <div class="loading-pulse pulse-2"></div>
                        <p class="text-secondary mt-3 small">Mengambil QRIS Dinamis WijayaPay...</p>
                    </div>

                    <div id="qris-container" style="display: none;">
                        <div class="qr-container shadow-sm">
                            <img id="qris-image" src="" alt="QRIS WijayaPay" class="qr-image">
                        </div>
                        <p class="text-warning small mb-3">
                            <i class="fas fa-hourglass-half me-1"></i> Scan QRIS di atas untuk membayar otomatis
                        </p>
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2 text-secondary small">
                            <div class="loading-pulse pulse-1" style="width: 8px; height: 8px; background: var(--text-secondary)"></div>
                            <span>Menunggu pembayaran...</span>
                        </div>
                    </div>
                </div>

                <!-- RFID Tab -->
                <div class="tab-pane fade" id="rfid-pay" role="tabpanel">
                    <form id="rfid-form" onsubmit="processRfidPay(event)">
                        <input type="hidden" name="sku_code" value="<?= $produk['sku_code'] ?>">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">

                        <p class="text-secondary small mb-4">Input atau tempelkan kartu RFID Anda pada reader untuk mengambil UID</p>
                        
                        <div class="d-flex gap-2 mb-4">
                            <input type="text" name="rfid_uid" id="rfid_uid" class="form-control input-rfid flex-grow-1" placeholder="UID KARTU" required autocomplete="off" autofocus>
                            <button type="button" class="btn btn-outline-light px-3 d-flex align-items-center justify-content-center" onclick="scanNfc('rfid_uid')" style="border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255, 255, 255, 0.05); color: white; white-space: nowrap;">
                                <i class="fas fa-mobile-alt me-2"></i>Scan NFC
                            </button>
                        </div>

                        <button type="submit" id="btn-rfid-submit" class="btn-pay btn-gradient py-3">
                            <i class="fas fa-check-circle me-1"></i> Verifikasi & Bayar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success Area -->
        <div class="glass-card status-overlay" id="success-area">
            <div class="status-icon icon-success"><i class="fas fa-check-circle"></i></div>
            <h3 class="fw-bold mb-2">Pembayaran Berhasil!</h3>
            <p class="text-secondary small mb-4" id="success-msg">Pembayaran lunas. Rincian pesanan Anda:</p>
            
            <div class="p-3 rounded-4 mb-4 text-start animate__animated animate__fadeIn" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); font-size: 0.9rem;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Nama Barang:</span>
                    <span class="fw-bold text-white"><?= sanitize($produk['nama_produk']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Kategori:</span>
                    <span class="fw-bold text-white"><?= sanitize($produk['kategori']) ?></span>
                </div>
                <?php if ($produk['kategori'] == 'Digital'): ?>
                <div class="mt-3 pt-3 border-top border-secondary-50 text-center" style="border-top-style: dashed !important;">
                    <small class="text-secondary d-block mb-1">Serial Number / Kode Voucher</small>
                    <div class="fw-bold text-success font-monospace fs-5" style="letter-spacing: 1px;">
                        TRX-<?= strtoupper(substr(md5($order_id), 0, 16)) ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="mt-3 pt-3 border-top border-secondary-50 text-center" style="border-top-style: dashed !important;">
                    <div class="fw-bold text-success small">
                        <i class="fas fa-box-open me-1"></i> Silakan ambil produk Anda di kasir
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="price-box py-3 text-center mb-4">
                <div class="price-title">Jumlah Terbayar</div>
                <div class="price-value" style="color: var(--success-color)">Rp <?= number_format($total_harga, 0, ',', '.') ?></div>
            </div>
            <a href="<?= base_url('/') ?>" class="btn-pay btn-gradient d-block py-3 text-decoration-none">Selesai / Kembali</a>
        </div>

        <!-- Error Area -->
        <div class="glass-card status-overlay" id="error-area">
            <div class="status-icon icon-error"><i class="fas fa-times-circle"></i></div>
            <h3 class="fw-bold mb-2">Pembayaran Gagal</h3>
            <p class="text-secondary small mb-4" id="error-msg">Terjadi kesalahan saat mendebet kartu RFID Anda.</p>
            <button onclick="resetPayment()" class="btn-pay btn-gradient py-3">Coba Metode Lain</button>
        </div>

        <p class="text-center text-secondary-50 small mt-4">Order ID: <span class="font-monospace"><?= $order_id ?></span></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let pollingInterval = null;
        let qrisCreated = false;

        document.addEventListener("DOMContentLoaded", function() {
            initQris();
        });

        // Meminta QRIS WijayaPay
        async function initQris() {
            if (qrisCreated) return;
            
            const formData = new FormData();
            formData.append("sku_code", "<?= $produk['sku_code'] ?>");
            formData.append("order_id", "<?= $order_id ?>");

            try {
                const response = await fetch("<?= base_url('checkout/payQris') ?>", {
                    method: "POST",
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.status === "success") {
                    qrisCreated = true;
                    document.getElementById("qris-loading").style.display = "none";
                    document.getElementById("qris-container").style.display = "block";
                    
                    // Generate QR code image url from qr_data
                    document.getElementById("qris-image").src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(data.qr_data)}`;
                    
                    // Mulai polling status transaksi
                    startPolling();
                } else {
                    document.getElementById("qris-loading").innerHTML = `
                        <i class="fas fa-exclamation-triangle text-danger fa-2x mb-2"></i>
                        <p class="text-danger small">${data.message || 'Gagal membuat transaksi QRIS.'}</p>
                    `;
                }
            } catch (err) {
                document.getElementById("qris-loading").innerHTML = `
                    <i class="fas fa-exclamation-triangle text-danger fa-2x mb-2"></i>
                    <p class="text-danger small">Koneksi gateway terputus.</p>
                `;
            }
        }

        // Mulai Polling Transaksi
        function startPolling() {
            if (pollingInterval) clearInterval(pollingInterval);
            
            pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch("<?= base_url('checkout/checkStatus/'.$order_id) ?>");
                    const data = await response.json();
                    
                    if (data.status === "success") {
                        clearInterval(pollingInterval);
                        showSuccess("Pembayaran QRIS WijayaPay Berhasil Terdeteksi!");
                    } else if (data.status === "failed" || data.status === "expired") {
                        clearInterval(pollingInterval);
                        showError("Transaksi QRIS Kedaluwarsa atau Gagal.");
                    }
                } catch (err) {
                    console.error("Polling error:", err);
                }
            }, 3000);
        }

        // Memproses Tap RFID Card via AJAX
        async function processRfidPay(e) {
            e.preventDefault();
            
            const form = document.getElementById("rfid-form");
            const btn = document.getElementById("btn-rfid-submit");
            const formData = new FormData(form);

            btn.disabled = true;
            btn.innerHTML = "<i class='fas fa-spinner fa-spin me-2'></i>Memproses...";

            try {
                const response = await fetch("<?= base_url('checkout/payRfid') ?>", {
                    method: "POST",
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.status === "success") {
                    showSuccess(data.message);
                } else {
                    showError(data.message);
                }
            } catch (err) {
                showError("Gagal terhubung ke server kasir.");
            } finally {
                btn.disabled = false;
                btn.innerHTML = "<i class='fas fa-check-circle me-1'></i> Verifikasi & Bayar";
            }
        }

        function showSuccess(msg) {
            if (pollingInterval) clearInterval(pollingInterval);
            document.getElementById("payment-card-body").style.display = "none";
            document.getElementById("error-area").style.display = "none";
            document.getElementById("success-area").style.display = "block";
            document.getElementById("success-msg").textContent = msg;
        }

        function showError(msg) {
            if (pollingInterval) clearInterval(pollingInterval);
            document.getElementById("payment-card-body").style.display = "none";
            document.getElementById("success-area").style.display = "none";
            document.getElementById("error-area").style.display = "block";
            document.getElementById("error-msg").textContent = msg;
        }

        function resetPayment() {
            document.getElementById("success-area").style.display = "none";
            document.getElementById("error-area").style.display = "none";
            document.getElementById("payment-card-body").style.display = "block";
            document.getElementById("rfid_uid").value = "";
            qrisCreated = false;
            
            // Re-init QRIS if we are on QRIS tab
            const activeTab = document.querySelector("#paymentTab .nav-link.active").id;
            if (activeTab === "qris-tab") {
                document.getElementById("qris-loading").style.display = "block";
                document.getElementById("qris-container").style.display = "none";
                initQris();
            }
        }

        // Focus RFID input when RFID tab is shown
        const rfidTab = document.getElementById('rfid-tab');
        rfidTab.addEventListener('shown.bs.tab', function (event) {
            document.getElementById('rfid_uid').focus();
            if (pollingInterval) clearInterval(pollingInterval); // Stop polling when user switches away from QRIS
        });

        const qrisTab = document.getElementById('qris-tab');
        qrisTab.addEventListener('shown.bs.tab', function (event) {
            if (qrisCreated) startPolling(); // Resume polling when switching back
        });

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