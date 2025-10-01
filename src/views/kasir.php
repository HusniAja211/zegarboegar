<?php
require_once __DIR__ . '/components/header.php';
?>

<div class="mt-6 flex justify-center">
    <div class="bg-white shadow-md rounded-lg overflow-hidden w-fit">
        <table class="table-auto border border-gray-200 text-center">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Nomor Telepon</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataPage as $index => $row): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border"><?= $start + $index + 1 ?></td>
                        <td class="px-4 py-2 border"><?= $row['id_kasir'] ?></td>
                        <td class="px-4 py-2 border font-semibold"><?= $row['nama_kasir'] ?></td>
                        <td class="px-4 py-2 border"><?= $row['email_kasir'] ?></td>
                        <td class="px-4 py-2 border"><?= $row['nomor_telepon_kasir'] ?></td>
                        <td class="px-4 py-2 border"><?= $row['status'] ?></td>
                        <td class="px-4 py-2 border space-x-2">
                            <a href="#" class="text-blue-600 hover:underline">Lihat</a>
                            <a href="#" class="text-green-600 hover:underline">Ubah</a>
                            <a href="#" style="color: red;" class="hover:underline">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
require __DIR__ . '/components/pagination.php';
renderPagination($page, $totalPages, $start, $perPage, $totalData, '/kasir?page=');
?>
</div>

<?php
require_once __DIR__ . '/components/footer.php';
?>