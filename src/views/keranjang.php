<?php 
require_once __DIR__ . '/components/header.php';

// Data Dummy Transaksi (Tidak Berubah)
$keranjang = [
    ['id' => 101, 'nama' => 'Baju Kaos Biru Navy', 'harga' => 150000, 'kuantitas' => 2],
    ['id' => 102, 'nama' => 'Celana Jeans Slim Fit', 'harga' => 350000, 'kuantitas' => 1],
];

$subtotal = array_reduce($keranjang, function($sum, $item) {
    return $sum + ($item['harga'] * $item['kuantitas']);
}, 0);

$biayaPengiriman = 25000;
$totalKotor = $subtotal + $biayaPengiriman;

// Data Dummy Member (POIN DIPINDAH KE JS UNTUK SIMULASI PENCARIAN)
$nilaiPoinPerRupiah = 1;

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>

<main class="min-h-screen p-4 sm:p-8 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-extrabold text-blue-900 mb-8 border-b border-blue-200 pb-2">
            🛒 Keranjang Belanja & Pembayaran
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-4">
                <?php if (empty($keranjang)): ?>
                    <div class="p-6 bg-white border-2 border-dashed border-blue-300 text-center text-blue-700 rounded-lg shadow-sm">
                        <p class="text-xl font-semibold">Keranjang Anda Kosong.</p>
                        <a href="/produk" class="text-blue-600 hover:text-blue-800 underline mt-2 inline-block">Mulai Belanja Sekarang!</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($keranjang as $item): ?>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center bg-white p-4 rounded-xl shadow-lg border border-blue-100 transition duration-300 hover:shadow-xl">
                            
                            <div class="flex-grow w-full mt-3 sm:mt-0">
                                <h2 class="text-lg font-semibold text-blue-800"><?= htmlspecialchars($item['nama']) ?></h2>
                                <p class="text-sm text-gray-500">Harga Satuan: <?= formatRupiah($item['harga']) ?></p>
                                
                                <div class="flex items-center space-x-4 mt-2">
                                    <div class="flex items-center border border-blue-300 rounded-md">
                                        <button class="px-3 py-1 text-blue-700 hover:bg-blue-50 transition duration-150">-</button>
                                        <input type="text" value="<?= $item['kuantitas'] ?>" class="w-10 text-center border-x border-blue-300 focus:outline-none text-blue-900 bg-white" readonly>
                                        <button class="px-3 py-1 text-blue-700 hover:bg-blue-50 transition duration-150">+</button>
                                    </div>
                                    <button class="text-red-500 hover:text-red-700 text-sm font-medium transition duration-150">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-4 sm:mt-0 flex-shrink-0 text-right w-full sm:w-auto">
                                <p class="text-xl font-bold text-blue-900">
                                    <?= formatRupiah($item['harga'] * $item['kuantitas']) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-600 sticky top-8" id="ringkasan-pembayaran">
                    <h2 class="text-2xl font-bold text-blue-900 mb-4 border-b border-blue-200 pb-2">
                        Ringkasan Pembayaran
                    </h2>
                    
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <label for="input-member-telp" class="block text-sm font-medium text-blue-900 mb-1">
                            Nomor Telepon Member (Opsional)
                        </label>
                        <input type="text" id="input-member-telp" placeholder="Cari 08xx..." 
                            class="w-full p-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base font-semibold text-gray-800">
                        
                        <p id="member-info" class="text-sm mt-2 text-gray-500 italic">
                            Member belum teridentifikasi.
                        </p>
                    </div>
                    <div class="mb-4 bg-blue-50 p-3 rounded-lg border border-blue-200">
                        <p class="text-sm font-semibold text-blue-700">Poin Member Saat Ini:</p>
                        <p class="text-lg font-bold text-blue-900" data-poin-nilai="0" id="poin-saat-ini">
                            <?= formatRupiah(0) ?> (0 Poin)
                        </p>
                    </div>

                    <div class="space-y-3 text-gray-700">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-medium"><?= formatRupiah($subtotal) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pengiriman</span>
                            <span class="font-medium"><?= formatRupiah($biayaPengiriman) ?></span>
                        </div>

                        <div class="flex justify-between items-center text-green-700 font-semibold border-t border-blue-200 pt-3">
                            <span>Potongan Poin</span>
                            <input type="number" id="input-potongan-poin" value="0" placeholder="0" 
                                class="w-24 text-right border-b border-green-300 focus:outline-none focus:border-green-500 text-green-700 p-0.5 bg-white font-medium" disabled>
                        </div>

                        <div class="pt-4 border-t border-blue-200 flex justify-between text-xl font-extrabold text-blue-900">
                            <span>Total Tagihan</span>
                            <span id="total-tagihan" data-total-kotor="<?= $totalKotor ?>"><?= formatRupiah($totalKotor) ?></span>
                        </div>
                    </div>

                    <hr class="my-4 border-blue-200">
                    
                    <div class="space-y-3">
                        <label for="input-uang" class="block text-sm font-medium text-blue-900">
                            Jumlah Uang Masuk (Bayar)
                        </label>
                        <input type="number" id="input-uang" placeholder="Masukkan jumlah uang" 
                            class="w-full p-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xl font-bold text-gray-800">
                    </div>
                    
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-300 text-center">
                        <p class="text-sm text-blue-700">Kembalian</p>
                        <p class="text-2xl font-extrabold text-blue-900" id="kembalian">
                            <?= formatRupiah(0) ?>
                        </p>
                    </div>

                    <button id="btn-checkout" class="mt-6 w-full py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-300 transform hover:scale-[1.01]" disabled>
                        Selesaikan Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
<?php 
require_once __DIR__ . '/components/footer.php';
?>