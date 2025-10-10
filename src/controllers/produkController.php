<?php
require_once __DIR__ . '/../models/produkModels.php';
require_once __DIR__ . '/../models/kategoriModels.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class produkController
{

    private $produkModel;
    private $kategoriModel;

    public function __construct()
    {
        $this->produkModel = new Produk();
        $this->kategoriModel = new Kategori();
    }

    public function listProduk()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $produkModel = new Produk();
        $kategoriList = $produkModel->getAllKategori();

        require __DIR__ . '/../views/produk.php';
    }

    public function detailProduk($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $produkModel = new Produk();
        $detailProduk = $produkModel->getProdukById($id);

        if (!$detailProduk) {
            header("Location: /produk?error=notfound");
            exit;
        }

        require __DIR__ . '/../views/detailProduk.php';
    }

    public function getProdukByKategori()
    {
        header('Content-Type: application/json');

        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        if (!isset($_GET['kategori']) || empty($_GET['kategori'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Kategori tidak ditemukan']);
            return;
        }

        $kategori = $_GET['kategori'];
        $produkModel = new Produk();
        $produkList = $produkModel->getProdukByKategori($kategori);

        echo json_encode($produkList);
    }

     public function tambahProduk()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        // Ambil semua kategori untuk dropdown
        $kategoriModel = new Kategori();
        $listKategori = $kategoriModel->getAllKategori();

        // Tampilkan view tambah produk
        require __DIR__ . '/../views/tambahProduk.php';
    }

    public function storeProduk()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $produkModel = new Produk();

        // Ambil data POST
        $kode = $_POST['kode_produk'] ?? '';
        $nama = $_POST['nama_produk'] ?? '';
        $idKategori = $_POST['id_kategori'] ?? '';
        $modal = $_POST['modal'] ?? 0;
        $hargaJual = $_POST['harga_jual'] ?? 0;
        $keuntungan = $hargaJual - $modal ?? 0;
        $stok = $_POST['stok'] ?? 0;
        $kadaluarsa = $_POST['kadaluarsa'] ?? null;
        $deskripsi = $_POST['deskripsi'] ?? '';
        $barcode = $kode . '_barcode';

        // 🔍 Cek apakah kode_produk atau nama_produk sudah ada
        if ($produkModel->isKodeExists($kode)) {
            header("Location: /tambahproduk?error=kode_exists");
            exit;
        }

        if ($produkModel->isNamaExists($nama)) {
            header("Location: /tambahproduk?error=nama_exists");
            exit;
        }

        // Upload gambar
        $gambarPath = null;
        if (!empty($_FILES['gambar']['name'])) {
            $uploadDir = __DIR__ . '/../../public/images/produk/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = uniqid('prod_') . '_' . basename($_FILES['gambar']['name']);
            $targetFile = $uploadDir . $fileName;

            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($fileType, $allowed)) {
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
                    $gambarPath = '/images/produk/' . $fileName;
                } else {
                    header("Location: /tambah-produk?error=upload_failed");
                    exit;
                }
            } else {
                header("Location: /tambah-produk?error=invalid_file");
                exit;
            }
        }

        // Simpan ke database
        $success = $produkModel->createProduk([
            'kode_produk' => $kode,
            'nama_produk' => $nama,
            'id_kategori' => $idKategori,
            'keuntungan' => $keuntungan,
            'modal' => $modal,
            'harga_jual' => $hargaJual,
            'stok' => $stok,
            'kadaluarsa' => $kadaluarsa,
            'deskripsi' => $deskripsi,
            'gambar' => $gambarPath,
        ]);

        if ($success) {
            header("Location: /produk?success=created");
        } else {
            header("Location: /tambah-produk?error=save_failed");
        }
    }

    public function editProduk($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $produk = $this->produkModel->getProdukById($id); // pastikan nama method-nya sama di model
        $listKategori = $this->kategoriModel->getAllKategori();

        if (!$produk) {
            header("Location: /produk?error=notfound");
            exit;
        }

        require __DIR__ . '/../views/tambahProduk.php'; // Reuse form
    }

    public function updateProduk()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_produk'];

            $data = [
                'nama_produk' => trim($_POST['nama_produk']),
                'modal' => $_POST['modal'] ?? null,
                'harga_jual' => $_POST['harga_jual'] ?? null,
                'stok' => $_POST['stok'] ?? null,
                'id_kategori' => $_POST['id_kategori'] ?? null,
                'kadaluarsa' => $_POST['kadaluarsa'] ?? null,
                'deskripsi' => $_POST['deskripsi'] ?? null,
            ];

            // Gambar lama
            $gambarLama = $_POST['gambar_lama'] ?? null;

            // Jika user upload gambar baru
            if (!empty($_FILES['gambar']['name'])) {
                $uploadDir = __DIR__ . '/../../public/images/produk/';
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

                $filename = 'prod_' . uniqid() . '_' . basename($_FILES['gambar']['name']);
                $targetFile = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
                    $data['gambar'] = '/images/produk/' . $filename;
                }
            } else {
                // Kalau tidak upload baru, simpan gambar lama
                $data['gambar'] = $gambarLama;
            }

            $this->produkModel->updateProduk($id, $data);

            header("Location: /produk?success=updated");
            exit;
        }
    }

    public function deleteProduk($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            exit;
        }

        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $produkModel = new Produk();
        $produk = $produkModel->getProdukById($id);

        if (!$produk) {
            header("Location: /produk?error=notfound");
            exit;
        }

        // Hapus gambar (jika ada)
        if (!empty($produk['gambar'])) {
            $filePath = __DIR__ . '/../../public' . $produk['gambar'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus dari database
        $produkModel->deleteProduk($id);

        // Redirect ke produk dengan notifikasi sukses
        header("Location: /produk?success=deleted");
        exit;
    }



}
