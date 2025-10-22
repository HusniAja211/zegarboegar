<?php
require_once __DIR__ . '/../models/kategoriModels.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class KategoriController
{
    private $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new Kategori();
    }

    /**
     * Menampilkan daftar semua kategori
     */
    public function listKategori()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $allKategori = $this->kategoriModel->getKategoriWithCount(); // 🔁 diubah
        require __DIR__ . '/../views/kategori.php';
    }


    public function tambahKategori() {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        require __DIR__ . '/../views/teKategori.php';
    }

    /**
     * Menampilkan detail kategori berdasarkan ID
     */
    public function detailKategori($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $kategori = $this->kategoriModel->getKategoriById($id);
        if (!$kategori) {
            header("Location: /kategori?error=notfound");
            exit;
        }

        require __DIR__ . '/../views/detailKategori.php';
    }

    /**
     * Menyimpan kategori baru ke database
     */
    public function storeKategori()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['namaKategori'] ?? '');

            if (empty($nama)) {
                header("Location: /kategori/tambah?error=empty");
                exit;
            }

            // Cek apakah nama kategori sudah ada
            if ($this->kategoriModel->isNamaExists($nama)) {
                header("Location: /kategori/tambah?error=nama_exists");
                exit;
            }

            // Simpan kategori
            $success = $this->kategoriModel->addKategori($nama);

            if ($success) {
                header("Location: /kategori?success=created");
            } else {
                header("Location: /kategori/tambah?error=save_failed");
            }
            exit;
        }
    }

    /**
     * Menampilkan form edit kategori
     */
    public function editKategori($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $kategori = $this->kategoriModel->getKategoriById($id);
        if (!$kategori) {
            header("Location: /kategori?error=notfound");
            exit;
        }

        require __DIR__ . '/../views/teKategori.php';
    }

    /**
     * Update kategori yang sudah ada
     */
    public function updateKategori()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_kategori'] ?? null;
            $nama = trim($_POST['namaKategori'] ?? '');

            if (empty($id) || empty($nama)) {
                header("Location: /kategori/edit?id=$id&error=empty");
                exit;
            }

            $success = $this->kategoriModel->updateKategori($id, $nama);

            if ($success) {
                header("Location: /kategori?success=updated");
            } else {
                header("Location: /kategori/edit?id=$id&error=update_failed");
            }
            exit;
        }
    }

    /**
     * Hapus kategori berdasarkan ID
     */
   public function deleteKategori($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['error' => 'unauthorized']);
                return;
            }
            header("Location: /login?error=unauthorized");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            exit;
        }

        $kategori = $this->kategoriModel->getKategoriById($id);
        if (!$kategori) {
            if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['error' => 'notfound']);
                return;
            }
            header("Location: /kategori?error=notfound");
            exit;
        }

        // 🔍 Cek apakah masih digunakan oleh produk
        require_once __DIR__ . '/../models/produkModels.php';
        $produkModel = new Produk();
        $count = $produkModel->countProdukByKategori($id);

        if ($count > 0) {
            if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode([
                    'error' => 'kategori_in_use',
                    'message' => 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.'
                ]);
                return;
            }
            header("Location: /kategori?error=kategori_in_use");
            exit;
        }

        // ✅ Hapus jika tidak digunakan
        $deleted = $this->kategoriModel->deleteKategori($id);
        if ($deleted) {
            if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                return;
            }
                header("Location: /kategori?success=kategori_deleted");
        } else {
            if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['error' => 'delete_failed']);
                return;
            }
            header("Location: /kategori?error=delete_failed");
        }
        exit;
    }
}
