<?php
require_once __DIR__ . '/../helpers/SessionManager.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../../vendor/autoload.php';


use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanController
{
    public function index()
    {
        SessionManager::start();
        require __DIR__ . '/../views/laporan.php';
    }

    // 📊 API: Keuntungan per bulan (untuk chart)
    public function getKeuntunganTahunan()
    {
        SessionManager::start();
        header('Content-Type: application/json; charset=utf-8');

        $tahun = $_GET['tahun'] ?? date('Y');
        $db = (new Database())->getConnection();

        $stmt = $db->prepare("
            SELECT 
                MONTH(t.tanggal_dibuat) AS bulan,
                SUM(dt.subtotal - (p.modal * dt.qty)) AS total_keuntungan
            FROM t_transaksi t
            JOIN t_detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
            JOIN t_produk p ON dt.id_produk = p.id_produk
            WHERE YEAR(t.tanggal_dibuat) = :tahun
            GROUP BY MONTH(t.tanggal_dibuat)
            ORDER BY bulan ASC
        ");
        $stmt->execute(['tahun' => $tahun]);

        $data = array_fill(1, 12, 0);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[(int)$row['bulan']] = (float)$row['total_keuntungan'];
        }

        echo json_encode([
            'status' => true,
            'tahun' => $tahun,
            'data' => array_values($data)
        ]);
    }

    // 📋 API: Laporan penjualan produk
    public function getLaporanPenjualan()
    {
        SessionManager::start();
        header('Content-Type: application/json; charset=utf-8');

        $tahun = $_GET['tahun'] ?? date('Y');
        $db = (new Database())->getConnection();

        $stmt = $db->prepare("
            SELECT 
                p.nama_produk,
                SUM(dt.qty) AS jumlah_terjual,
                SUM(dt.subtotal) AS total_penjualan,
                SUM(p.modal * dt.qty) AS total_modal,
                SUM(dt.subtotal - (p.modal * dt.qty)) AS total_keuntungan
            FROM t_detail_transaksi dt
            JOIN t_produk p ON dt.id_produk = p.id_produk
            JOIN t_transaksi t ON dt.id_transaksi = t.id_transaksi
            WHERE YEAR(t.tanggal_dibuat) = :tahun
            GROUP BY p.id_produk
            ORDER BY total_penjualan DESC
        ");
        $stmt->execute(['tahun' => $tahun]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => true,
            'tahun' => $tahun,
            'data' => $result
        ]);
    }

    // 🧾 Cetak TABEL laporan ke PDF
    public function cetakTablePDF()
    {
        $tahun = $_GET['tahun'] ?? date('Y');
        $db = (new Database())->getConnection();

        $stmt = $db->prepare("
            SELECT 
                p.nama_produk,
                SUM(dt.qty) AS jumlah_terjual,
                SUM(dt.subtotal) AS total_penjualan,
                SUM(p.modal * dt.qty) AS total_modal,
                SUM(dt.subtotal - (p.modal * dt.qty)) AS total_keuntungan
            FROM t_detail_transaksi dt
            JOIN t_produk p ON dt.id_produk = p.id_produk
            JOIN t_transaksi t ON dt.id_transaksi = t.id_transaksi
            WHERE YEAR(t.tanggal_dibuat) = :tahun
            GROUP BY p.id_produk
        ");
        $stmt->execute(['tahun' => $tahun]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        include __DIR__ . '/../views/laporanTablePDF.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("laporan_tabel_$tahun.pdf", ["Attachment" => true]);
    }

    // 📈 Cetak GRAFIK keuntungan ke PDF
    public function cetakGrafikPDF()
    {
        // Pastikan session dan autoloader siap
        SessionManager::start();

        // Nonaktifkan semua output error agar tidak merusak PDF
        error_reporting(0);
        ini_set('display_errors', 0);

        // Bersihkan seluruh buffer
        while (ob_get_level()) ob_end_clean();

        $tahun = $_POST['tahun'] ?? date('Y');
        $chartBase64 = $_POST['chartBase64'] ?? '';

        // Buat HTML dengan template yang sudah kamu punya
        ob_start();
        include __DIR__ . '/../views/grafikPDF.php';
        $html = ob_get_clean();

        // Siapkan Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Kirim PDF langsung ke browser
        $filename = "Grafik_Keuntungan_{$tahun}.pdf";

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($dompdf->output()));

        echo $dompdf->output();
        exit;
    }
}
