<style>
  body { font-family: sans-serif; }
  h1 { color: #2563eb; text-align: center; }
  table { width: 100%; border-collapse: collapse; margin-top: 20px; }
  th, td { border: 1px solid #ccc; padding: 6px; font-size: 12px; }
  th { background: #e0f2fe; color: #1e3a8a; }
  tfoot td { font-weight: bold; background: #f0f9ff; }
</style>

<h1>Laporan Penjualan Tahun <?= htmlspecialchars($tahun) ?></h1>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Produk</th>
      <th>Jumlah Terjual</th>
      <th>Total Penjualan</th>
      <th>Total Keuntungan</th>
      <th>Total Modal</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $no=1;
    $totalJual=0; $totalUntung=0; $totalModal=0;
    foreach ($data as $row): 
      $totalJual += $row['total_penjualan'];
      $totalUntung += $row['total_keuntungan'];
      $totalModal += $row['total_modal'];
    ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['nama_produk']) ?></td>
      <td><?= number_format($row['jumlah_terjual']) ?></td>
      <td>Rp <?= number_format($row['total_penjualan'],0,',','.') ?></td>
      <td>Rp <?= number_format($row['total_keuntungan'],0,',','.') ?></td>
      <td>Rp <?= number_format($row['total_modal'],0,',','.') ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3">Total</td>
      <td>Rp <?= number_format($totalJual,0,',','.') ?></td>
      <td>Rp <?= number_format($totalUntung,0,',','.') ?></td>
      <td>Rp <?= number_format($totalModal,0,',','.') ?></td>
    </tr>
  </tfoot>
</table>
