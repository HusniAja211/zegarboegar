<?php require_once __DIR__ . '/components/header.php'; ?>

<main class="p-6">
  <h1 class="text-2xl font-bold text-blue-800 mb-4 text-center">📊 Laporan Penjualan & Keuntungan</h1>

  <div class="flex justify-end mb-4 gap-4">
    <select id="tahun-select" class="border border-blue-400 rounded-lg px-4 py-2 text-blue-700">
      <?php
      $tahunSekarang = date('Y');
      for ($i = $tahunSekarang; $i >= $tahunSekarang - 5; $i--) {
          echo "<option value='$i'>$i</option>";
      }
      ?>
    </select>
    <button id="btnCetakGrafik" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">🖨️ Cetak Grafik</button>
    <button id="btnCetakTable" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">🧾 Cetak Tabel</button>
  </div>

  <div class="bg-white rounded-lg shadow-lg p-6 mb-8 border border-blue-200">
    <canvas id="chartKeuntungan" height="100"></canvas>
  </div>

  <div class="bg-white rounded-lg shadow-lg p-6 border border-blue-200">
    <h2 class="text-xl font-semibold text-blue-800 mb-4 text-center">📋 Total Penjualan Per Produk</h2>
    <div class="overflow-x-auto flex justify-center">
      <table class="min-w-full border border-gray-300 text-sm" id="tableLaporan">
        <thead class="bg-blue-100 text-blue-900">
          <tr>
            <th class="py-2 px-3 border">No</th>
            <th class="py-2 px-3 border text-left">Nama Produk</th>
            <th class="py-2 px-3 border">Jumlah Terjual</th>
            <th class="py-2 px-3 border">Total Penjualan</th>
            <th class="py-2 px-3 border">Total Keuntungan</th>
            <th class="py-2 px-3 border">Total Modal</th>
          </tr>
        </thead>
        <tbody id="tbodyLaporan" class="text-gray-800"></tbody>
        <tfoot class="bg-blue-50 font-semibold">
          <tr>
            <td colspan="3" class="text-right py-2 px-3 border">Total</td>
            <td id="totalPenjualan" class="py-2 px-3 border text-right">Rp 0</td>
            <td id="totalKeuntungan" class="py-2 px-3 border text-right">Rp 0</td>
            <td id="totalModal" class="py-2 px-3 border text-right">Rp 0</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</main>
<?php require_once __DIR__ . '/components/footer.php'; ?>
