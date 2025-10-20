<?php
// =========================================================================
// I. Persiapan Awal
// =========================================================================

// --- Autoload Models ---
// Sebaiknya gunakan autoloader (misal, Composer) jika memungkinkan.
// Jika tidak, pastikan semua model yang dibutuhkan dimuat.
require_once __DIR__ . '/../src/models/kasirModels.php';
require_once __DIR__ . '/../src/models/produkModels.php';
require_once __DIR__ . '/../src/models/transaksiModels.php';
require_once __DIR__ . '/../src/models/kategoriModels.php';
require_once __DIR__ . '/../src/models/memberModels.php'; // Pastikan memberModels ada jika digunakan

// --- Inisialisasi Model & Maintenance ---
$kasirModel = new Kasir();
$kasirModel->deactivateInactiveKasir(7);


// --- PARSING URL SECARA AMAN ---
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Hapus trailing slash dan normalisasi root '/'
$path = rtrim($requestUri, '/');
if ($path === '') {
    $path = '/';
}

// --- FUNGSI HELPER UNTUK MENGURANGI REDUNDANSI ---
/**
 * Memuat Controller, menginisialisasinya, dan memanggil method.
 * @param string $controllerName Nama file controller (misal: 'KasirController')
 * @param string $method Nama method yang akan dipanggil
 * @param array $params Parameter untuk method
 */
function routeToController(string $controllerName, string $method, array $params = []): void
{
    $controllerPath = __DIR__ . "/../src/controllers/{$controllerName}.php";
    if (!file_exists($controllerPath)) {
        // Handle error jika controller tidak ditemukan
        header("HTTP/1.0 500 Internal Server Error");
        echo "Error: Controller $controllerName tidak ditemukan.";
        exit;
    }
    require_once $controllerPath;
    
    // Asumsi nama class sama dengan nama file tanpa .php
    $className = str_replace('.php', '', $controllerName);
    $controller = new $className();
    
    // Panggil method dengan parameter yang ada
    call_user_func_array([$controller, $method], $params);
}

error_log("PATH: " . $path);


// =========================================================================
// II. ROUTING LOGIC
// =========================================================================
switch (true) {
    // -------------------------
    // Rute Publik / Autentikasi
    // -------------------------
    case ($path === '/register'):
        routeToController('kasirController', 'register');
        break;

    case ($path === '/login'):
        routeToController('kasirController', 'login');
        break;

    case ($path === '/forgetPassword'):
        routeToController('kasirController', 'forgetPassword');
        break;

    case ($path === '/logout'):
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            routeToController('kasirController', 'logout');
        } else {
            header("Location: /dashboard");
            exit;
        }
        break;

    // -------------------------
    // Rute Tampilan Statis/Umum
    // -------------------------
    case ($path === '/'):
        require __DIR__ . '/../src/views/landingPage.php';
        break;
        
    // case ($path === '/test'):
    //     require __DIR__ . '/../src/views/test.php';
    //     break;

    // -------------------------
    // Rute Dashboard
    // -------------------------
    case ($path === '/dashboard'):
        routeToController('dashboardController', 'index');
        break;

    // -------------------------
    // Rute Transaksi
    // -------------------------
    case ($path === '/transaksi'):
        routeToController('transaksiController', 'listTransaksi');
        break;

    // -------------------------
    // Rute Kasir
    // Diurutkan dari yang paling spesifik ke umum: delete, update, detail, list
    // -------------------------
    case (preg_match('#^/kasir/delete/([0-9]+)$#', $path, $matches)):
        routeToController('kasirController', 'deleteKasir', [$matches[1]]);
        break;

    case (preg_match('#^/kasir/update/([0-9]+)$#', $path, $matches)):
        routeToController('kasirController', 'updateKasir', [$matches[1]]);
        break;
        
    case (preg_match('#^/kasir/([0-9]+)$#', $path, $matches)):
        routeToController('kasirController', 'detailKasir', [$matches[1]]);
        break;

    case ($path === '/kasir'):
        routeToController('kasirController', 'listKasir');
        break;


    // -------------------------
    // Rute Kategori
    // Diurutkan: delete, edit/id, store (POST), update (POST), tambah (VIEW), list
    // -------------------------
    case (preg_match('#^/kategori/delete/([0-9]+)$#', $path, $matches)):
        routeToController('kategoriController', 'deleteKategori', [$matches[1]]);
        break;
        
    case (preg_match('#^/kategori/edit/(\d+)$#', $path, $matches)):
        routeToController('kategoriController', 'editKategori', [$matches[1]]);
        break;

    case ($path === '/kategori/store'):
        routeToController('kategoriController', 'storeKategori');
        break;
        
    case ($path === '/kategori/update'):
        routeToController('kategoriController', 'updateKategori');
        break;
        
    case ($path === '/tambahkategori'):
        routeToController('kategoriController', 'tambahKategori');
        break;

    case ($path === '/kategori'):
        routeToController('kategoriController', 'listKategori');
        break;


    // -------------------------
    // Rute Produk
    // Diurutkan: delete, edit/id, API, store (POST), update (POST), tambah (VIEW), list
    // -------------------------
    case (preg_match('#^/produk/delete/(\d+)$#', $path, $matches)):
        routeToController('produkController', 'deleteProduk', [$matches[1]]);
        break;

    case (preg_match('#^/produk/edit/(\d+)$#', $path, $matches)):
        routeToController('produkController', 'editProduk', [$matches[1]]);
        break;
        
    case ($path === '/produk/api'):
        routeToController('produkController', 'getProdukByKategori');
        break;

    case ($path === '/produk/store'):
        routeToController('produkController', 'storeProduk');
        break;
        
    case ($path === '/produk/update'):
        routeToController('produkController', 'updateProduk');
        break;

    case ($path === '/tambahproduk'):
        routeToController('produkController', 'tambahProduk');
        break;

    case ($path === '/produk'):
        routeToController('produkController', 'listproduk');
        break;


    // -------------------------
    // Rute Member
    // Diurutkan: delete, edit/id, detail/id, store (POST), update (POST), tambah (VIEW), list
    // -------------------------
    case (preg_match('#^/member/delete/(\d+)$#', $path, $matches)):
        routeToController('memberController', 'deleteMember', [$matches[1]]);
        break;

    case (preg_match('#^/member/edit/(\d+)$#', $path, $matches)):
        routeToController('memberController', 'editMember', [$matches[1]]);
        break;
        
    case (preg_match('#^/member/(\d+)$#', $path, $matches)):
        routeToController('memberController', 'detailMember', [$matches[1]]);
        break;

    case ($path === '/member/store'):
        routeToController('memberController', 'storeMember');
        break;
        
    case ($path === '/member/update'):
        routeToController('memberController', 'updateMember');
        break;
        
    case ($path === '/tambahmember'):
        routeToController('memberController', 'tambahMember');
        break;

    case ($path === '/member'):
        routeToController('memberController', 'listMember');
        break;

    // -------------------------
    // Rute Keranjang
    // Diurutkan: delete, edit/id, detail/id, store (POST), update (POST), tambah (VIEW), list
    // -------------------------
    case ($path === '/keranjang'):
        // Ganti pemuatan manual dengan helper
        routeToController('keranjangController', 'index');
        break;

    // Tambah ke keranjang
    case (preg_match('#^/keranjang/tambah/(\d+)$#', $path, $matches)):
        // Gunakan $path dan $matches untuk konsistensi, bukan $_SERVER['REQUEST_URI']
        routeToController('keranjangController', 'tambah', [$matches[1]]);
        break;

    // Tambah via kode_produk (scanner)
    case ($path === '/keranjang/tambahByKode'):
        routeToController('keranjangController', 'tambahByKode');
        break;

    // Hapus item
    case (preg_match('#^/keranjang/hapus/(\d+)$#', $path, $matches)):
        // Note: Anda bisa menghapus `? true : false`
        routeToController('keranjangController', 'hapus', [$matches[1]]);
        break;

    // Kurangi qty item
    case (preg_match('#^/keranjang/kurangi/(\d+)$#', $path, $matches)):
        // Gunakan $path dan $matches untuk konsistensi, bukan $_SERVER['REQUEST_URI']
        routeToController('keranjangController', 'kurangi', [$matches[1]]);
        break;

    // Kosongkan keranjang
    case ($path === '/keranjang/kosongkan'):
        routeToController('keranjangController', 'kosongkan');
        break;

    // Checkout
    case ($path === '/keranjang/checkout'):
        routeToController('keranjangController', 'checkout');
        break;
    
    // API Member berdasarkan no_hp
    case (preg_match('#^/member/api$#', $path)):
        routeToController('memberController', 'getMemberByTelp');
        break;

    // =====================
    // Rute Selesai Beli
    // =====================
    case (preg_match('#^/transaksi/selesai/([A-Z0-9]+)$#', $path, $matches)):
        $_GET['kode'] = $matches[1];
        require __DIR__ . '/../src/views/selesaiBeli.php';
        break;

    // Kirim WhatsApp berdasarkan kode transaksi
    case (preg_match('#^/transaksi/kirimWA/([A-Z0-9]+)$#', $path, $matches) ? true : false):
        $_GET['kode'] = $matches[1];
        require_once __DIR__ . '/../src/helpers/sendToWhatsapp.php';
        break;

    // Cetak PDF Invoice Pakai DOMPDF
    case (preg_match('#^/transaksi/print-pdf/([A-Z0-9]+)$#', $path, $matches) ? true : false):
        $_GET['kode'] = $matches[1];
        require_once __DIR__ . '/../src/helpers/printInvoicePDF.php';
        break;

    // -------------------------
    // Rute Laporan
    // -------------------------
    case ($path === '/laporan'):
        routeToController('laporanController', 'index');
        break;

    // API laporan keuntungan tahunan
    case ($path === '/laporan/keuntungan'):
        routeToController('laporanController', 'getKeuntunganTahunan');
        break;

    // API laporan penjualan produk
    case ($path === '/laporan/penjualan'):
        routeToController('laporanController', 'getLaporanPenjualan');
        break;

    // Cetak PDF tabel laporan
    case ($path === '/laporan/cetakTablePDF'):
        routeToController('laporanController', 'cetakTablePDF');
        break;

    // Cetak PDF grafik keuntungan
    case ($path === '/laporan/cetakGrafikPDF'):
        routeToController('laporanController', 'cetakGrafikPDF');
        break;

    // =========================================================================
    // III. Default: 404 Not Found
    // =========================================================================
    default:
        http_response_code(404);
        require __DIR__ . '/../src/views/404.php';
        break;
}