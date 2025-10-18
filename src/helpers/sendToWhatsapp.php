<?php
require_once __DIR__ . '/../models/transaksiModels.php';
require_once __DIR__ . '/../models/detailTransaksiModels.php';
require_once __DIR__ . '/../models/memberModels.php';

session_start();

$kode = $_GET['kode'] ?? null;
if (!$kode) {
    die("Kode transaksi tidak ditemukan.");
}

$transaksiModel = new Transaksi();
$detailModel = new DetailTransaksi();
$memberModel = new Member();

$transaksi = $transaksiModel->getTransaksiByKode($kode);
if (!$transaksi) {
    die("Transaksi tidak ditemukan di database.");
}

$detailItems = $detailModel->getDetailByTransaksi($transaksi['id_transaksi']);
$namaKasir = $_SESSION['kasir']['nama'] ?? '-';
$total = $transaksi['total'] ?? 0;
$tanggal = date('d M Y H:i', strtotime($transaksi['tanggal_dibuat']));
$poinBaru = floor($total * 0.10);
$poinTotal = 0;
$noHp = '';

if (!empty($transaksi['id_member'])) {
    $member = $memberModel->getMemberById($transaksi['id_member']);
    if ($member) {
        $noHp = preg_replace('/[^0-9]/', '', $member['no_hp']);
        $poinTotal = $member['poin'];
    }
}

if (empty($noHp)) {
    die("Nomor WhatsApp tidak ditemukan untuk transaksi ini.");
}

$detailText = "";
foreach ($detailItems as $item) {
    $nama = $item['nama_produk'];
    $qty = $item['qty'];
    $harga = number_format($item['harga'], 0, ',', '.');
    $subtotal = number_format($item['harga'] * $item['qty'], 0, ',', '.');
    $detailText .= "{$nama}\t{$qty}\tRp {$harga}\tRp {$subtotal}\n";
}

$totalFormat = number_format($total, 0, ',', '.');

$message = "
Terima kasih telah berbelanja di *ZegarBoegar* 🙏

Kode: *{$transaksi['kode_transaksi']}*
Tanggal: {$tanggal}
Kasir: *{$namaKasir}*
Total: *Rp {$totalFormat}*

🛒 *Detail Pembelian*
Produk\tQty\tHarga\tSubtotal
{$detailText}
Total\tRp {$totalFormat}

🎁 *{$poinBaru} poin* telah ditambahkan ke akun Anda.
Total poin sekarang: *{$poinTotal}*
";

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.fonnte.com/send',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
        'target' => $noHp,
        'message' => $message,
        'countryCode' => '62'
    ],
    CURLOPT_HTTPHEADER => [
        'Authorization: EAVxNhuSfoE42GdqTrAQ'
    ]
]);

$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

file_put_contents(__DIR__ . '/whatsapp_log.txt',
    date('Y-m-d H:i:s') . " | Kode: $kode | Target: $noHp | Response: $response | Error: $error\n",
    FILE_APPEND
);

header('Content-Type: application/json');

if ($error) {
    echo json_encode(['status' => false, 'message' => 'Gagal mengirim pesan ke WhatsApp.']);
} else {
    echo json_encode(['status' => true, 'message' => 'Pesan berhasil dikirim ke WhatsApp pelanggan.']);
}
exit;

