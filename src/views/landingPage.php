<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Digital</title>

    <!-- Tailwind build -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body class="bg-white text-blue-900">

    <!-- Hero Section -->
    <section class="relative bg-blue-900 text-white">
        <div class="max-w-6xl mx-auto px-6 py-24 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div data-aos="fade-right">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Kasir Online yang Cepat & Praktis untuk Bisnismu</h1>
                <p class="mb-6 text-lg">Atur transaksi, stok, dan laporan penjualan secara otomatis dalam satu platform.</p>
                <a href="/register" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg transition">Coba Gratis</a>
            </div>
            <div data-aos="fade-left" class="flex justify-center">
                <img src="images/digitalCashier_illust/digitalCashier_illust.jpg"
                    alt="Kasir Digital" class="rounded-xl shadow-lg">
            </div>
        </div>
    </section>

    <!-- Fitur Utama -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div data-aos="fade-up" class="p-6 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-4xl mb-3">🧾</div>
                <h3 class="font-semibold text-xl mb-2">Catat Transaksi Otomatis</h3>
                <p>Semua penjualan langsung tercatat rapi tanpa ribet.</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="150" class="p-6 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-4xl mb-3">📊</div>
                <h3 class="font-semibold text-xl mb-2">Laporan Real-time</h3>
                <p>Analisis bisnis kapanpun dengan data terbaru.</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="300" class="p-6 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-4xl mb-3">📦</div>
                <h3 class="font-semibold text-xl mb-2">Manajemen Stok Mudah</h3>
                <p>Pantau barang masuk & keluar secara otomatis.</p>
            </div>
        </div>
    </section>

    <!-- Tampilan Produk -->
    <section class="py-16 bg-blue-50">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-8" data-aos="fade-up">Tampilan Kasir Digital</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Gambar Dashboard -->
                <div data-aos="zoom-in" class="flex flex-col items-center">
                    <img src="https://cdn.dribbble.com/userupload/16759387/file/original-42f2b27bd06b4a0f068ae9771f20f2ac.png?resize=752x&vertical=center"
                        alt="Dashboard Kasir"
                        class="rounded-xl shadow-lg mb-3">
                    <p class="text-sm text-blue-800">Tampilan dashboard untuk memantau penjualan dan laporan real-time.</p>
                </div>

                <!-- Gambar Kasir Digital -->
                <div data-aos="zoom-in" data-aos-delay="150" class="flex flex-col items-center">
                    <img src="https://user-images.githubusercontent.com/61135648/85206187-da564b00-b352-11ea-8dcc-444a537813b4.png"
                        alt="Kasir Digital"
                        class="rounded-xl shadow-lg mb-3">
                    <p class="text-sm text-blue-800">Ilustrasi kasir digital dengan interface sederhana dan mudah digunakan.</p>
                </div>

            </div>
        </div>
    </section>


    <!-- Testimoni -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-8" data-aos="fade-up">Apa Kata Pengguna</h2>
            <div class="space-y-8">
                <blockquote data-aos="fade-up" class="p-6 bg-blue-50 rounded-xl shadow">
                    <p class="italic mb-2">“Semenjak pakai Kasir Digital, laporan penjualan lebih rapi dan stok selalu terkontrol.”</p>
                    <span class="font-semibold">— Husni, Pemilik Toko Risol</span>
                </blockquote>
                <blockquote data-aos="fade-up" data-aos-delay="150" class="p-6 bg-blue-50 rounded-xl shadow">
                    <p class="italic mb-2">“Sangat membantu untuk bisnis kecil saya. Simple tapi powerful!”</p>
                    <span class="font-semibold">— Sri, Warung Kopi</span>
                </blockquote>
                <blockquote data-aos="fade-up" data-aos-delay="150" class="p-6 bg-blue-50 rounded-xl shadow">
                    <p class="italic mb-2">“Sangat membantu untuk bisnis kecil saya. Simple tapi powerful!”</p>
                    <span class="font-semibold">— Muklis, Pemilik Dapur Umum Program MBG</span>
                </blockquote>
            </div>
        </div>
    </section>

    <!-- CTA Kedua -->
    <section class="py-20 bg-blue-900 text-center text-white">
        <h2 class="text-3xl font-bold mb-4" data-aos="fade-up">Mulai Gratis 14 Hari Sekarang</h2>
        <p class="mb-6 text-lg" data-aos="fade-up">Coba semua fitur premium tanpa biaya.</p>
        <a href="/register" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg transition" data-aos="zoom-in">Daftar Gratis</a>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-950 text-blue-200 py-8">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
            <div>&copy; © Husni Mubarak. Zoegar Boegar. 2025. All rights reserved.</div>
            <div class="space-x-4">
                <a href="#" class="hover:text-white">Tentang</a>
                <a href="#" class="hover:text-white">Kontak</a>
                <a href="#" class="hover:text-white">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>

    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>