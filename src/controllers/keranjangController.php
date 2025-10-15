<?php
require_once __DIR__ . '/../helpers/SessionManager.php';
require_once __DIR__ . '/../models/produkModel.php';
require_once __DIR__ . '/../models/transaksiModel.php';
require_once __DIR__ . '/../models/detailTransaksiModel.php';

class KeranjangController
{
    public function index()
    {
        SessionManager::start();
        $cart = $_SESSION['cart'] ?? [];
        require __DIR__ . '/../views/keranjang.php';
    }

    // Tambah produk ke keranjang
    public function tambah($id)
    {
        SessionManager::start();
        $produkModel = new Produk();
        $produk = $produkModel->getProdukById($id);

        if (!$produk) {
            header("Location: /keranjang?error=notfound");
            exit;
        }

        // Buat keranjang jika belum ada
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Jika produk sudah ada di keranjang, tambah qty
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty']++;
        } else {
            $_SESSION['cart'][$id] = [
                'id_produk' => $produk['id_produk'],
                'nama_produk' => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'qty' => 1
            ];
        }

        header("Location: /keranjang?success=added");
        exit;
    }

    // Hapus satu item dari keranjang
    public function hapus($id)
    {
        SessionManager::start();
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
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
        unset($_SESSION['cart']);
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

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header("Location: /keranjang?error=empty");
            exit;
        }

        $transaksiModel = new Transaksi();
        $detailModel = new DetailTransaksi();

        $idKasir = $_SESSION['user']['id_kasir'] ?? null; // ubah sesuai login user
        $tanggal = date('Y-m-d H:i:s');
        $total = array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], $cart));

        // 1️⃣ Simpan transaksi utama
        $idTransaksi = $transaksiModel->insertTransaksi($idKasir, $tanggal, $total);

        // 2️⃣ Simpan detail transaksi
        foreach ($cart as $item) {
            $detailModel->insertDetail($idTransaksi, $item['id_produk'], $item['qty'], $item['harga']);
        }

        // 3️⃣ Kosongkan keranjang
        unset($_SESSION['cart']);

        header("Location: /transaksi/$idTransaksi?success=checkout");
        exit;
    }
}
