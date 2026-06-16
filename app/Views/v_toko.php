<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Toko Modern — Fitri Lopet Cell</title>
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
            --primary-accent: #6366f1; /* Indigo */
            --primary-accent-hover: #4f46e5;
            --success-color: #10b981; /* Emerald */
            --danger-color: #ef4444; /* Rose */
            --warning-color: #f59e0b; /* Amber */
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
        }

        /* Navbar */
        .navbar {
            background: rgba(10, 15, 29, 0.7) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 1.2rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            color: var(--text-primary) !important;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .glass-card:hover {
            transform: translateY(-8px);
            background: var(--bg-card-hover);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 12px 30px rgba(99, 102, 241, 0.15);
        }

        .card-img-area {
            background: rgba(255, 255, 255, 0.02);
            padding: 2.5rem 1rem;
            position: relative;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 50px;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .product-icon {
            font-size: 3.5rem;
            background: linear-gradient(135deg, #a5b4fc 0%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: transform 0.4s;
        }

        .glass-card:hover .product-icon {
            transform: scale(1.15) rotate(5deg);
        }

        .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
            line-height: 1.4;
        }

        .product-sku {
            font-family: monospace;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .price-badge {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 10px;
            font-size: 1.2rem;
            font-weight: 800;
            color: #818cf8;
            margin: 1.2rem 0;
            text-align: center;
        }

        /* Buttons */
        .btn-modern {
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--primary-accent) 0%, #ec4899 100%);
            color: white;
        }

        .btn-primary-gradient:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .btn-secondary-dark {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-secondary);
        }

        /* Floating Admin Button */
        .btn-admin-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, var(--primary-accent) 0%, #ec4899 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-admin-float:hover {
            transform: scale(1.1) rotate(15deg);
            color: white;
            box-shadow: 0 12px 30px rgba(236, 72, 153, 0.5);
        }

        /* Search input */
        .search-container {
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .search-box {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            padding: 12px 25px;
            color: white;
            padding-left: 50px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-box:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-accent);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            color: white;
        }

        .search-wrapper {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        /* Toast container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-5">
        <div class="container">
            <a class="navbar-brand fw-extrabold d-flex align-items-center" href="#">
                <span class="p-2 bg-gradient rounded-3 me-2" style="background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%); display: inline-flex;">
                    <i class="fas fa-store text-white"></i>
                </span>
                <span class="brand-gradient fw-bold">Fitri Lopet Cell</span>
            </a>
            
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="<?= base_url('admin/login') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1.5" style="border-color: var(--border-color)">
                    <i class="fas fa-lock me-1"></i> Admin Panel
                </a>
                <a href="<?= base_url('pos') ?>" class="btn btn-sm btn-primary-gradient rounded-pill px-4 py-1.5 fw-bold">
                    <i class="fas fa-desktop me-1"></i> Buka Kasir POS
                </a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="text-center mb-5">
            <h1 class="fw-extrabold" style="letter-spacing: -1px; font-weight: 800;">Etalase Produk Warung</h1>
            <p class="text-secondary">Pilih barang, beli langsung, bayar instant dengan saldo RFID atau QRIS WijayaPay.</p>
        </div>

        <!-- Search Bar -->
        <div class="search-container">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="search-input" class="form-control search-box" placeholder="Cari nama barang atau kode SKU..." onkeyup="filterProducts()">
            </div>
        </div>

        <!-- Alert messages if any -->
        <?php if (session()->getFlashdata('pesan')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 mb-4 text-center shadow-lg" 
                 style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3) !important; color: #34d399;" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('pesan') ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Catalog Grid -->
        <div class="row g-4" id="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    <div class="col-6 col-md-4 col-lg-3 product-item" data-name="<?= strtolower($p['nama_produk']) ?>" data-sku="<?= strtolower($p['sku_code']) ?>">
                        <div class="glass-card">
                            <!-- Card Header Image Area -->
                            <div class="card-img-area">
                                <span class="card-category-badge">
                                    <?= $p['kategori'] == 'Digital' ? '📱 Digital' : '📦 Fisik' ?>
                                </span>
                                <div class="product-icon">
                                    <?php if ($p['kategori'] == 'Digital'): ?>
                                        <i class="fas fa-mobile-alt"></i>
                                    <?php else: ?>
                                        <i class="fas fa-box-open"></i>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="product-title"><?= sanitize($p['nama_produk']) ?></div>
                                <div class="product-sku">SKU: <?= sanitize($p['sku_code']) ?></div>

                                <div class="price-badge">
                                    Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?>
                                </div>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary small">Stok</span>
                                        <?php if ($p['stok'] <= 0): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1">Habis</span>
                                        <?php elseif ($p['stok'] <= 5): ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2.5 py-1">Sisa <?= $p['stok'] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1"><?= $p['stok'] ?> Pcs</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($p['stok'] > 0): ?>
                                        <a href="<?= base_url('proses-beli/'.$p['sku_code']) ?>" class="btn-modern btn-primary-gradient w-100 py-2.5">
                                            <i class="fas fa-shopping-cart text-sm"></i> Beli Sekarang
                                        </a>
                                    <?php else: ?>
                                        <button class="btn-modern btn-secondary-dark w-100 py-2.5" disabled>
                                            <i class="fas fa-times"></i> Habis
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="py-5" style="background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px dashed var(--border-color)">
                        <i class="fas fa-box-open fa-3x text-secondary mb-3"></i>
                        <h5 class="text-secondary">Etalase masih kosong</h5>
                        <p class="text-muted small">Kelola barang belanja melalui Admin Panel terlebih dahulu.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Floating Admin Button -->
    <a href="<?= base_url('admin') ?>" class="btn-admin-float" title="Buka Admin Panel">
        <i class="fas fa-cogs fa-lg"></i>
    </a>

    <!-- Footer -->
    <footer class="mt-5 py-5 text-center text-secondary border-top" style="border-color: var(--border-color) !important;">
        <div class="container">
            <p class="mb-1"><strong>Warung Fitri Lopet Celluler</strong> &copy; 2026</p>
            <p class="small text-secondary-50">Sistem Pembayaran Closed-Loop RFID & QRIS Otomatis Terintegrasi.</p>
        </div>
    </footer>

    <script>
        function filterProducts() {
            const query = document.getElementById('search-input').value.toLowerCase();
            const items = document.querySelectorAll('.product-item');
            
            items.forEach(item => {
                const name = item.getAttribute('data-name');
                const sku = item.getAttribute('data-sku');
                
                if (name.includes(query) || sku.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>