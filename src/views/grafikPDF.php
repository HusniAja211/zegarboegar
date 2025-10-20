<style>
  body { font-family: sans-serif; text-align: center; }
  h1 { color: #2563eb; margin-bottom: 20px; }
  img { width: 100%; max-width: 700px; margin-top: 10px; border: 1px solid #ccc; border-radius: 8px; }
  footer { margin-top: 20px; font-size: 12px; color: #666; }
</style>

<h1>Grafik Keuntungan Tahun <?= htmlspecialchars($tahun) ?></h1>

<?php if (!empty($chartBase64)): ?>
  <img src="<?= $chartBase64 ?>" alt="Grafik Keuntungan Tahunan">
<?php else: ?>
  <p>Tidak ada data grafik untuk tahun ini.</p>
<?php endif; ?>

<?php date_default_timezone_set('Asia/Jakarta'); ?>
<footer>Dicetak pada <?= date('d M Y H:i') ?></footer>
