<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Warung Fitri Lopet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Kita samakan styling dasar dengan v_toko.php */
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Navbar dengan gradien yang sama */
        .navbar { background: linear-gradient(135deg, #1d2b64 0%, #f8cdda 100%); }
        
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        /* Styling Tabel agar lebih modern */
        .table { margin-bottom: 0; }
        .table thead th { 
            background-color: #f8f9fa; 
            border-bottom: 2px solid #dee2e6;
            color: #1d2b64;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }
        
        .btn-action { border-radius: 8px; padding: 5px 12px; }
        .badge-sku { background-color: #e9ecef; color: #1d2b64; font-family: monospace; }
        
        .header-title { color: #1d2b64; font-weight: 800; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-user-shield me-2"></i>Admin Panel</a>
        <div class="d-flex gap-2">
            <a href="<?= base_url('/') ?>" class="btn btn-sm btn-light rounded-pill px-3">
                <i class="fas fa-store me-1"></i> Ke Toko
            </a>
            <a href="<?= base_url('admin/logout') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h4 class="header-title"><i class="fas fa-boxes-stacked me-2"></i>Kelola Stok Warung</h4>
            <p class="text-muted small">Update harga dan stok barang fisik atau digital kamu di sini.</p>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Produk</th>
                        <th>SKU</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($products)): ?>
                        <?php foreach($products as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= $p['nama_produk'] ?></div>
                                <span class="text-muted small"><?= $p['kategori'] ?></span>
                            </td>
                            <td><span class="badge badge-sku"><?= $p['sku_code'] ?></span></td>
                            <td class="fw-bold text-primary">Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
                            <td>
                                <?php if($p['stok'] <= 5): ?>
                                    <span class="badge bg-danger rounded-pill">Sisa <?= $p['stok'] ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success rounded-pill"><?= $p['stok'] ?> Pcs</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group">
                        <div class="btn-group">
                            <a href="<?= base_url('admin/edit/'.$p['id']) ?>" class="btn btn-sm btn-outline-primary btn-action me-2">
                                <i class="fas fa-edit"></i>
                            </a>
                                                        
                            <a href="<?= base_url('admin/delete/'.$p['id']) ?>" 
                               class="btn btn-sm btn-outline-danger btn-action"
                               onclick="return confirm('Hapus <?= $p['nama_produk'] ?> dari etalase?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                Belum ada data barang.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0">
                <h5 class="fw-bold"><i class="fas fa-plus-circle me-2"></i>Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/store') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" placeholder="Es Teh" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">SKU Code</label>
                            <input type="text" name="sku_code" class="form-control" placeholder="ET01" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Stok</label>
                            <input type="number" name="stok" class="form-control" value="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga Jual</label>
                        <input type="number" name="harga_jual" class="form-control" placeholder="3000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kategori</label>
                        <select name="kategori" class="form-select" style="border-radius: 10px;">
                            <option value="Digital">📱 Digital (Pulsa/Voucher)</option>
                            <option value="Fisik">📦 Fisik (Barang/Benda)</option>
                        </select>
                    </div>                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" style="background: #1d2b64;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="mt-4 text-center">
    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus-circle me-2"></i>Tambah Barang Baru
    </button>
</div>

</div>

<footer class="mt-5 py-4 text-center text-muted border-top">
    <p class="small">&copy; 2026 Dashboard Warung Fitri Lopet. <br>Halaman khusus pengelola.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>