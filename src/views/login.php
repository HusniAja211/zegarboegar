<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Kasir Digital</title>

  <!-- Tailwind -->
  <link href="/css/app.css" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="background-image: url('/images/outer_background/outer_background.jpg'); background-size: cover; background-position: center;"
  class="text-blue-900 min-h-screen flex items-center justify-center">

  <!-- Wrapper Utama -->
  <div class="w-[90%] md:w-[75%] max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

    <!-- Kiri (Ilustrasi) -->
    <div class="hidden md:flex flex-col items-center justify-center bg-gradient-to-b from-blue-50 to-blue-100 p-8">
      <img src="images/digitalCashier_illust/digitalCashier_illust.jpg"
        alt="Ilustrasi Kasir Digital Login"
        class="rounded-xl shadow-lg mb-4">
      <p class="text-blue-700 text-sm font-medium text-center">
        Akses cepat ke Kasir Digital<br> dan kelola bisnismu dengan mudah.
      </p>
    </div>

    <!-- Kanan (Form Login) -->
    <div class="flex items-center justify-center p-8">
      <div class="w-full max-w-sm space-y-5">
        <div>
          <h2 class="text-2xl font-bold">Login</h2>
          <p class="text-xs text-blue-600">
            Belum punya akun?
            <a href="/register" class="text-blue-500 font-bold hover:underline">Daftar</a>
          </p>
        </div>

        <form action="/login" method="POST" class="space-y-4 text-sm">
          <!-- Email -->
          <input type="email" name="email_kasir" placeholder="Email" required
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <!-- Password -->
          <input type="password" name="password_kasir" placeholder="Password" minlength="8" maxlength="8" required
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">

          <!-- Link Lupa Password -->
          <div class="text-right">
            <a href="/forgetPassword" class="text-xs text-blue-500 hover:underline font-medium">
              Lupa Password?
            </a>
          </div>

          <button type="submit"
            class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium shadow-md transition text-sm">
            Login
          </button>
        </form>

        <!-- Garis pembatas -->
        <div class="flex items-center">
          <div class="flex-1 h-px bg-blue-200"></div>
          <p class="px-2 text-blue-500 text-xs">atau login dengan</p>
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
  </div>

  <!-- SweetAlert Notif -->
  <?php if (isset($_GET['success']) && $_GET['success'] === '1'): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Registrasi Berhasil!',
        text: 'Akun kamu sudah dibuat. Silakan login sekarang.',
        confirmButtonColor: '#2563eb'
      });
    </script>

  <?php elseif (isset($_GET['success']) && $_GET['success'] === 'logout'): ?>
    <script>
      Swal.fire({
        icon: 'info',
        title: 'Logout Berhasil',
        text: 'Kamu sudah keluar dari sistem.',
        confirmButtonColor: '#2563eb'
      });
    </script>

  <?php elseif (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Login Gagal!',
        text: 'Email atau password salah!',
        confirmButtonColor: '#dc2626'
      });
    </script>
  <?php endif; ?>


</body>

</html>