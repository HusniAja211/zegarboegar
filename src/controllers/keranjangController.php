<?php
require_once __DIR__ . '/../helpers/SessionManager.php';
require_once __DIR__ . '/../models/produkModels.php';
require_once __DIR__ . '/../models/transaksiModels.php';
require_once __DIR__ . '/../models/detailTransaksiModels.php';
require_once __DIR__ . '/../models/memberModels.php';

class KeranjangController
{
    public function index()
    {
        SessionManager::start();
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

    // Tambah produk ke keranjang via kode produk diketik
    public function tambahByKode()
    {
        SessionManager::start();
        header('Content-Type: application/json; charset=utf-8');

        // Ambil JSON dari body
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);
        $kode_produk = $data['kode_produk'] ?? null;

        if (!$kode_produk) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Kode produk tidak dikirim.']);
            exit;
        }

        $produkModel = new Produk();
        $produk = $produkModel->getProdukByKode($kode_produk);

        if (!$produk) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan.']);
            exit;
        }

        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        $id = $produk['id_produk'];

        // Tambah qty kalau sudah ada
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
            'message' => 'Produk ditambahkan via kode.',
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

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
            exit;
        }

        // ✅ Ambil body JSON
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Data JSON tidak valid']);
            exit;
        }

        // ✅ Ambil data dari JSON
        $no_hp = $data['telp'] ?? null;
        $poinDipakai = (float)($data['poinDipakai'] ?? 0);
        $bayar = (float)($data['bayar'] ?? 0);
        $totalTagihan = (float)($data['totalTagihan'] ?? 0);

        // ✅ Ambil keranjang dari session
        $keranjang = $_SESSION['keranjang'] ?? [];
        if (empty($keranjang)) {
            echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong']);
            exit;
        }

        $transaksiModel = new Transaksi();
        $detailModel = new DetailTransaksi();
        $produkModel = new Produk();
        $memberModel = new Member();

        // Pastikan kasir sudah login — ambil data via SessionManager
        $kasirSession = \SessionManager::kasir();
        if (!$kasirSession || !isset($kasirSession['id'])) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Kasir belum login atau ID kasir tidak tersedia di session.'
            ]);
            exit;
        }

        $id_kasir = $kasirSession['id'];
        $tanggal = date('Y-m-d H:i:s');
        $id_member = null;

        // ✅ Cari member berdasarkan nomor HP (jika ada)
        if ($no_hp) {
            $member = $memberModel->getMemberByTelp($no_hp);
            if ($member) {
                $id_member = $member['id_member'];
            }
        }

        // ✅ Hitung total transaksi
        $total = 0;
        foreach ($keranjang as $item) {
            $total += ($item['harga'] * $item['qty']);
        }

        // ✅ Hitung kembalian
        $kembalian = $bayar - $totalTagihan;
        if ($kembalian < 0) $kembalian = 0;

        // ✅ Generate kode transaksi
        $kode_transaksi = 'TRX' . date('ymdHis') . rand(10, 99);

        try {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("
                INSERT INTO t_transaksi 
                (kode_transaksi, id_kasir, id_member, total, bayar, kembalian, tanggal_dibuat)
                VALUES (:kode_transaksi, :id_kasir, :id_member, :total, :bayar, :kembalian, NOW())
            ");
            $stmt->execute([
                ':kode_transaksi' => $kode_transaksi,
                ':id_kasir' => $id_kasir,
                ':id_member' => $id_member,
                ':total' => $total,
                ':bayar' => $bayar,
                ':kembalian' => $kembalian
            ]);
            $id_transaksi = $db->lastInsertId();

            // ✅ Simpan detail transaksi
            $stmtDetail = $db->prepare("
                INSERT INTO t_detail_transaksi 
                (id_transaksi, id_produk, qty, harga, subtotal, fid_kode_transaksi)
                VALUES (:id_transaksi, :id_produk, :qty, :harga, :subtotal, :fid_kode_transaksi)
            ");
            foreach ($keranjang as $item) {
                $subtotal = $item['harga'] * $item['qty'];
                $stmtDetail->execute([
                    ':id_transaksi' => $id_transaksi,
                    ':id_produk' => $item['id_produk'],
                    ':qty' => $item['qty'],
                    ':harga' => $item['harga'],
                    ':subtotal' => $subtotal,
                    ':fid_kode_transaksi' => $kode_transaksi
                ]);

                $produkModel->kurangiStok($item['id_produk'], $item['qty']);
            }

            // ✅ Update poin member (jika ada)
            if ($id_member) {
                $member = $memberModel->getMemberById($id_member);
                if ($member) {
                    $poinBaru = $member['poin'] - $poinDipakai + floor($total * 0.10); // misal 0.1%
                    if ($poinBaru < 0) $poinBaru = 0;

                    $memberModel->updateMember(
                        $member['id_member'],
                        $member['nama_member'],
                        $member['no_hp'],
                        $member['email'],
                        $poinBaru
                    );
                }
            }

            unset($_SESSION['keranjang']);

            echo json_encode([
                'status' => 'success',
                'kode_transaksi' => $kode_transaksi,
                'kembalian' => $kembalian
            ]);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }

}