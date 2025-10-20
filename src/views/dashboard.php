<?php
require_once __DIR__ . '/components/header.php';
$kasirModel = new Kasir();
$kasirModel->updateLastActivity($_SESSION['kasir']['id']);
?>

<main class="flex-1 max-w-7xl w-full mx-auto bg-gray-50 text-gray-800">
  <!-- Hero Section -->
  <section class="bg-blue-100">
    <div class="max-w-7xl mx-auto px-4 py-4 text-center">
      <h2 class="text-lg font-semibold text-blue-800">🔔 Reminder Backup Data</h2>
      <p class="text-sm text-blue-700">
        Segera lakukan backup data transaksi Anda untuk menghindari kehilangan data.
        <a href="#" class="text-blue-600 underline">Klik di sini</a> untuk backup.
      </p>
    </div>
  </section>

  <!-- Konten Dashboard -->
  <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Card Total Keuntungan -->
    <div class="bg-white rounded-xl shadow p-4">
      <h3 class="text-sm text-gray-500">Total Keuntungan</h3>
      <p class="text-2xl font-bold text-blue-700">
        Rp <?= number_format($totalKeuntungan, 0, ',', '.') ?>
      </p>
      <span class="text-xs text-green-500">Keseluruhan</span>
    </div>

    <!-- Card Keuntungan Bulan Ini -->
    <div class="bg-white rounded-xl shadow p-4">
      <h3 class="text-sm text-gray-500">Total Keuntungan Bulan Ini</h3>
      <p class="text-2xl font-bold text-blue-700">
        Rp <?= number_format($keuntunganBulanIni, 0, ',', '.') ?>
      </p>
      <?php if ($persentaseKenaikan >= 0): ?>
        <span class="text-xs text-green-500">+<?= number_format($persentaseKenaikan, 1) ?>% dari bulan lalu</span>
      <?php else: ?>
        <span class="text-xs text-red-500"><?= number_format($persentaseKenaikan, 1) ?>% dari bulan lalu</span>
      <?php endif; ?>
    </div>

    <!-- Card Total Order -->
    <div class="bg-white rounded-xl shadow p-4">
      <h3 class="text-sm text-gray-500">Total Order Bulan Ini</h3>
      <p class="text-2xl font-bold text-blue-700">
        <?= $totalOrder ?>
      </p>
      <?php if ($persentaseOrder >= 0): ?>
        <span class="text-xs text-green-500">+<?= number_format($persentaseOrder, 1) ?>% dari bulan lalu</span>
      <?php else: ?>
        <span class="text-xs text-red-500"><?= number_format($persentaseOrder, 1) ?>% dari bulan lalu</span>
      <?php endif; ?>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="bg-white rounded-xl shadow p-4 lg:col-span-3">
      <h3 class="text-base font-semibold text-gray-700 mb-3">Transaksi Terbaru</h3>
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b text-gray-600">
            <th class="py-2 text-left">Order ID</th>
            <th class="py-2 text-left">Tanggal</th>
            <th class="py-2 text-left">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($transaksiTerbaru)): ?>
            <?php foreach ($transaksiTerbaru as $trx): ?>
              <tr class="border-b hover:bg-gray-50">
                <td class="py-2 font-medium text-gray-700">#<?= htmlspecialchars($trx['id_transaksi']) ?></td>
                <td><?= date('d M Y', strtotime($trx['tanggal_dibuat'])) ?></td>
                <td>Rp <?= number_format($trx['total_harga'], 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" class="py-3 text-center text-gray-500">Belum ada transaksi.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/components/footer.php'; ?>
