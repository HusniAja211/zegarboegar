<?php
require_once __DIR__ . '/components/header.php';
?>

<main class="min-h-screen p-8 bg-gray-50">
    <!-- Container -->
    <div class="max-w-6xl mx-auto flex flex-col space-y-6">

        <!-- Title -->
        <h1 class="text-2xl font-bold text-black">Data Transaksi</h1>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-200">
            <table class="w-full table-auto border-collapse text-sm text-black">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 border">No</th>
                        <th class="px-4 py-3 border">ID Transaksi</th>
                        <th class="px-4 py-3 border">Kode Transaksi</th>
                        <th class="px-4 py-3 border">ID Kasir</th>
                        <th class="px-4 py-3 border">ID Member</th>
                        <th class="px-4 py-3 border">Total</th>
                        <th class="px-4 py-3 border">Bayar</th>
                        <th class="px-4 py-3 border">Kembalian</th>
                        <th class="px-4 py-3 border">Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dataPage)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500 italic">
                                Data kosong
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dataPage as $index => $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border text-center"><?= $start + $index + 1 ?></td>
                                <td class="px-4 py-2 border text-center"><?= $row['id_transaksi'] ?></td>
                                <td class="px-4 py-2 border font-semibold"><?= $row['kode_transaksi'] ?></td>
                                <td class="px-4 py-2 border"><?= $row['id_kasir'] ?></td>
                                <td class="px-4 py-2 border text-center"><?= $row['id_member'] ?></td>
                                <td class="px-4 py-2 border text-center"><?= $row['total'] ?></td>
                                <td class="px-4 py-2 border text-center"><?= $row['bayar'] ?></td>
                                <td class="px-4 py-2 border text-center"><?= $row['kembalian'] ?></td>
                                <td class="px-4 py-2 border text-center"><?= $row['tanggal_dibuat'] ?></td>
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