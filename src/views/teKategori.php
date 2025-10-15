<?php
require_once __DIR__ . '/components/header.php';

// Cek apakah ini halaman edit
$isEdit = isset($kategori) && !empty($kategori);
$formAction = $isEdit ? '/kategori/update' : '/kategori/store';
$formTitle = $isEdit ? 'Edit Data Kategori' : 'Form Entri Kategori Baru';
$formSubtitle = $isEdit
    ? 'Ubah informasi kategori di bawah dan simpan untuk memperbarui data.'
    : 'Lengkapi semua data yang diperlukan untuk menambah kategori baru.';
$submitText = $isEdit ? '💾 Perbarui Kategori' : '💾 Simpan Kategori Sekarang';
$submitColor = $isEdit ? 'bg-amber-600 hover:bg-amber-700 shadow-amber-400/40' : 'bg-blue-700 hover:bg-blue-800 shadow-blue-500/40';
?>

<main class="min-h-screen flex items-center justify-center bg-gray-50 p-8 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-4xl bg-white shadow-2xl rounded-xl overflow-hidden border border-blue-100/80">
        
        <div class="bg-blue-600 p-6 text-white text-center">
            <h1 class="text-3xl font-extrabold tracking-wider">
                <?= htmlspecialchars($formTitle) ?>
            </h1>
            <p class="mt-1 text-blue-100"><?= htmlspecialchars($formSubtitle) ?></p>
        </div>

        <form action="<?= $formAction ?>" method="POST" class="p-8 space-y-6">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_kategori" value="<?= htmlspecialchars($kategori['id_kategori']) ?>">
            <?php endif; ?>

            <div class="space-y-6 pt-4">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="namaKategori" class="block text-sm font-bold text-gray-700 mb-1">Nama Kategori</label>
                        <input type="text" id="namaKategori" name="namaKategori" required
                            value="<?= htmlspecialchars($kategori['nama_kategori'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 shadow-sm bg-blue-50/50">
                    </div>
                    
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="w-full <?= $submitColor ?> text-black py-3.5 rounded-lg font-extrabold text-xl shadow-xl transition duration-300 transform hover:scale-[1.005] focus:outline-none focus:ring-4 focus:ring-blue-300 tracking-wider">
                    <?= $submitText ?>
                </button>
            </div>
        </form>
    </div>
</main>

<?php
require_once __DIR__ . '/components/footer.php';
?>
