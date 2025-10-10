<?php
require_once __DIR__ . '/components/header.php';

// Cek apakah ini halaman edit
$isEdit = isset($produk);
$formAction = $isEdit ? '/produk/update' : '/produk/store';
$formTitle = $isEdit ? 'Edit Data Produk' : 'Form Entri Produk Baru';
$formSubtitle = $isEdit
    ? 'Ubah informasi produk di bawah dan simpan untuk memperbarui data.'
    : 'Lengkapi semua data yang diperlukan untuk menambahkan produk ke inventori.';
$submitText = $isEdit ? '💾 PERBARUI PRODUK' : '💾 SIMPAN PRODUK SEKARANG';
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

        <form action="<?= $formAction ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_produk" value="<?= htmlspecialchars($produk['id_produk']) ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b pb-6 border-gray-100">
                <div>
                    <label for="kode_produk" class="block text-sm font-bold text-gray-700 mb-1">Kode Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="kode_produk" name="kode_produk" required maxlength="50"
                        value="<?= htmlspecialchars($produk['kode_produk'] ?? '') ?>"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 shadow-sm"
                        placeholder="Contoh: MKN001"
                        <?= $isEdit ? 'readonly class="bg-gray-100 cursor-not-allowed w-full border border-gray-300 rounded-lg p-3 shadow-sm"' : '' ?>>
                </div>

                <div>
                    <label for="nama_produk" class="block text-sm font-bold text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_produk" name="nama_produk" required maxlength="150"
                        value="<?= htmlspecialchars($produk['nama_produk'] ?? '') ?>"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 shadow-sm"
                        placeholder="Contoh: Kemeja Flanel Biru">
                </div>
            </div>

            <div class="space-y-6 pt-4">
                <h2 class="text-xl font-bold text-blue-700 border-b-2 border-blue-500/50 pb-2">📦 Harga & Stok</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="modal" class="block text-sm font-bold text-gray-700 mb-1">Modal (Rp)</label>
                        <input type="number" id="modal" name="modal" step="0.01" required
                            value="<?= htmlspecialchars($produk['modal'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 shadow-sm text-right bg-blue-50/50"
                            placeholder="0.00">
                    </div>
                    <div>
                        <label for="harga_jual" class="block text-sm font-bold text-gray-700 mb-1">Harga Jual (Rp)</label>
                        <input type="number" id="harga_jual" name="harga_jual" step="0.01" required
                            value="<?= htmlspecialchars($produk['harga_jual'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 shadow-sm text-right bg-blue-50/50"
                            placeholder="0.00">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="id_kategori" class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                        <select id="id_kategori" name="id_kategori" required
                            class="w-full border border-gray-300 rounded-lg p-3 bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 shadow-sm appearance-none cursor-pointer">
                            <option value="" disabled>-- Pilih Kategori --</option>
                            <?php foreach ($listKategori as $kategori): ?>
                                <option value="<?= htmlspecialchars($kategori['id_kategori']) ?>"
                                    <?= (isset($produk['id_kategori']) && $produk['id_kategori'] == $kategori['id_kategori']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kategori['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="stok" class="block text-sm font-bold text-gray-700 mb-1">Stok</label>
                        <input type="number" id="stok" name="stok" required min="0"
                            value="<?= htmlspecialchars($produk['stok'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 shadow-sm text-right bg-white"
                            placeholder="0">
                    </div>
                    <div>
                        <label for="kadaluarsa" class="block text-sm font-bold text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                        <input type="date" id="kadaluarsa" name="kadaluarsa"
                            value="<?= htmlspecialchars($produk['kadaluarsa'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg p-3 bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 shadow-sm cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="space-y-6 pt-4">
                <h2 class="text-xl font-bold text-blue-700 border-b-2 border-blue-500/50 pb-2">🖼️ Detail Tambahan</h2>

                <div>
                    <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Produk</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition duration-200 shadow-sm resize-y"
                        placeholder="Tulis detail produk di sini..."><?= htmlspecialchars($produk['deskripsi'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="gambar" class="block text-sm font-bold text-gray-700 mb-1">Upload Gambar Produk</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition duration-200 cursor-pointer"/>
                    
                    <?php if ($isEdit && !empty($produk['gambar'])): ?>
                        <div class="mt-3">
                            <p class="text-xs text-gray-500 mb-1">Gambar Saat Ini:</p>
                            <img src="<?= htmlspecialchars($produk['gambar']) ?>" alt="Gambar Produk" class="w-28 h-28 rounded-lg shadow-md border border-gray-200 object-cover">
                        </div>
                    <?php endif; ?>
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
