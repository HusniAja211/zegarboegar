<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Akun - Kasir Digital</title>

  <!-- Tailwind -->
  <link href="/css/app.css" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-image: url('/images/outer_background/outer_background.jpg'); background-size: cover; background-position: center;"
      class="text-blue-900 min-h-screen flex items-center justify-center">

  <!-- Wrapper Utama -->
  <div class="w-[90%] md:w-[75%] max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

    <!-- Kiri (Form Register) -->
    <div class="flex items-center justify-center p-8">
      <div class="w-full max-w-sm space-y-4">
        <div>
          <h2 class="text-2xl font-bold">Buat Akun</h2>
          <p class="text-xs text-blue-600">
            Sudah punya akun? 
            <a href="/login" class="text-blue-500 font-bold hover:underline">Login</a>
          </p>
        </div>

        <form action="/register" method="POST" class="space-y-3 text-sm">
          <input type="text" name="nama_kasir" placeholder="Nama Lengkap" required
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <input type="email" name="email_kasir" placeholder="Email" required
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <input type="tel" name="nomor_telepon_kasir" placeholder="Nomor WhatsApp" required
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <input type="password" name="password_kasir" placeholder="Password" minlength="8" maxlength="8" required
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <input type="password" name="confirm_password" placeholder="Konfirmasi Password" minlength="8" maxlength="8" required
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <label class="flex items-center text-xs text-blue-700">
            <input type="checkbox" name="terms" class="mr-2 accent-blue-500" required>
            Saya setuju dengan <a href="#" class="ml-1 text-blue-500 hover:underline">Syarat & Ketentuan</a>
          </label>

          <button type="submit"
            class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium shadow-md transition text-sm">
            Buat Akun
          </button>
        </form>

        <!-- Garis pembatas -->
        <div class="flex items-center">
          <div class="flex-1 h-px bg-blue-200"></div>
          <p class="px-2 text-blue-500 text-xs">atau daftar dengan</p>
          <div class="flex-1 h-px bg-blue-200"></div>
        </div>

        <!-- Tombol sosial -->
        <div class="flex flex-col justify-center sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
          <button class="w-full sm:w-1/2 py-2 border border-blue-300 rounded-lg flex items-center justify-center hover:bg-blue-50 transition text-xs">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4 mr-1"> Google
          </button>
        </div>
      </div>
    </div>

    <!-- Kanan (Ilustrasi) -->
    <div class="hidden md:flex flex-col items-center justify-center bg-gradient-to-b from-blue-50 to-blue-100 p-8">
      <img src="images/digitalCashier_illust/digitalCashier_illust.jpg" 
           alt="Ilustrasi Kasir Digital"
           class="rounded-xl shadow-lg mb-4">
      <p class="text-blue-700 text-sm font-medium text-center">
        Kasir Digital untuk bisnismu,<br> cepat, praktis, dan modern.
      </p>
    </div>
  </div>

  <!-- SweetAlert Error -->
  <?php if (isset($_GET['error'])): ?>
    <script>
      <?php if ($_GET['error'] === 'email'): ?>
        Swal.fire({icon: 'error', title: 'Email sudah terdaftar', text: 'Gunakan email lain.'});
      <?php elseif ($_GET['error'] === 'password_mismatch'): ?>
        Swal.fire({icon: 'error', title: 'Password tidak sama', text: 'Konfirmasi password harus cocok.'});
      <?php elseif ($_GET['error'] === 'terms'): ?>
        Swal.fire({icon: 'warning', title: 'Syarat & Ketentuan', text: 'Anda harus setuju dulu.'});
      <?php else: ?>
        Swal.fire({icon: 'error', title: 'Registrasi gagal', text: 'Terjadi kesalahan. Coba lagi.'});
      <?php endif; ?>
    </script>
  <?php endif; ?>

</body>
</html>
