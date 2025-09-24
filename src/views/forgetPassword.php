<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password - Kasir Digital</title>

  <!-- Tailwind -->
  <link href="/css/app.css" rel="stylesheet">
</head>
<body style="background-image: url('/images/outer_background/outer_background.jpg'); background-size: cover; background-position: center;"
      class="text-blue-900 min-h-screen flex items-center justify-center">

  <!-- Wrapper Utama -->
  <div class="w-[90%] md:w-[60%] max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

    <!-- Kiri (Form Reset Password) -->
    <div class="flex items-center justify-center p-8">
      <div class="w-full max-w-sm space-y-5">
        <div>
          <h2 class="text-2xl font-bold">Lupa Password</h2>
          <p class="text-xs text-blue-600">
            Ingat password?
            <a href="/login" class="text-blue-500 font-bold hover:underline">Login</a>
          </p>
        </div>

        <form action="#" method="POST" class="space-y-4 text-sm">
          <!-- Email -->
          <input type="email" name="email" placeholder="Email"
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 
                   focus:outline-none focus:ring-2 focus:ring-blue-500">

          <!-- OTP -->
          <input type="text" name="otp" placeholder="Kode OTP"
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 
                   focus:outline-none focus:ring-2 focus:ring-blue-500">

          <!-- New Password -->
          <input type="password" name="new_password" placeholder="Password Baru"
            class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 
                   focus:outline-none focus:ring-2 focus:ring-blue-500">

          <!-- Tombol -->
          <button type="submit"
            class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded-lg 
                   text-white font-medium shadow-md transition text-sm">
            Reset Password
          </button>
        </form>
      </div>
    </div>

    <!-- Kanan (Ilustrasi) -->
    <div class="hidden md:flex flex-col items-center justify-center bg-gradient-to-b from-blue-50 to-blue-100 p-8">
      <img src="images/digitalCashier_illust/digitalCashier_illust.jpg" 
           alt="Ilustrasi Reset Password"
           class="rounded-xl shadow-lg mb-4">
      <p class="text-blue-700 text-sm font-medium text-center">
        Atur ulang password untuk keamanan akunmu.<br> Masukkan email, OTP, dan password baru.
      </p>
    </div>
  </div>

</body>
</html>
