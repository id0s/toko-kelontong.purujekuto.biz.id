<?php namespace App\Controllers;

class Admin extends BaseController {
    
    protected $db;
    protected $session;

    public function __construct() {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function index() {
        // Cek apakah sudah login
        if (!$this->session->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $data['products'] = $this->db->table('products')->orderBy('id', 'DESC')->get()->getResultArray();
        return view('v_admin', $data);
    }

public function login() {
    if ($this->request->getPost()) {
        $pass = $this->request->getPost('password');
        if ($pass === 'perintis29') { 
            $this->session->set('is_admin', true);
            return redirect()->to('/admin');
        } else {
            return "<script>alert('Password Salah!'); window.location='/admin/login';</script>";
        }
    }
    
    return '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Admin - Fitri Lopet</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            html, body { height: 100%; margin: 0; }
            body { 
                background: linear-gradient(135deg, #1d2b64 0%, #f8cdda 100%); 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                font-family: "Segoe UI", sans-serif;
                padding: 15px;
            }
            .login-card {
                background: white; 
                border: none; 
                border-radius: 25px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
                width: 100%; 
                max-width: 400px; 
                padding: 40px 30px;
                transition: transform 0.3s;
            }
            .btn-login {
                background: #1d2b64; 
                color: white; 
                border-radius: 12px; 
                padding: 12px; 
                border: none; 
                width: 100%;
                font-size: 1.1rem;
            }
            .btn-login:hover { background: #141e46; color: white; transform: translateY(-2px); }
            .icon-lock { color: #1d2b64; font-size: 3.5rem; margin-bottom: 20px; }
            .form-control:focus { border-color: #1d2b64; box-shadow: none; }
        </style>
    </head>
    <body>
        <div class="login-card text-center">
            <div class="icon-lock"><i class="fas fa-user-shield"></i></div>
            <h3 class="fw-bold mb-1">Admin Panel</h3>
            <p class="text-muted mb-4">Warung Fitri Lopet Celluler</p>
            
            <form method="post">
                <div class="mb-4">
                    <input type="password" name="password" class="form-control text-center" 
                           placeholder="••••••••" required 
                           style="border-radius: 12px; padding: 15px; font-size: 1.2rem; background: #f8f9fa;">
                </div>
                <button type="submit" class="btn btn-login fw-bold shadow">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
                </button>
            </form>
            
            <div class="mt-4 pt-2">
                <a href="'.base_url('/').'" class="text-decoration-none small text-muted hover-link">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Toko
                </a>
            </div>
        </div>
    </body>
    </html>
    ';
}
public function store() {
    if (!$this->session->get('is_admin')) return redirect()->to('/admin/login');

    $id = $this->request->getPost('id'); // Ambil ID hidden

    $data = [
        'nama_produk' => $this->request->getPost('nama_produk'),
        'sku_code'    => $this->request->getPost('sku_code'),
        'harga_jual'  => $this->request->getPost('harga_jual'),
        'stok'        => $this->request->getPost('stok'),
        'kategori'    => $this->request->getPost('kategori'),
    ];

    if ($id) {
        // Mode Edit: Update data produk
        $this->db->table('products')->where('id', $id)->update($data);
    } else {
        // Mode Tambah: Insert data baru
        $this->db->table('products')->insert($data);
    }

    return redirect()->to('/admin');
}
public function edit($id) {
    if (!$this->session->get('is_admin')) return redirect()->to('/admin/login');
    
    // Ambil 1 data produk berdasarkan ID
    $data['p'] = $this->db->table('products')->where('id', $id)->get()->getRowArray();
    
    if (!$data['p']) {
        return "Data tidak ditemukan!";
    }

    return view('v_admin_edit', $data);
}
public function delete($id) {
    if (!$this->session->get('is_admin')) return redirect()->to('/admin/login');
    
    // Ambil 1 data produk berdasarkan ID
    $data['p'] = $this->db->table('products')->where('id', $id)->get()->getRowArray();
    $this->db->table('products')->where('id', $id)->delete();
    return redirect()->to('/admin');
}

public function logout() {
    $this->session->destroy();
    return redirect()->to('/admin/login');
}
}