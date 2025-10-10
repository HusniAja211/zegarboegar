<?php
require_once __DIR__ . '/../src/models/kasirModels.php';
require_once __DIR__ . '/../src/models/produkModels.php';
require_once __DIR__ . '/../src/models/transaksiModels.php';
require_once __DIR__ . '/../src/models/kategoriModels.php';
$kasirModel = new Kasir();
$kasirModel->deactivateInactiveKasir(7);

// --- PARSING URL SECARA AMAN ---
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($requestUri, '/');

// Normalisasi root
if ($path === '') {
    $path = '/';
}

// --- DEBUG OPSIONAL ---
// echo "<pre>";
// echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
// echo "Path parsed: " . $path . "\n";
// echo "</pre>";
// die();


// --- ROUTING LOGIC ---
switch (true) {
    // =========================
    // Halaman statis
    // =========================
    case ($path === '/'):
        require __DIR__ . '/../src/views/landingPage.php';
        break;

    case ($path === '/dashboard'):
        require __DIR__ . '/../src/views/dashboard.php';
        break;

    case ($path === '/transaksi'):
        require __DIR__ . '/../src/controllers/transaksiController.php';
        $controller = new TransaksiController();
        $controller->listTransaksi();
        break;
    case ($path === '/test'):
        require __DIR__ . '/../src/views/test.php';
        break;

    // =========================
    // Halaman kasir
    // =========================
    case ($path === '/kasir'):
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->listKasir();
        break;

    //Route dinamis: /kasir/{id}
    case (preg_match('#^/kasir/([0-9]+)$#', $path, $matches)):
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->detailKasir($matches[1]); // tampilkan detail kasir
        break;

    //Route hapus kasir: /kasir/delete/{id}
    case (preg_match('#^/kasir/delete/([0-9]+)$#', $path, $matches)):
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->deleteKasir($matches[1]);
        break;

    //Route update kasir: /kasir/update/{id}
    case (preg_match('#^/kasir/update/([0-9]+)$#', $path, $matches)):
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->updateKasir($matches[1]);
        break;

    // =========================
    // Halaman Produk
    // =========================
    case (preg_match('#^/produk/delete/(\d+)$#', $path, $matches) ? true : false):
        require __DIR__ . '/../src/controllers/produkController.php';
        (new produkController())->deleteProduk($matches[1]);
        break;
        
    case ($path === '/produk'):
        require __DIR__ . '/../src/controllers/produkController.php';
        $controller = new produkController();
        $controller->listproduk();
        break;

    // Route API produk per kategori (untuk Alpine.js)
    case ($path === '/produk/api'):
        require __DIR__ . '/../src/controllers/produkController.php';
        $controller = new produkController();
        $controller->getProdukByKategori();
        break;

    case ($path === '/tambahproduk'):
        require __DIR__ . '/../src/controllers/produkController.php';
        $controller = new produkController();
        $controller->tambahProduk();
        break;

    case ($path === '/produk/store'):
        require __DIR__ . '/../src/controllers/produkController.php';
        $controller = new produkController();
        $controller->storeProduk();
        break;

    case (preg_match('#^/produk/edit/(\d+)$#', $path, $matches) ? true : false):
        require __DIR__ . '/../src/controllers/produkController.php';
        (new produkController())->editProduk($matches[1]);
        break;

   case ($path === '/produk/update'):
        require __DIR__ . '/../src/controllers/produkController.php';
        (new produkController())->updateProduk();
        break;

    

    // =========================
    // Sebelum login
    // =========================
    case ($path === '/register'):
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->register();
        break;

    case ($path === '/login'):
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->login();
        break;

    case ($path === '/forgetPassword'):
        require __DIR__ . '/../src/controllers/kasirController.php';
        $controller = new KasirController();
        $controller->forgetPassword();
        break;

    case '/logout':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/../src/controllers/kasirController.php';
            $controller = new KasirController();
            $controller->logout();
        } else {
            header("Location: /dashboard");
            exit;
        }
        break;

    // =========================
    // Default: 404
    // =========================
    default:
        http_response_code(404);
        require __DIR__ . '/../src/views/404.php';
        break;
}
