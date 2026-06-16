<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Fitri Lopet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .header-bg { 
            background: linear-gradient(135deg, #1d2b64 0%, #f8cdda 100%); 
            height: 150px; width: 100%; position: absolute; top: 0; z-index: -1; 
        }
        .card-edit { 
            border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            margin-top: 50px; overflow: hidden;
        }
        .btn-update { 
            background: #1d2b64; color: white; border-radius: 12px; padding: 12px; 
            border: none; font-weight: 600; transition: 0.3s;
        }
        .btn-update:hover { background: #141e46; transform: translateY(-2px); color: white; }
        .form-label { font-weight: 600; color: #444; font-size: 0.9rem; }
        .form-control, .form-select { border-radius: 10px; padding: 10px; border: 1px solid #ddd; }
        .form-control:focus { border-color: #1d2b64; box-shadow: none; }
    </style>
</head>
<body>

<div class="header-bg"></div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="d-flex justify-content-between align-items-center mb-3 text-white">
                <h4 class="fw-bold m-0"><i class="fas fa-pen-nib me-2"></i>Edit Informasi Produk</h4>
                <a href="<?= base_url('admin') ?>" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm">
                    <i class="fas fa-times me-1"></i> Batal
                </a>
            </div>

            <div class="card card-edit p-4 p-md-5 bg-white">
                <form action="<?= base_url('admin/store') ?>" method="post">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">

                    <div class="mb-4 text-center">
                        <span class="badge bg-light text-primary p-2 px-3 rounded-pill">ID Produk: #<?= $p['id'] ?></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" value="<?= $p['nama_produk'] ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU Code</label>
                            <input type="text" name="sku_code" class="form-control" value="<?= $p['sku_code'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="Digital" <?= $p['kategori'] == 'Digital' ? 'selected' : '' ?>>📱 Digital (Pulsa/Voucher)</option>
                                <option value="Fisik" <?= $p['kategori'] == 'Fisik' ? 'selected' : '' ?>>📦 Fisik (Barang/Benda)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Jual (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                <input type="number" name="harga_jual" class="form-control border-start-0" value="<?= $p['harga_jual'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok Saat Ini</label>
                            <input type="number" name="stok" class="form-control" value="<?= $p['stok'] ?>" required>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <div class="d-grid">
                        <button type="submit" class="btn btn-update">
                            <i class="fas fa-save me-2"></i> Perbarui Data Produk
                        </button>
                    </div>
                </form>
            </div>
            
            <p class="text-center mt-4 text-muted small">Warung Fitri Lopet Celluler &copy; 2026</p>
        </div>
    </div>
</div>

</body>
</html>