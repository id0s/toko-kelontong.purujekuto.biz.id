<?php

namespace App\Controllers;

if (file_exists('/home/payment-gw/config.php')) {
    require_once '/home/payment-gw/config.php';
}

class POS extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['products'] = $this->db->table('products')->orderBy('nama_produk', 'ASC')->get()->getResultArray();
        return view('v_pos', $data);
    }

    /**
     * Memeriksa Data & Saldo Kartu RFID (untuk AJAX Kasir)
     */
    public function checkCard($uid = '')
    {
        $uid = strtoupper(trim($uid));
        if (empty($uid)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'UID Kosong']);
        }

        $user = $this->db->table('users')->where('rfid_uid', $uid)->get()->getRowArray();
        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kartu RFID tidak terdaftar']);
        }

        if ($user['status'] !== 'active') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kartu sedang diblokir']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'nama' => $user['nama'],
            'saldo' => (float)$user['saldo']
        ]);
    }

    /**
     * Memproses Transaksi POS Kasir
     */
    public function checkout()
    {
        $metode = trim($this->request->getPost('metode_bayar') ?? 'tunai');
        $rfidUid = strtoupper(trim($this->request->getPost('rfid_uid') ?? ''));
        $cartData = $this->request->getPost('cart');

        $cart = json_decode($cartData, true);
        if (empty($cart)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Keranjang belanja kosong']);
        }

        // Hitung total belanja & validasi stok
        $totalBelanja = 0;
        $validatedItems = [];

        foreach ($cart as $item) {
            $pId = (int)$item['id'];
            $qty = (int)$item['qty'];

            $produk = $this->db->table('products')->where('id', $pId)->get()->getRowArray();
            if (!$produk) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Barang ID #' . $pId . ' tidak ditemukan']);
            }

            if ($produk['stok'] < $qty) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Stok "' . $produk['nama_produk'] . '" tidak cukup. Sisa: ' . $produk['stok']
                ]);
            }

            $subtotal = (float)$produk['harga_jual'] * $qty;
            $totalBelanja += $subtotal;

            $validatedItems[] = [
                'produk' => $produk,
                'qty' => $qty,
                'harga_satuan' => (float)$produk['harga_jual'],
                'subtotal' => $subtotal
            ];
        }

        $orderId = 'FL-POS-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        // ── PEMBAYARAN: TUNAI (CASH) ─────────────────────
        if ($metode === 'tunai') {
            try {
                $this->db->transBegin();

                // 1. Buat Transaksi Toko
                $this->db->table('transaksi')->insert([
                    'order_id'     => $orderId,
                    'total_harga'  => $totalBelanja,
                    'metode_bayar' => 'tunai',
                    'status'       => 'success',
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
                $insertId = $this->db->insertID();

                // 2. Simpan Detail & Potong Stok
                foreach ($validatedItems as $item) {
                    $this->db->table('transaksi_detail')->insert([
                        'transaksi_id' => $insertId,
                        'product_id'   => $item['produk']['id'],
                        'qty'          => $item['qty'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal'     => $item['subtotal']
                    ]);

                    $this->db->table('products')
                             ->where('id', $item['produk']['id'])
                             ->update(['stok' => $item['produk']['stok'] - $item['qty']]);
                }

                if ($this->db->transStatus() === false) {
                    $this->db->transRollback();
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan transaksi kasir']);
                }

                $this->db->transCommit();
                return $this->response->setJSON(['status' => 'success', 'order_id' => $orderId, 'message' => 'Transaksi tunai berhasil!']);

            } catch (\Exception $e) {
                $this->db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Kesalahan database: ' . $e->getMessage()]);
            }
        }

        // ── PEMBAYARAN: KARTU RFID ───────────────────────
        elseif ($metode === 'rfid') {
            if (empty($rfidUid)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'UID Kartu RFID tidak boleh kosong']);
            }

            // Cari User
            $user = $this->db->table('users')->where('rfid_uid', $rfidUid)->get()->getRowArray();
            if (!$user) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Kartu RFID tidak terdaftar']);
            }

            if ($user['status'] !== 'active') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Kartu sedang diblokir']);
            }

            if ((float)$user['saldo'] < $totalBelanja) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Saldo kartu tidak cukup. Sisa saldo: Rp ' . number_format($user['saldo'], 0, ',', '.')
                ]);
            }

            try {
                $this->db->transBegin();

                // 1. Potong Saldo User
                $this->db->query("UPDATE users SET saldo = saldo - ? WHERE id = ?", [$totalBelanja, $user['id']]);

                // 2. Buat Transaksi Toko
                $this->db->table('transaksi')->insert([
                    'order_id'     => $orderId,
                    'total_harga'  => $totalBelanja,
                    'metode_bayar' => 'rfid',
                    'rfid_uid'     => $rfidUid,
                    'status'       => 'success',
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
                $insertId = $this->db->insertID();

                // 3. Simpan Detail & Potong Stok
                foreach ($validatedItems as $item) {
                    $this->db->table('transaksi_detail')->insert([
                        'transaksi_id' => $insertId,
                        'product_id'   => $item['produk']['id'],
                        'qty'          => $item['qty'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal'     => $item['subtotal']
                    ]);

                    $this->db->table('products')
                             ->where('id', $item['produk']['id'])
                             ->update(['stok' => $item['produk']['stok'] - $item['qty']]);
                }

                // 4. Catat Transaksi di Payment Gateway (Pusat)
                $this->db->table('transactions')->insert([
                    'user_id'      => $user['id'],
                    'order_id'     => $orderId,
                    'jenis'        => 'payment',
                    'jumlah'       => $totalBelanja,
                    'metode_bayar' => 'RFID Card',
                    'gateway'      => 'manual',
                    'status'       => 'success',
                    'keterangan'   => 'Belanja kasir POS di Toko Kelontong',
                    'created_at'   => date('Y-m-d H:i:s')
                ]);

                // 5. Catat log kartu
                $this->db->table('rfid_logs')->insert([
                    'rfid_uid'  => $rfidUid,
                    'aksi'      => 'tap_pay',
                    'device_id' => 'KASIR-POS',
                    'result'    => 'success',
                    'detail'    => 'Belanja POS Rp ' . $totalBelanja,
                    'created_at'=> date('Y-m-d H:i:s')
                ]);

                if ($this->db->transStatus() === false) {
                    $this->db->transRollback();
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memproses transaksi RFID']);
                }

                $this->db->transCommit();
                return $this->response->setJSON(['status' => 'success', 'order_id' => $orderId, 'message' => 'Pembayaran saldo RFID Berhasil!']);

            } catch (\Exception $e) {
                $this->db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Kesalahan database: ' . $e->getMessage()]);
            }
        }

        // ── PEMBAYARAN: QRIS WIJAYAPAY ────────────────────
        elseif ($metode === 'qris') {
            $merchantCode = defined('WIJAYAPAY_MERCHANT_CODE') ? WIJAYAPAY_MERCHANT_CODE : '';
            $apiKey = defined('WIJAYAPAY_API_KEY') ? WIJAYAPAY_API_KEY : '';

            if (empty($merchantCode) || empty($apiKey)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'API Key WijayaPay belum dikonfigurasi di config.php']);
            }

            // Hitung signature & request QRIS ke WijayaPay
            $signature = md5($merchantCode . $apiKey . $orderId);
            $url = (defined('WIJAYAPAY_IS_PRODUCTION') && WIJAYAPAY_IS_PRODUCTION) 
                ? 'https://wijayapay.com/api/transaction/create'
                : 'https://sandbox.wijayapay.com/api/transaction/create';

            $postData = [
                'code_merchant' => $merchantCode,
                'api_key' => $apiKey,
                'ref_id' => $orderId,
                'code_payment' => 'QRIS',
                'nominal' => $totalBelanja
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
            $isSuccess = isset($resJson['success']) && $resJson['success'] === true;

            if (!$isSuccess) {
                $msg = $resJson['message'] ?? $resJson['msg'] ?? 'Gagal membuat QRIS di WijayaPay';
                return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
            }

            $qrData = $resJson['data']['qr_string'] ?? '';

            if (empty($qrData)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gateway tidak mengembalikan payload QRIS yang valid']);
            }

            try {
                $this->db->transBegin();

                // 1. Buat Transaksi Toko (status pending)
                $this->db->table('transaksi')->insert([
                    'order_id'     => $orderId,
                    'total_harga'  => $totalBelanja,
                    'metode_bayar' => 'qris',
                    'status'       => 'pending',
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
                $insertId = $this->db->insertID();

                // 2. Simpan Detail (stok tidak dipotong sampai status success lewat webhook/override)
                foreach ($validatedItems as $item) {
                    $this->db->table('transaksi_detail')->insert([
                        'transaksi_id' => $insertId,
                        'product_id'   => $item['produk']['id'],
                        'qty'          => $item['qty'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal'     => $item['subtotal']
                    ]);
                }

                if ($this->db->transStatus() === false) {
                    $this->db->transRollback();
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mencatat data transaksi QRIS']);
                }

                $this->db->transCommit();
                return $this->response->setJSON([
                    'status' => 'qris_pending',
                    'qr_data' => $qrData,
                    'order_id' => $orderId
                ]);

            } catch (\Exception $e) {
                $this->db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Kesalahan database: ' . $e->getMessage()]);
            }
        }
    }

    /**
     * Memantau Status Pembayaran QRIS (AJAX Polling Kasir)
     */
    public function pollStatus($orderId = '')
    {
        $orderId = trim($orderId);
        if (empty($orderId)) {
            return $this->response->setJSON(['status' => 'pending']);
        }

        $trx = $this->db->table('transaksi')->where('order_id', $orderId)->get()->getRowArray();
        if ($trx) {
            return $this->response->setJSON(['status' => $trx['status']]);
        }

        return $this->response->setJSON(['status' => 'pending']);
    }
}
