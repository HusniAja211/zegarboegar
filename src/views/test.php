<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Tabbed Navigation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS Kustom Monokrom Biru */
        .color-primary { background-color: #1e3a8a; } /* Biru Tua/Navy */
        .color-secondary { color: #3b82f6; } /* Biru Cerah */
        .color-accent { background-color: #3b82f6; }

        /* Penyesuaian Barcode Visual */
        .visual-barcode {
            font-family: monospace; font-weight: bold; font-size: 1rem; line-height: 1.5; letter-spacing: 0.1rem;
            color: #1e3a8a; text-shadow: 1px 0 #1e3a8a, 2px 0 #1e3a8a, 3px 0 #1e3a8a; 
            white-space: nowrap;
        }

        /* ----- TABBED NAVIGATION STYLING ----- */
        /* Sembunyikan semua konten tab secara default */
        .tab-content {
            display: none;
        }

        /* Tampilkan konten tab pertama (tab default) */
        #tab-elektronik-1:checked ~ .tab-pages > #page-elektronik-1,
        #tab-pakaian-1:checked ~ .tab-pages > #page-pakaian-1 {
            display: grid; /* Tampilkan sebagai Grid */
        }
        
        /* Tambahkan logika untuk Tab 2, dst. (diperlukan JS untuk navigasi fungsional) */
        /* Untuk demo, kita buat tab 2 elektronik aktif jika di-klik */
        #tab-elektronik-2:checked ~ .tab-pages > #page-elektronik-2 {
             display: grid;
        }

        /* Styling saat tab di-klik */
        .tab-button:checked + label {
            background-color: #3b82f6;
            color: white;
            border-bottom: 2px solid #3b82f6;
        }

    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <header class="color-primary text-white shadow-lg">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold">Katalog Produk dengan Tabbed Navigation</h1>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <section class="mb-16 border border-gray-200 rounded-lg bg-white p-6 shadow-md">
            <h2 class="text-3xl font-extrabold color-secondary mb-4 pb-2">
                ⚡ Elektronik (Maks 10 Produk)
            </h2>
            
            <div class="flex border-b border-gray-200 mb-6">
                <input type="radio" name="elektronik_tabs" id="tab-elektronik-1" class="hidden tab-input" checked>
                <label for="tab-elektronik-1" class="tab-label px-4 py-2 text-gray-600 font-semibold cursor-pointer border-b-2 border-transparent hover:border-blue-400 transition duration-150">Halaman 1 (1-3)</label>

                <input type="radio" name="elektronik_tabs" id="tab-elektronik-2" class="hidden tab-input">
                <label for="tab-elektronik-2" class="tab-label px-4 py-2 text-gray-600 font-semibold cursor-pointer border-b-2 border-transparent hover:border-blue-400 transition duration-150">Halaman 2 (4-6)</label>
                
                </div>

            <div class="tab-pages">
                
                <div id="page-elektronik-1" class="grid grid-cols-1 md:grid-cols-3 gap-8 tab-content">
                    <div class="max-w-xs bg-white rounded-xl shadow-xl overflow-hidden transform hover:shadow-2xl hover:scale-[1.03] transition duration-300 group">
                        <div class="relative h-44 w-full"><img class="w-full h-full object-cover" src="https://via.placeholder.com/400x400/3b82f6/ffffff?text=Smartphone" alt="Smartphone"></div>
                        <div class="p-5">
                            <h4 class="text-xl font-extrabold text-gray-900 leading-tight truncate">Smartphone X1</h4>
                            <p class="text-xs text-gray-500 mb-3">Kode: EL-SM-001</p>
                            <div class="flex items-center justify-between">
                                <div><p class="text-2xl font-black text-blue-700">Rp 5.000.000</p></div>
                                <p class="text-sm font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Stok: 5</p>
                            </div>
                        </div>
                        <div class="p-5 pt-0"><button class="w-full color-accent text-white py-2 rounded-lg font-bold hover:bg-blue-700">Beli</button></div>
                        <div class="p-3 bg-blue-50 text-center border-t border-blue-200"><div class="visual-barcode">123456789012</div></div>
                    </div>

                    <div class="max-w-xs bg-white rounded-xl shadow-xl overflow-hidden transform hover:shadow-2xl hover:scale-[1.03] transition duration-300 group">
                        <div class="relative h-44 w-full"><img class="w-full h-full object-cover" src="https://via.placeholder.com/400x400/1e3a8a/ffffff?text=Laptop" alt="Laptop"></div>
                        <div class="p-5">
                            <h4 class="text-xl font-extrabold text-gray-900 leading-tight truncate">Laptop Pro-14</h4>
                            <p class="text-xs text-gray-500 mb-3">Kode: EL-LP-002</p>
                            <div class="flex items-center justify-between">
                                <div><p class="text-2xl font-black text-blue-700">Rp 12.5 Jt</p></div>
                                <p class="text-sm font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Stok: 8</p>
                            </div>
                        </div>
                        <div class="p-5 pt-0"><button class="w-full color-accent text-white py-2 rounded-lg font-bold hover:bg-blue-700">Beli</button></div>
                        <div class="p-3 bg-blue-50 text-center border-t border-blue-200"><div class="visual-barcode">123456789013</div></div>
                    </div>
                    
                    <div class="max-w-xs bg-white rounded-xl shadow-xl overflow-hidden transform hover:shadow-2xl hover:scale-[1.03] transition duration-300 group">
                        <div class="relative h-44 w-full"><img class="w-full h-full object-cover" src="https://via.placeholder.com/400x400/93c5fd/1e3a8a?text=Headphone" alt="Headphone"></div>
                        <div class="p-5">
                            <h4 class="text-xl font-extrabold text-gray-900 leading-tight truncate">Headphone BT</h4>
                            <p class="text-xs text-gray-500 mb-3">Kode: EL-HP-003</p>
                            <div class="flex items-center justify-between">
                                <div><p class="text-2xl font-black text-blue-700">Rp 899.000</p></div>
                                <p class="text-sm font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Stok: 15</p>
                            </div>
                        </div>
                        <div class="p-5 pt-0"><button class="w-full color-accent text-white py-2 rounded-lg font-bold hover:bg-blue-700">Beli</button></div>
                        <div class="p-3 bg-blue-50 text-center border-t border-blue-200"><div class="visual-barcode">123456789014</div></div>
                    </div>
                </div> 
                
                <div id="page-elektronik-2" class="grid grid-cols-1 md:grid-cols-3 gap-8 tab-content">
                     <div class="max-w-xs bg-white rounded-xl shadow-xl overflow-hidden transform hover:shadow-2xl hover:scale-[1.03] transition duration-300 group">
                        <div class="relative h-44 w-full"><img class="w-full h-full object-cover" src="https://via.placeholder.com/400x400/bfdbfe/1e3a8a?text=Smartwatch" alt="Smartwatch"></div>
                        <div class="p-5">
                            <h4 class="text-xl font-extrabold text-gray-900 leading-tight truncate">Smartwatch S1</h4>
                            <p class="text-xs text-gray-500 mb-3">Kode: EL-SW-004</p>
                            <div class="flex items-center justify-between">
                                <div><p class="text-2xl font-black text-blue-700">Rp 1.500.000</p></div>
                                <p class="text-sm font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Stok: 10</p>
                            </div>
                        </div>
                        <div class="p-5 pt-0"><button class="w-full color-accent text-white py-2 rounded-lg font-bold hover:bg-blue-700">Beli</button></div>
                        <div class="p-3 bg-blue-50 text-center border-t border-blue-200"><div class="visual-barcode">123456789015</div></div>
                    </div>
                    </div>

            </div>
        </section>

        <hr class="my-10 border-blue-200">

        <section class="mb-16 border border-gray-200 rounded-lg bg-white p-6 shadow-md">
            <h2 class="text-3xl font-extrabold color-secondary mb-4 pb-2">
                👕 Pakaian (Maks 10 Produk)
            </h2>
            
            <div class="flex border-b border-gray-200 mb-6">
                <input type="radio" name="pakaian_tabs" id="tab-pakaian-1" class="hidden tab-input" checked>
                <label for="tab-pakaian-1" class="tab-label px-4 py-2 text-gray-600 font-semibold cursor-pointer border-b-2 border-transparent hover:border-blue-400 transition duration-150">Halaman 1 (1-3)</label>

                </div>

            <div class="tab-pages">
                <div id="page-pakaian-1" class="grid grid-cols-1 md:grid-cols-3 gap-8 tab-content">
                    <div class="max-w-xs bg-white rounded-xl shadow-xl overflow-hidden transform hover:shadow-2xl hover:scale-[1.03] transition duration-300 group">
                        <div class="relative h-44 w-full"><img class="w-full h-full object-cover" src="https://via.placeholder.com/400x400/93c5fd/1e3a8a?text=Kemeja" alt="Kemeja"></div>
                        <div class="p-5">
                            <h4 class="text-xl font-extrabold text-gray-900 leading-tight truncate">Kemeja Linen</h4>
                            <p class="text-xs text-gray-500 mb-3">Kode: PK-KL-001</p>
                            <div class="flex items-center justify-between">
                                <div><p class="text-2xl font-black text-blue-700">Rp 250.000</p></div>
                                <p class="text-sm font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Stok: 20</p>
                            </div>
                        </div>
                        <div class="p-5 pt-0"><button class="w-full color-accent text-white py-2 rounded-lg font-bold hover:bg-blue-700">Beli</button></div>
                        <div class="p-3 bg-blue-50 text-center border-t border-blue-200"><div class="visual-barcode">223456789012</div></div>
                    </div>
                    
                    <div class="max-w-xs bg-white rounded-xl shadow-xl overflow-hidden transform hover:shadow-2xl hover:scale-[1.03] transition duration-300 group">
                        <div class="relative h-44 w-full"><img class="w-full h-full object-cover" src="https://via.placeholder.com/400x400/1e3a8a/ffffff?text=Jeans" alt="Jeans"></div>
                        <div class="p-5">
                            <h4 class="text-xl font-extrabold text-gray-900 leading-tight truncate">Jeans Slim Fit</h4>
                            <p class="text-xs text-gray-500 mb-3">Kode: PK-JF-002</p>
                            <div class="flex items-center justify-between">
                                <div><p class="text-2xl font-black text-blue-700">Rp 450.000</p></div>
                                <p class="text-sm font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Stok: 18</p>
                            </div>
                        </div>
                        <div class="p-5 pt-0"><button class="w-full color-accent text-white py-2 rounded-lg font-bold hover:bg-blue-700">Beli</button></div>
                        <div class="p-3 bg-blue-50 text-center border-t border-blue-200"><div class="visual-barcode">223456789013</div></div>
                    </div>

                    <div class="max-w-xs bg-white rounded-xl shadow-xl overflow-hidden transform hover:shadow-2xl hover:scale-[1.03] transition duration-300 group">
                        <div class="relative h-44 w-full"><img class="w-full h-full object-cover" src="https://via.placeholder.com/400x400/60a5fa/ffffff?text=Jaket" alt="Jaket"></div>
                        <div class="p-5">
                            <h4 class="text-xl font-extrabold text-gray-900 leading-tight truncate">Jaket Bomber Navy</h4>
                            <p class="text-xs text-gray-500 mb-3">Kode: PK-JB-003</p>
                            <div class="flex items-center justify-between">
                                <div><p class="text-2xl font-black text-blue-700">Rp 650.000</p></div>
                                <p class="text-sm font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Stok: 9</p>
                            </div>
                        </div>
                        <div class="p-5 pt-0"><button class="w-full color-accent text-white py-2 rounded-lg font-bold hover:bg-blue-700">Beli</button></div>
                        <div class="p-3 bg-blue-50 text-center border-t border-blue-200"><div class="visual-barcode">223456789014</div></div>
                    </div>
                </div>
            </div>
        </section>

    </main>

</body>
</html>