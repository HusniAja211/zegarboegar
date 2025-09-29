<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password - Kasir Digital</title>
  <link href="/css/app.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-image: url('/images/outer_background/outer_background.jpg'); background-size: cover; background-position: center;"
      class="text-blue-900 min-h-screen flex items-center justify-center">

  <div class="w-[90%] md:w-[60%] max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

    <!-- Form Reset Password -->
    <div class="flex items-center justify-center p-8">
      <div class="w-full max-w-sm space-y-5">
        <div>
          <h2 class="text-2xl font-bold">Lupa Password</h2>
          <p class="text-xs text-blue-600">
            Ingat password?
            <a href="/login" class="text-blue-500 font-bold hover:underline">Login</a>
          </p>
        </div>

        <!-- SweetAlert untuk error / success -->
        <?php if ($error): ?>
        <script>
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?= htmlspecialchars($error) ?>',
            confirmButtonColor: '#dc2626'
          });
        </script>
        <?php endif; ?>

        <?php if ($success): ?>
        <script>
          Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '<?= htmlspecialchars($success) ?>',
            confirmButtonColor: '#2563eb'
          });
        </script>
        <?php endif; ?>

        <form action="/forgetPassword" method="POST" class="space-y-4 text-sm">
          <?php if ($step == 1): ?>
            <!-- Step 1: Email -->
            <input type="email" name="email" placeholder="Email" required
                   class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 
                          focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                    class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded-lg 
                           text-white font-medium shadow-md transition text-sm">
              Kirim Kode OTP
            </button>
          <?php else: ?>
            <!-- Step 2: OTP + Password Baru -->
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

            <input type="text" name="otp" placeholder="Kode OTP" required maxlength="8"
                   class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 
                          focus:outline-none focus:ring-2 focus:ring-blue-500">

            <input type="password" name="new_password" placeholder="Password Baru" required
                   class="w-full px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 
                          focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button type="submit"
                    class="w-full py-2 bg-blue-600 hover:bg-blue-700 rounded-lg 
                           text-white font-medium shadow-md transition text-sm">
              Reset Password
            </button>
          <?php endif; ?>
        </form>
      </div>
    </div>

    <!-- Ilustrasi kanan -->
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
