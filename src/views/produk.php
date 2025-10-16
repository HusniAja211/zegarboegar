<?php
require_once __DIR__ . '/components/header.php';

$kategoriList = $kategoriList ?? ['Semua Produk', 'Makanan', 'Minuman'];
$initialCategory = $kategoriList[0] ?? 'Semua Produk';
?>

<main class="min-h-screen p-8 bg-blue-50">

    <h1 class="text-3xl font-bold text-center text-indigo-800 mb-10">
        🛍️ Daftar Produk
    </h1>

    <!-- Navigasi Kategori -->
    <div class="flex justify-between items-center mb-8">
        <div id="kategoriTabs" class="flex space-x-4 border-b border-indigo-200 overflow-x-auto">
            <?php foreach ($kategoriList as $kategori): ?>
                <button 
                    class="kategori-tab px-6 py-2 font-semibold whitespace-nowrap transition-all duration-200"
                    data-kategori="<?= htmlspecialchars($kategori) ?>">
                    <?= htmlspecialchars($kategori) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <a href="/tambahproduk" 
           class="ml-6 bg-indigo-600 text-white px-5 py-2 rounded-lg font-semibold shadow-md hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2">
            Tambah Produk
        </a>
    </div>

    <!-- Pesan Loading -->
    <p id="loadingMessage" class="text-center text-indigo-500 font-medium mt-10 hidden">
        ⏳ Memuat produk...
    </p>

    <!-- Kontainer Produk -->
    <div id="produkContainer" 
         class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 justify-center">
        <!-- Produk akan dimuat lewat JS -->
    </div>

</main>

<?php require_once __DIR__ . '/components/footer.php'; ?>
