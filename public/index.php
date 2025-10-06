<?php

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
