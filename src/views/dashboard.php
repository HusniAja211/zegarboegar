<?php
require_once __DIR__ . '/components/header.php';
?>

<!-- Hero Section (Notifikasi / Pengumuman) -->
<section class="bg-blue-100">
  <div class="max-w-7xl mx-auto px-4 py-4 text-center">
    <h2 class="text-lg font-semibold text-blue-800">🔔 Reminder Backup Data</h2>
    <p class="text-sm text-blue-700">Segera lakukan backup data transaksi Anda untuk menghindari kehilangan data.
      <a href="#" class="text-blue-600 underline">Klik di sini</a> untuk backup.
    </p>
  </div>
</section>

<!-- Konten Dashboard -->
<div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- Card Statistik -->
  <div class="bg-white rounded-xl shadow p-4">
    <h3 class="text-sm text-gray-500">Total Profit</h3>
    <p class="text-2xl font-bold text-blue-700">$38,591</p>
    <span class="text-xs text-green-500">+2.3% dari bulan lalu</span>
  </div>
  <div class="bg-white rounded-xl shadow p-4">
    <h3 class="text-sm text-gray-500">Total Order</h3>
    <p class="text-2xl font-bold text-blue-700">601</p>
    <span class="text-xs text-green-500">+0.2% dari bulan lalu</span>
  </div>
  <div class="bg-white rounded-xl shadow p-4">
    <h3 class="text-sm text-gray-500">Order Selesai</h3>
    <p class="text-2xl font-bold text-blue-700">71</p>
    <span class="text-xs text-red-500">-1.2% dari tahun lalu</span>
  </div>

  <!-- Chart & Activity -->
  <div class="bg-white rounded-xl shadow p-4 lg:col-span-2">
    <h3 class="text-base font-semibold text-gray-700 mb-2">Aktivitas Customer</h3>
    <div class="h-48 flex items-center justify-center text-gray-400">[Chart Placeholder]</div>
  </div>

  <div class="bg-white rounded-xl shadow p-4">
    <h3 class="text-base font-semibold text-gray-700 mb-2">Target Ads Market</h3>
    <div class="h-48 flex items-center justify-center text-gray-400">[Chart Placeholder]</div>
  </div>

  <!-- Transaksi Terbaru -->
  <div class="bg-white rounded-xl shadow p-4 lg:col-span-3">
    <h3 class="text-base font-semibold text-gray-700 mb-3">Transaksi Terbaru</h3>
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b text-gray-600">
          <th class="py-2 text-left">Order ID</th>
          <th class="py-2 text-left">Tanggal</th>
          <th class="py-2 text-left">Jumlah</th>
          <th class="py-2 text-left">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-b">
          <td class="py-2">#19992327</td>
          <td>13 Jun 2024</td>
          <td>$537.1</td>
          <td><span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700">Pending</span></td>
        </tr>
        <tr class="border-b">
          <td class="py-2">#11234922</td>
          <td>12 Jun 2024</td>
          <td>$381.4</td>
          <td><span class="px-2 py-1 rounded bg-green-100 text-green-700">Paid</span></td>
        </tr>
        <tr>
          <td class="py-2">#18773227</td>
          <td>10 Jun 2024</td>
          <td>$792.2</td>
          <td><span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700">Pending</span></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
  <?php require_once __DIR__ . '/components/footer.php'; ?>