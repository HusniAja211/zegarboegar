<?php
/**
 * Komponen Pagination
 * 
 * Cara pakai:
 * require __DIR__ . '/components/pagination.php';
 * renderPagination($page, $totalPages, $start, $perPage, $totalData, '/kasir?page=');
 */

function renderPagination($page, $totalPages, $start, $perPage, $totalData, $baseUrl) {
    ?>
    <div class="mt-6 mb-20 flex flex-col items-center space-y-4">
        <!-- Info data -->
        <div class="text-sm text-gray-600">
            Menampilkan <?= $start + 1 ?> - <?= min($start + $perPage, $totalData) ?> dari <?= $totalData ?> data
        </div>

        <!-- Navigasi -->
        <div class="flex justify-center flex-wrap gap-1">
            <!-- Tombol Sebelumnya -->
            <?php if ($page > 1): ?>
                <a href="<?= $baseUrl . ($page - 1) ?>" 
                   class="px-3 py-1 border rounded bg-white hover:bg-gray-100">«</a>
            <?php endif; ?>

            <?php
            $visibleRange = 2; // jumlah halaman kiri-kanan dari current

            // halaman pertama
            if ($page > ($visibleRange + 2)) {
                echo '<a href="'.$baseUrl.'1" class="px-3 py-1 border rounded bg-white hover:bg-gray-100">1</a>';
                echo '<span class="px-3 py-1">...</span>';
            }

            // halaman sekitar current
            for ($i = max(1, $page - $visibleRange); $i <= min($totalPages, $page + $visibleRange); $i++) {
                if ($i == $page) {
                    echo '<span class="px-3 py-1 border rounded bg-blue-600 text-white font-semibold">'.$i.'</span>';
                } else {
                    echo '<a href="'.$baseUrl.$i.'" class="px-3 py-1 border rounded bg-white hover:bg-gray-100">'.$i.'</a>';
                }
            }

            // halaman terakhir
            if ($page < $totalPages - ($visibleRange + 1)) {
                echo '<span class="px-3 py-1">...</span>';
                echo '<a href="'.$baseUrl.$totalPages.'" class="px-3 py-1 border rounded bg-white hover:bg-gray-100">'.$totalPages.'</a>';
            }
            ?>

            <!-- Tombol Selanjutnya -->
            <?php if ($page < $totalPages): ?>
                <a href="<?= $baseUrl . ($page + 1) ?>" 
                   class="px-3 py-1 border rounded bg-white hover:bg-gray-100">»</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
