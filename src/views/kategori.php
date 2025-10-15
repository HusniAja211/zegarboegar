<?php
require_once __DIR__ . '/components/header.php';
?>

<main class="min-h-screen p-8 bg-gray-50">
    <!-- Container -->
    <div class="max-w-6xl mx-auto flex flex-col space-y-6">

        <!-- Title -->
        <div class="flex flex-row justify-between">
        <h1 class="text-2xl font-bold text-black">Data Kategori</h1>
        <h1 class="text-2xl font-bold text-black"><a href="/tambahkategori">Tambah Kategori</a></h1>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-200">
            <table class="w-full table-auto border-collapse text-sm text-black">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 border">No</th>
                        <th class="px-4 py-3 border">ID Kategori</th>
                        <th class="px-4 py-3 border">Nama Kategori</th>
                        <th class="px-4 py-3 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($allKategori)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500 italic">
                                Data kosong
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allKategori as $index => $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border text-center"><?= $index + 1 ?></td>
                                <td class="px-4 py-2 border text-center"><?= $row['id_kategori'] ?></td>
                                <td class="px-4 py-2 border font-semibold"><?= $row['nama_kategori'] ?></td>
                                 <td class="px-4 py-2 border text-center space-x-3">
                                    <a href="/kategori/edit/<?= $row['id_kategori'] ?>" style="color: blue" class="hover:underline">Lihat</a>
                                    <button type="button"
                                        onclick="deleteKategoriAjax(<?= htmlspecialchars($row['id_kategori']) ?>)"
                                        class="hover:underline text-red-600">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php
require_once __DIR__ . '/components/footer.php';
?>