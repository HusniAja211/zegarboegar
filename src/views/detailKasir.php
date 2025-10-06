<?php require_once __DIR__ . '/components/header.php'; ?>

<main class="bg-blue-50/50 min-h-screen font-sans flex p-8">
  <section class="flex-1 flex justify-center items-start  md:px-10 overflow-y-auto">

      <div class="relative rounded-2xl p-6"
           style="background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 50%, #93c5fd 100%);">

        <form action="/kasir/update/<?= $detailKasir['id_kasir'] ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">

          <div class="flex flex-row items-center justify-between md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
              <img src="<?= '/' . ltrim($detailKasir['gambar_kasir'] ?: '/images/default_pfp/defaultPFP.jpg', '/') ?>"
                   width="200" height="200"
                   class="rounded-full object-cover border-4 border-blue-200" />

              <div>
                <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($detailKasir['nama_kasir']) ?></h2>
                <p class="text-sm text-blue-700">ID: <?= htmlspecialchars($detailKasir['id_kasir']) ?></p>

                <label class="block text-sm font-medium text-gray-600 mt-3">Ubah Foto Profil</label>
                <input type="file" name="gambar_kasir" accept="image/*"
                  class="block w-full text-sm text-gray-700 bg-blue-50 border border-blue-200 rounded-lg cursor-pointer focus:outline-none">
              </div>
            </div>

            <button type="submit" id="btnUpdateKasir" 
              class="bg-blue-600 text-white font-medium px-6 py-2 rounded-lg hover:bg-blue-700 transition shadow-md self-start md:self-center">
              Simpan Perubahan
            </button>
          </div>

          <!-- GRID FORM -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="text-sm font-medium text-gray-600 block mb-1">Nama</label>
              <input type="text" name="nama_kasir" value="<?= htmlspecialchars($detailKasir['nama_kasir']) ?>"
                     class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-gray-800">
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600 block mb-1">Email</label>
              <input type="email" name="email_kasir" value="<?= htmlspecialchars($detailKasir['email_kasir']) ?>"
                     class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-gray-800">
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600 block mb-1">Nomor Telepon</label>
              <input type="text" name="nomor_telepon_kasir" value="<?= htmlspecialchars($detailKasir['nomor_telepon_kasir']) ?>"
                     class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-gray-800">
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600 block mb-1">Status</label>
              <select name="status"
                      class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-gray-800">
                <option value="Aktif" <?= $detailKasir['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="Tidak Aktif" <?= $detailKasir['status'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
              </select>
            </div>
          </div>

          <!-- PASSWORD UPDATE SECTION -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 border-t pt-4">
            <div>
              <label class="text-sm font-medium text-gray-600 block mb-1">Password Baru</label>
              <input type="password" name="password_baru"
                     class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-gray-800" placeholder="Masukkan password baru" minlength="8" maxlength="8">
            </div>
            <div>
              <label class="text-sm font-medium text-gray-600 block mb-1">Konfirmasi Password Baru</label>
              <input type="password" name="konfirmasi_password"
                     class="w-full bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 text-gray-800" placeholder="Ulangi password baru" minlength="8" maxlength="8">
            </div>
          </div>

        </form>
      </div>
    
  </section>
</main>

<?php require_once __DIR__ . '/components/footer.php'; ?>
