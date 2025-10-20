<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../models/transaksiModels.php';
require_once __DIR__ . '/../models/detailTransaksiModels.php';
require_once __DIR__ . '/../models/memberModels.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$kode = $_GET['kode'] ?? null;
if (!$kode) {
    die("Kode transaksi tidak ditemukan.");
}

$transaksiModel = new Transaksi();
$detailModel = new DetailTransaksi();
$memberModel = new Member();

$trx = $transaksiModel->getTransaksiByKode($kode);
if (!$trx) {
    die("Transaksi tidak ditemukan.");
}

$detail = $detailModel->getDetailByTransaksi($trx['id_transaksi']);
$tanggal = date('d M Y H:i', strtotime($trx['tanggal_dibuat']));
$total = $trx['total'];
$kasir = $_SESSION['kasir']['nama'] ?? '-';

$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice '.$kode.'</title>
<style>
body {
  font-family: DejaVu Sans, sans-serif;
  font-size: 12px;
  margin: 0;
  padding: 10px;
  color: #333;
}
.header {
  text-align: center;
  border-bottom: 1px solid #000;
  padding-bottom: 4px;
  margin-bottom: 10px;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 4px;
}
.right { text-align: right; }
.center { text-align: center; }
.total {
  font-weight: bold;
  border-top: 1px solid #000;
  padding-top: 4px;
}
.footer {
  text-align: center;
  border-top: 1px dashed #999;
  margin-top: 10px;
  padding-top: 5px;
  font-size: 11px;
}
</style>
</head>
<body>
  <div class="header">
    <h2>ZEGARBOEGAR</h2>
    <p>Jl. Contoh No. 123, Bogor</p>
    <small>Tanggal: '.$tanggal.' | Kasir: '.$kasir.'</small><br>
    <small>Kode: '.$kode.'</small>
  </div>

  <table>
    <thead>
      <tr style="border-bottom:1px solid #000;">
        <th>Produk</th>
        <th class="center">Qty</th>
        <th class="right">Harga</th>
        <th class="right">Subtotal</th>
      </tr>
    </thead>
    <tbody>';

foreach ($detail as $item) {
    $subtotal = $item['qty'] * $item['harga'];
    $html .= '
      <tr>
        <td>'.htmlspecialchars($item['nama_produk']).'</td>
        <td class="center">'.$item['qty'].'</td>
        <td class="right">Rp '.number_format($item['harga'], 0, ',', '.').'</td>
        <td class="right">Rp '.number_format($subtotal, 0, ',', '.').'</td>
      </tr>';
}

$html .= '
      <tr>
        <td colspan="3" class="right total">TOTAL</td>
        <td class="right total">Rp '.number_format($total, 0, ',', '.').'</td>
      </tr>
    </tbody>
  </table>

  <div class="footer">
    Terima kasih telah berbelanja di ZegarBoegar!<br>
    Semoga hari Anda menyenangkan 😊
  </div>
</body>
</html>
';

// Dompdf setup
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait'); // bisa diganti ke 'A4' atau 'custom' untuk struk kecil
$dompdf->render();

// Output ke browser (langsung tampil PDF)
$dompdf->stream("Invoice_$kode.pdf", ["Attachment" => false]);
exit;
