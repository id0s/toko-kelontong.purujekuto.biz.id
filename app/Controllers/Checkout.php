<?php 

namespace App\Controllers;

// Load payment gateway configuration
if (file_exists('/home/payment-gw/config.php')) {
    require_once '/home/payment-gw/config.php';
}

class Checkout extends BaseController 
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Mengambil semua produk untuk etalase
        $data['products'] = $this->db->table('products')->orderBy('id', 'DESC')->get()->getResultArray();
        return view('v_toko', $data);
    }

    public function process($sku = null) 
    {
        // 1. Cari Produk
        $produk = $this->db->table('products')->where('sku_code', $sku)->get()->getRowArray();
        if (!$produk) {
            $produk = $this->db->table('products')->where('id', $sku)->get()->getRowArray();
        }

        if (!$produk) {
            return "<script>alert('Barang tidak ditemukan!'); window.location='/';</script>";
        }

        if ($produk['stok'] <= 0) {
            return "<script>alert('Stok barang habis!'); window.location='/';</script>";
        }

        $order_id = 'FL-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        return view('v_pembayaran', [
            'produk' => $produk,
            'order_id' => $order_id,
            'total_harga' => $produk['harga_jual']
        ]);
    }

    /**
     * Memproses Pembayaran via Saldo Kartu RFID (Closed-loop)
     */
    public function payRfid()
    {
        $uid = strtoupper(trim($this->request->getPost('rfid_uid') ?? ''));
        $sku = trim($this->request->getPost('sku_code') ?? '');
        $order_id = trim($this->request->getPost('order_id') ?? '');

        if (empty($uid) || empty($sku) || empty($order_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Semua data wajib diisi']);
        }

        // Cari Produk
        $produk = $this->db->table('products')->where('sku_code', $sku)->get()->getRowArray();
        if (!$produk || $produk['stok'] <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak valid atau stok habis']);
        }

        // Cari User Kartu RFID
        $user = $this->db->table('users')->where('rfid_uid', $uid)->get()->getRowArray();
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kartu RFID tidak terdaftar']);
        }

        if ($user['status'] !== 'active') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kartu RFID tidak aktif / diblokir']);
        }

        $total_bayar = (float)$produk['harga_jual'];

        if ((float)$user['saldo'] < $total_bayar) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Saldo kartu tidak cukup. Sisa saldo: Rp ' . number_format($user['saldo'], 0, ',', '.')
            ]);
        }

        try {
            // Jalankan transaksi database
            $this->db->transBegin();

            // 1. Potong Saldo User
            $this->db->query("UPDATE users SET saldo = saldo - ? WHERE id = ?", [$total_bayar, $user['id']]);

            // 2. Potong Stok Produk
            $this->db->table('products')
                     ->where('id', $produk['id'])
                     ->update(['stok' => $produk['stok'] - 1]);

            // 3. Simpan Transaksi Toko (Update jika sudah ada record pending dari QRIS)
            $existingTrx = $this->db->table('transaksi')->where('order_id', $order_id)->get()->getRowArray();
            if ($existingTrx) {
                $this->db->table('transaksi')
                         ->where('order_id', $order_id)
                         ->update([
                             'metode_bayar' => 'rfid',
                             'rfid_uid'     => $uid,
                             'status'       => 'success',
                             'created_at'   => date('Y-m-d H:i:s')
                         ]);
            } else {
                $this->db->table('transaksi')->insert([
                    'order_id'     => $order_id,
                    'product_id'   => $produk['id'],
                    'total_harga'  => $total_bayar,
                    'metode_bayar' => 'rfid',
                    'rfid_uid'     => $uid,
                    'status'       => 'success',
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            }

            // 4. Catat Transaksi di Log Payment Gateway (pusat)
            $this->db->table('transactions')->insert([
                'user_id'      => $user['id'],
                'order_id'     => $order_id,
                'jenis'        => 'payment',
                'jumlah'       => $total_bayar,
                'metode_bayar' => 'RFID Card',
                'gateway'      => 'manual',
                'status'       => 'success',
                'keterangan'   => 'Belanja produk: ' . $produk['nama_produk'] . ' di Toko Kelontong',
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            // 5. Catat log kartu
            $this->db->table('rfid_logs')->insert([
                'rfid_uid'  => $uid,
                'aksi'      => 'tap_pay',
                'device_id' => 'TOKO-CUSTOMER',
                'result'    => 'success',
                'detail'    => 'Belanja Rp ' . $total_bayar,
                'created_at'=> date('Y-m-d H:i:s')
            ]);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memproses transaksi database']);
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pembayaran berhasil! Saldo terpotong: Rp ' . number_format($total_bayar, 0, ',', '.')
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Meminta Dynamic QRIS WijayaPay secara Realtime
     */
    public function payQris()
    {
        $sku = trim($this->request->getPost('sku_code') ?? '');
        $order_id = trim($this->request->getPost('order_id') ?? '');

        if (empty($sku) || empty($order_id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
        }

        // Cari Produk
        $produk = $this->db->table('products')->where('sku_code', $sku)->get()->getRowArray();
        if (!$produk || $produk['stok'] <= 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan atau stok habis']);
        }

        $total_bayar = (int)$produk['harga_jual'];

        // Panggil API WijayaPay
        $merchantCode = defined('WIJAYAPAY_MERCHANT_CODE') ? WIJAYAPAY_MERCHANT_CODE : '';
        $apiKey = defined('WIJAYAPAY_API_KEY') ? WIJAYAPAY_API_KEY : '';
        
        if (empty($merchantCode) || empty($apiKey)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Konfigurasi API WijayaPay tidak ditemukan']);
        }

        $signature = md5($merchantCode . $apiKey . $order_id);
        $url = (defined('WIJAYAPAY_IS_PRODUCTION') && WIJAYAPAY_IS_PRODUCTION) 
            ? 'https://wijayapay.com/api/transaction/create'
            : 'https://sandbox.wijayapay.com/api/transaction/create';

        $postData = [
            'code_merchant' => $merchantCode,
            'api_key' => $apiKey,
            'ref_id' => $order_id,
            'code_payment' => 'QRIS',
            'nominal' => $total_bayar
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
            'X-Signature: ' . $signature
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Koneksi ke gateway gagal: ' . $curlError]);
        }

        $resJson = json_decode($response, true);
        
        $isSuccess = true;
        if (!isset($resJson['success']) || $resJson['success'] === false) {
            $isSuccess = false;
        }

        if (!$isSuccess) {
            $msg = $resJson['message'] ?? $resJson['msg'] ?? 'Gagal membuat QRIS di WijayaPay';
            return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
        }

        $qrData = $resJson['data']['qr_string'] ?? '';
        $paymentName = $resJson['data']['payment_name'] ?? 'QRIS';

        if (empty($qrData)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Respons gateway tidak menyertakan data QRIS']);
        }

        // Simpan Transaksi Toko dengan status pending
        $this->db->table('transaksi')->insert([
            'order_id'     => $order_id,
            'product_id'   => $produk['id'],
            'total_harga'  => $total_bayar,
            'metode_bayar' => 'qris',
            'status'       => 'pending',
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'qr_data' => $qrData,
            'payment_name' => $paymentName,
            'order_id' => $order_id
        ]);
    }

    /**
     * Memeriksa Status Transaksi secara Realtime (Polling)
     */
    public function checkStatus($order_id = null)
    {
        if (empty($order_id)) {
            return $this->response->setJSON(['status' => 'pending']);
        }

        $transaksi = $this->db->table('transaksi')->where('order_id', $order_id)->get()->getRowArray();
        
        if ($transaksi) {
            return $this->response->setJSON([
                'status' => $transaksi['status'],
                'message' => $transaksi['status'] === 'success' ? 'Pembayaran berhasil dikonfirmasi!' : 'Menunggu pembayaran...'
            ]);
        }

        return $this->response->setJSON(['status' => 'pending']);
    }

    public function konfirmasi($order_id = null)
    {
        if ($order_id == null) return "Order ID kosong!";

        // Cek dulu transaksi toko
        $trx = $this->db->table('transaksi')->where('order_id', $order_id)->get()->getRowArray();
        if ($trx && $trx['status'] === 'pending') {
            $this->db->transBegin();

            // 1. Update status di database
            $this->db->table('transaksi')
                     ->where('order_id', $order_id)
                     ->update(['status' => 'success']);

            // 2. Kurangi stok
            $produk = $this->db->table('products')->where('id', $trx['product_id'])->get()->getRowArray();
            if ($produk) {
                $this->db->table('products')
                         ->where('id', $produk['id'])
                         ->update(['stok' => max(0, $produk['stok'] - 1)]);
            }

            $this->db->transCommit();
        }

        // Redirect balik ke halaman utama
        return redirect()->to(base_url('/'))->with('pesan', 'Pembayaran Berhasil Dikonfirmasi!');
    }
}