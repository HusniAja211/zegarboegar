<?php
require_once __DIR__ . '/../helpers/SessionManager.php';
require_once __DIR__ . '/../models/produkModels.php';
require_once __DIR__ . '/../models/transaksiModels.php';
require_once __DIR__ . '/../models/detailTransaksiModels.php';

class KeranjangController
{
    public function index()
    {
        SessionManager::start();
        // ✅ Ganti 'cart' menjadi 'keranjang'
        $cart = $_SESSION['keranjang'] ?? []; 
        require __DIR__ . '/../views/keranjang.php';
    }

    // Tambah produk ke keranjang
    public function tambah($id)
        {
            SessionManager::start();
            $produkModel = new Produk();
            $produk = $produkModel->getProdukById($id);

            // --- Tambah header JSON dulu biar fetch().json() tidak error ---
            header('Content-Type: application/json; charset=utf-8');

            if (!$produk) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Produk tidak ditemukan.'
                ]);
                exit;
            }

            // ✅ Pastikan keranjang ada
            if (!isset($_SESSION['keranjang'])) {
                $_SESSION['keranjang'] = [];
            }

            // ✅ Tambah qty kalau produk sudah ada
            if (isset($_SESSION['keranjang'][$id])) {
                $_SESSION['keranjang'][$id]['qty']++;
            } else {
                $_SESSION['keranjang'][$id] = [
                    'id_produk' => $produk['id_produk'],
                    'nama_produk' => $produk['nama_produk'],
                    'harga' => $produk['harga_jual'],
                    'qty' => 1
                ];
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'keranjang' => $_SESSION['keranjang']
            ]);
            exit;
    }

    public function kurangi($id)
    {
        SessionManager::start();
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['keranjang'][$id])) {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan di keranjang.'
            ]);
            exit;
        }

        // Kurangi jumlah produk
        $_SESSION['keranjang'][$id]['qty']--;

        // Hapus produk jika jumlah 0
        if ($_SESSION['keranjang'][$id]['qty'] <= 0) {
            unset($_SESSION['keranjang'][$id]);
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Jumlah produk diperbarui.',
            'keranjang' => $_SESSION['keranjang']
        ]);
        exit;
    }


    // Hapus satu item dari keranjang
    public function hapus($id)
    {
        SessionManager::start();

        // Mode AJAX
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json; charset=utf-8');

            if (isset($_SESSION['keranjang'][$id])) {
                unset($_SESSION['keranjang'][$id]);
                echo json_encode(['status' => 'success', 'message' => 'Produk dihapus dari keranjang.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan di keranjang.']);
            }
            exit;
        }

        // Mode redirect biasa
        if (isset($_SESSION['keranjang'][$id])) {
            unset($_SESSION['keranjang'][$id]);
            header("Location: /keranjang?success=deleted");
        } else {
            header("Location: /keranjang?error=notfound");
        }
        exit;
    }


    // Kosongkan seluruh keranjang
    public function kosongkan()
    {
        SessionManager::start();
        // ✅ Ganti 'cart' menjadi 'keranjang'
        unset($_SESSION['keranjang']);
        header("Location: /keranjang?success=cleared");
        exit;
    }

    // Proses checkout
    public function checkout()
    {
        SessionManager::start();

        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        // ✅ Ganti 'cart' menjadi 'keranjang'
        $cart = $_SESSION['keranjang'] ?? [];
        if (empty($cart)) {
            header("Location: /keranjang?error=empty");
            exit;
        }

        $transaksiModel = new Transaksi();
        $detailModel = new DetailTransaksi();

        $idKasir = $_SESSION['user']['id_kasir'] ?? null; 
        $tanggal = date('Y-m-d H:i:s');
        $total = array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], $cart));

        // 1️⃣ Simpan transaksi utama
        $idTransaksi = $transaksiModel->insertTransaksi($idKasir, $tanggal, $total);

        // 2️⃣ Simpan detail transaksi
        foreach ($cart as $item) {
            $detailModel->insertDetail($idTransaksi, $item['id_produk'], $item['qty'], $item['harga']);
        }

        // 3️⃣ Kosongkan keranjang (menggunakan nama 'keranjang')
        unset($_SESSION['keranjang']);

        header("Location: /transaksi/$idTransaksi?success=checkout");
        exit;
    }
}