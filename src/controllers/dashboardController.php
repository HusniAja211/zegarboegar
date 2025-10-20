<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class DashboardController
{
    public function index()
    {
        SessionManager::start();
        $db = (new Database())->getConnection();

        // =============================
        // TOTAL KEUNTUNGAN SEMUA WAKTU
        // =============================
        $stmtTotal = $db->query("
            SELECT SUM(dt.subtotal - (p.modal * dt.qty)) AS total_keuntungan
            FROM t_detail_transaksi dt
            JOIN t_produk p ON dt.id_produk = p.id_produk
        ");
        $totalKeuntungan = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total_keuntungan'] ?? 0;

        // =============================
        // TOTAL KEUNTUNGAN BULAN INI
        // =============================
        $stmtBulanIni = $db->prepare("
            SELECT SUM(dt.subtotal - (p.modal * dt.qty)) AS total_keuntungan_bulan
            FROM t_detail_transaksi dt
            JOIN t_produk p ON dt.id_produk = p.id_produk
            JOIN t_transaksi t ON dt.id_transaksi = t.id_transaksi
            WHERE MONTH(t.tanggal_dibuat) = MONTH(CURRENT_DATE())
              AND YEAR(t.tanggal_dibuat) = YEAR(CURRENT_DATE())
        ");
        $stmtBulanIni->execute();
        $keuntunganBulanIni = $stmtBulanIni->fetch(PDO::FETCH_ASSOC)['total_keuntungan_bulan'] ?? 0;

        // =============================
        // TOTAL KEUNTUNGAN BULAN LALU (untuk hitung persentase)
        // =============================
        $stmtBulanLalu = $db->prepare("
            SELECT SUM(dt.subtotal - (p.modal * dt.qty)) AS total_keuntungan_bulan_lalu
            FROM t_detail_transaksi dt
            JOIN t_produk p ON dt.id_produk = p.id_produk
            JOIN t_transaksi t ON dt.id_transaksi = t.id_transaksi
            WHERE MONTH(t.tanggal_dibuat) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
              AND YEAR(t.tanggal_dibuat) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
        ");
        $stmtBulanLalu->execute();
        $keuntunganBulanLalu = $stmtBulanLalu->fetch(PDO::FETCH_ASSOC)['total_keuntungan_bulan_lalu'] ?? 0;

        // =============================
        // HITUNG PERSENTASE KENAIKAN
        // =============================
        $persentaseKenaikan = 0;
        if ($keuntunganBulanLalu > 0) {
            $persentaseKenaikan = (($keuntunganBulanIni - $keuntunganBulanLalu) / $keuntunganBulanLalu) * 100;
        }

        // =============================
        // TOTAL ORDER BULAN INI
        // =============================
        $stmtOrder = $db->prepare("
            SELECT COUNT(*) AS total_order
            FROM t_transaksi
            WHERE MONTH(tanggal_dibuat) = MONTH(CURRENT_DATE())
              AND YEAR(tanggal_dibuat) = YEAR(CURRENT_DATE())
        ");
        $stmtOrder->execute();
        $totalOrder = $stmtOrder->fetch(PDO::FETCH_ASSOC)['total_order'] ?? 0;

        // =============================
        // TOTAL ORDER BULAN LALU
        // =============================
        $stmtOrderLalu = $db->prepare("
            SELECT COUNT(*) AS total_order_lalu
            FROM t_transaksi
            WHERE MONTH(tanggal_dibuat) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
              AND YEAR(tanggal_dibuat) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
        ");
        $stmtOrderLalu->execute();
        $totalOrderLalu = $stmtOrderLalu->fetch(PDO::FETCH_ASSOC)['total_order_lalu'] ?? 0;

        $persentaseOrder = 0;
        if ($totalOrderLalu > 0) {
            $persentaseOrder = (($totalOrder - $totalOrderLalu) / $totalOrderLalu) * 100;
        }

        // =============================
        // TRANSAKSI TERBARU (5 TERAKHIR)
        // =============================
        $stmtTransaksi = $db->query("
            SELECT 
                t.id_transaksi,
                t.tanggal_dibuat,
                SUM(dt.subtotal) AS total_harga
            FROM t_transaksi t
            JOIN t_detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
            GROUP BY t.id_transaksi, t.tanggal_dibuat
            ORDER BY t.tanggal_dibuat DESC
            LIMIT 5
        ");
        $transaksiTerbaru = $stmtTransaksi->fetchAll(PDO::FETCH_ASSOC);

        // Kirim data ke view
        require __DIR__ . '/../views/dashboard.php';
    }
}
