<?php
require_once __DIR__ . '/../models/transaksiModels.php';
require_once __DIR__ . '/../models/detailTransaksiModels.php';
require_once __DIR__ . '/../models/memberModels.php';
require_once __DIR__ . '/components/header.php';

$kode = $_GET['kode'] ?? null;
if (!$kode) {
    echo "<main class='max-w-xl mx-auto p-6 text-center text-red-600 font-medium'>Kode transaksi tidak ditemukan.</main>";
    require_once __DIR__ . '/components/footer.php';
    exit;
}

$transaksiModel = new Transaksi();
$detailModel = new DetailTransaksi();
$memberModel = new Member();

$transaksi = $transaksiModel->getTransaksiByKode($kode);
if (!$transaksi) {
    echo "<main class='max-w-xl mx-auto p-6 text-center text-red-600 font-medium'>Transaksi tidak ditemukan di database.</main>";
    require_once __DIR__ . '/components/footer.php';
    exit;
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
        $noHp = $member['no_hp'];
        $poinTotal = $member['poin'];
    }
}
?>

<main class="max-w-3xl mx-auto bg-white shadow-md rounded-xl mt-8 p-6">
  <div class="text-center mb-6">
    <h2 class="text-2xl font-semibold text-green-600">✅ Transaksi Berhasil!</h2>
    <p class="text-gray-600 mt-1">Terima kasih telah berbelanja di <strong>ZegarBoegar</strong></p>
  </div>

  <div class="grid grid-cols-2 gap-4 text-sm mb-6">
    <p><span class="font-semibold text-gray-700">Kode:</span> <?= htmlspecialchars($kode) ?></p>
    <p><span class="font-semibold text-gray-700">Tanggal:</span> <?= $tanggal ?></p>
    <p><span class="font-semibold text-gray-700">Kasir:</span> <?= htmlspecialchars($namaKasir) ?></p>
    <p><span class="font-semibold text-gray-700">Total:</span> Rp <?= number_format($total, 0, ',', '.') ?></p>
  </div>

  <h3 class="text-lg font-semibold text-gray-800 mb-3">🛒 Detail Pembelian</h3>
  <div class="overflow-x-auto">
    <table class="w-full border border-gray-200 text-sm text-left">
      <thead class="bg-gray-100 border-b border-gray-200">
        <tr>
          <th class="p-2 font-medium">Produk</th>
          <th class="p-2 font-medium text-center">Qty</th>
          <th class="p-2 font-medium text-right">Harga</th>
          <th class="p-2 font-medium text-right">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($detailItems as $item): ?>
          <tr class="border-b border-gray-100 hover:bg-gray-50">
            <td class="p-2"><?= htmlspecialchars($item['nama_produk']) ?></td>
            <td class="p-2 text-center"><?= $item['qty'] ?></td>
            <td class="p-2 text-right">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
            <td class="p-2 text-right">Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
        <tr class="bg-gray-50 font-semibold">
          <td colspan="3" class="p-2 text-right">Total</td>
          <td class="p-2 text-right">Rp <?= number_format($total, 0, ',', '.') ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <?php if ($poinBaru > 0): ?>
    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-md text-sm">
      🎁 <strong><?= $poinBaru ?></strong> poin telah ditambahkan ke akun Anda.
      <?php if ($poinTotal > 0): ?>
        <br><span class="text-gray-700">Total poin sekarang: <strong><?= $poinTotal ?></strong></span>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
    <button 
      onclick="window.location='/keranjang'"
      class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition">
      ⬅️ Kembali ke Kasir
    </button>

    <?php if (!empty($noHp)): ?>
      <button 
        id="btnWA"
        data-kode="<?= htmlspecialchars($kode) ?>"
        style="background-color: green;"
        class="hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
        📱 Kirim Struk ke WhatsApp
      </button>
    <?php endif; ?>
  </div>

  <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
    <a href="/transaksi/print-pdf/<?= htmlspecialchars($kode) ?>" 
      target="_blank"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
      🧾 Cetak Invoice (PDF)
    </a>

</main>
<?php require_once __DIR__ . '/components/footer.php'; ?>
