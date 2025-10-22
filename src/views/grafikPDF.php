<?php
$tahun = $_POST['tahun'] ?? '—';
$chartBase64 = $_POST['chartBase64'] ?? '';
$keuntunganJson = $_POST['dataKeuntungan'] ?? '[]';
$keuntungan = json_decode($keuntunganJson, true) ?? [];
$bulan = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
?>

<style>
  body { font-family: sans-serif; text-align: center; }
  h1 { color: #2563eb; margin-bottom: 20px; }
  h2 { color: #1e3a8a; margin-top: 30px; }
  table { border-collapse: collapse; margin: 20px auto; width: 70%; }
  th, td { border: 1px solid #ccc; padding: 8px 10px; text-align: center; }
  th { background: #e0e7ff; color: #1e3a8a; }
  footer { margin-top: 20px; font-size: 12px; color: #666; }
</style>

<h1>Grafik Keuntungan Tahun <?= htmlspecialchars($tahun) ?></h1>

<?php if (!empty($chartBase64)): ?>
  <img src="<?= $chartBase64 ?>" alt="Grafik Keuntungan Tahunan" style="max-width:700px; width:100%; border:1px solid #ccc; border-radius:8px;">
<?php else: ?>
  <p>Tidak ada data grafik untuk tahun ini.</p>
<?php endif; ?>

<h2>Detail Keuntungan per Bulan</h2>
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Bulan</th>
      <th>Keuntungan</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($bulan as $i => $b): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= $b ?></td>
        <td>Rp <?= number_format($keuntungan[$i] ?? 0, 0, ',', '.') ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php date_default_timezone_set('Asia/Jakarta'); ?>
<footer>Dicetak pada <?= date('d M Y H:i') ?></footer>
