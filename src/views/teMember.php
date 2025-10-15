<?php
require_once __DIR__ . '/components/header.php';

// Deteksi mode: tambah / detail / edit
$isEdit = isset($detailMember) && !empty($detailMember);
$formAction = $isEdit ? '/member/update' : '/member/store';
$title = $isEdit ? 'Detail & Edit Member' : 'Tambah Member Baru';
$subtitle = $isEdit
  ? 'Lihat atau ubah data member di bawah ini.'
  : 'Isi semua kolom untuk menambahkan member baru.';
?>

<main class="min-h-screen bg-gray-50 p-8 flex justify-center">
  <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg border border-gray-200">
    <div class="bg-blue-600 text-white text-center py-6 rounded-t-xl">
      <h1 class="text-3xl font-bold"><?= $title ?></h1>
      <p class="text-blue-100"><?= $subtitle ?></p>
    </div>

    <form action="<?= $formAction ?>" method="POST" class="p-8 space-y-6">

      <?php if ($isEdit): ?>
        <input type="hidden" name="id_member" value="<?= htmlspecialchars($detailMember['id_member']) ?>">
      <?php endif; ?>

      <!-- Nama -->
      <div>
        <label for="nama_member" class="block text-sm font-bold text-gray-700 mb-1">Nama Member</label>
        <input type="text" id="nama_member" name="nama_member"
          value="<?= htmlspecialchars($detailMember['nama_member'] ?? '') ?>"
          <?= $isEdit ? '' : 'required' ?>
          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 bg-blue-50/30">
      </div>

      <!-- Nomor HP -->
      <div>
        <label for="no_hp" class="block text-sm font-bold text-gray-700 mb-1">Nomor Telepon</label>
        <input type="text" id="no_hp" name="no_hp"
          value="<?= htmlspecialchars($detailMember['no_hp'] ?? '') ?>"
          <?= $isEdit ? '' : 'required' ?>
          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 bg-blue-50/30">
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
        <input type="email" id="email" name="email"
          value="<?= htmlspecialchars($detailMember['email'] ?? '') ?>"
          <?= $isEdit ? '' : 'required' ?>
          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 bg-blue-50/30">
      </div>

      <!-- Poin -->
      <div>
        <label for="poin" class="block text-sm font-bold text-gray-700 mb-1">Poin</label>
        <input type="number" id="poin" name="poin" min="0"
          value="<?= htmlspecialchars($detailMember['poin'] ?? '0') ?>"
          class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 bg-blue-50/30">
      </div>

      <div class="flex justify-between pt-4">
        <a href="/member" class="px-6 py-3 bg-gray-300 hover:bg-gray-400 rounded-lg font-semibold text-black shadow-md">← Kembali</a>

        <?php if ($isEdit): ?>
          <div class="space-x-3">
            <button type="submit"
              class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md">
              💾 Simpan Perubahan
            </button>
            <button type="button" id="btnHapus" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold shadow-md">
              🗑️ Hapus Member
            </button>
          </div>
        <?php else: ?>
          <button type="submit"
            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold shadow-md">
            ➕ Tambah Member
          </button>
        <?php endif; ?>
      </div>
    </form>
  </div>
</main>



<?php require_once __DIR__ . '/components/footer.php'; ?>
