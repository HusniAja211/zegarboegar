<?php 
require_once __DIR__ . '/../models/transaksiModels.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class TransaksiController{
    public function listTransaksi()
    {
        $transaksi = new Transaksi();
        $allTransaksi = $transaksi->getAllTransaksi();

        // pagination
        $perPage = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalData = count($allTransaksi);
        $totalPages = ceil($totalData / $perPage);
        $start = ($page - 1) * $perPage;
        $dataPage = array_slice($allTransaksi, $start, $perPage);

        require __DIR__ . '/../views/transaksi.php';
    }
}
