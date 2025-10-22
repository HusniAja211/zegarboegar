<?php
require_once __DIR__ . '/components/header.php';
?>

<main class="min-h-screen p-8 bg-gray-50">
  <!-- Container -->
  <div class="max-w-6xl mx-auto flex flex-col space-y-6">

    <!-- Title -->
     <div class="flex flex-row justify-between">
      <h1 class="text-2xl font-bold text-black">Data Kasir</h1>
      <!-- <h1 class="text-2xl font-bold text-black">Tambah Kasir</h1> -->
     </div>
    

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-200">
      <table class="w-full table-auto border-collapse text-sm text-black">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-4 py-3 border">No</th>
            <th class="px-4 py-3 border">ID</th>
            <th class="px-4 py-3 border">Nama</th>
            <th class="px-4 py-3 border">Email</th>
            <th class="px-4 py-3 border">Nomor Telepon</th>
            <th class="px-4 py-3 border">Status</th>
            <th class="px-4 py-3 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dataPage as $index => $row): ?>
            <tr>
              <td class="px-4 py-2 border text-center"><?= $start + $index + 1 ?></td>
              <td class="px-4 py-2 border text-center"><?= $row['id_kasir'] ?></td>
              <td class="px-4 py-2 border font-semibold"><?= $row['nama_kasir'] ?></td>
              <td class="px-4 py-2 border"><?= $row['email_kasir'] ?></td>
              <td class="px-4 py-2 border text-center"><?= $row['nomor_telepon_kasir'] ?></td>
              <?php if($row['status'] == 'Aktif' ) :?>
                <td class="text-blue-700 px-4 py-2 border text-center"><?= $row['status'] ?></td>
              <?php else : ?>
                <td style="color:red" class="px-4 py-2 border text-center"><?= $row['status'] ?></td>
              <?php endif; ?>
              <td class="px-4 py-2 border text-center space-x-3">
                <a href="/kasir/<?= $row['id_kasir'] ?>" style="color: blue" class="hover:underline">Lihat</a>
                <button style="color: red"
                  class="hover:underline btn-delete"
                  data-id="<?= $row['id_kasir'] ?>">Hapus
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
</main>
<?php
require_once __DIR__ . '/components/footer.php';
?>