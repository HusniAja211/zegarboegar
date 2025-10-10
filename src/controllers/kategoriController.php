<?php
require_once __DIR__ . '/../models/kategoriModels.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class kategoriController
{
    public function listKategori()
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $kategoriModel = new Kategori();
        $allKategori = $kategoriModel->getAllKategori();

        require __DIR__ . '/../views/kategori.php';
        require __DIR__ . '/../views/tambahProduk.php';
    }

    public function detailKategori($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $kategoriModel = new Kategori();
        $kategori = $kategoriModel->getKategoriById($id);

        if (!$kategori) {
            header("Location: /kategori?error=notfound");
            exit;
        }

        require __DIR__ . '/../views/detailKategori.php';
    }
}
