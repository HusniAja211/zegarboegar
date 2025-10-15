<?php
require_once __DIR__ . '/components/header.php';
?>

<main class="min-h-screen p-8 bg-blue-50" 
      x-data="produkPage" 
      x-init="loadProducts('<?php echo $kategoriList[0] ?? ''; ?>')">

    <h1 class="text-3xl font-bold text-center text-indigo-800 mb-10">🛍️ Daftar Produk</h1>

    <!-- Tabs Navigation -->
    <div class="flex justify-between items-center mb-8">
        
        <!-- Kategori Tabs -->
        <div class="flex space-x-4 border-b border-indigo-200 overflow-x-auto">
            <?php foreach ($kategoriList as $kategori): ?>
                <button 
                    class="px-6 py-2 font-semibold whitespace-nowrap transition-all duration-200"
                    :class="tab === '<?= $kategori ?>' 
                        ? 'border-b-4 border-indigo-600 text-indigo-800 bg-indigo-100 rounded-t-lg' 
                        : 'text-indigo-500 hover:text-indigo-700'"
                    @click="changeTab('<?= $kategori ?>')">
                    <?= htmlspecialchars($kategori) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Tombol Tambah Produk -->
        <a href="/tambahproduk" 
           class="ml-6 bg-indigo-600 text-white px-5 py-2 rounded-lg font-semibold shadow-md hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2">
            Tambah Produk
        </a>
    </div>

    <!-- Loading -->
    <template x-if="loading">
        <p class="text-center text-indigo-500 font-medium mt-10">⏳ Memuat produk...</p>
    </template>

    <!-- Produk -->
    <div x-show="!loading" x-transition.opacity.duration.300ms>
        <div id="produkContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 justify-center"></div>
    </div>
</main>

<?php require_once __DIR__ . '/components/footer.php'; ?>