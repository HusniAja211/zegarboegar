<?php
require_once __DIR__ . '/../models/kasirModels.php';
require_once __DIR__ . '/../helpers/SessionManager.php';
require_once __DIR__ . '/../helpers/mailer.php';


class KasirController
{
    //Registrasi
    public function register()
    {
        // Kalau sudah login, lempar ke dashboard
        require_once __DIR__ . '/../helpers/SessionManager.php';

        if (SessionManager::isLoggedIn()) {
            header("Location: /dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama     = trim($_POST['nama_kasir']);
            $email    = trim($_POST['email_kasir']);
            $telepon  = trim($_POST['nomor_telepon_kasir']);
            $password = trim($_POST['password_kasir']);
            $confirm  = trim($_POST['confirm_password']);

            $kasir = new Kasir();

            // cek email sudah ada
            if ($kasir->findByEmail($email)) {
                header("Location: /register?error=email");
                exit;
            }

            // validasi konfirmasi password
            if ($password !== $confirm) {
                header("Location: /register?error=password_mismatch");
                exit;
            }

            // validasi checkbox terms
            if (!isset($_POST['terms'])) {
                header("Location: /register?error=terms");
                exit;
            }

            // simpan data baru
            if ($kasir->register($nama, $email, $telepon, $password)) {
                header("Location: /login?success=1");
                exit;
            } else {
                header("Location: /register?error=failed");
                exit;
            }
        } else {
            require __DIR__ . '/../views/register.php';
        }
    }

    //Login
    public function login()
    {
        // Kalau sudah login, lempar ke dashboard
        require_once __DIR__ . '/../helpers/SessionManager.php';

        if (SessionManager::isLoggedIn()) {
            header("Location: /dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email_kasir']);
            $password = trim($_POST['password_kasir']);

            $kasir = new Kasir();
            $dataKasir = $kasir->verifyLogin($email, $password);

            if ($dataKasir) {
                SessionManager::login($dataKasir);
                header("Location: /dashboard");
                exit;
            } else {
                header("Location: /login?error=invalid");
                exit;
            }
        } else {
            require __DIR__ . '/../views/login.php';
        }
    }

    //Logout
    public function logout()
    {
        SessionManager::logout();
        header("Location: /login?success=logout");
        exit;
    }

    public function forgetPassword()
    {
        $kasir = new Kasir();

        $error = null;
        $success = null;
        $step = 1;
        $email = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // STEP 1: User input email → kirim OTP
            if (isset($_POST['email']) && !isset($_POST['otp'])) {
                $email = trim($_POST['email']);
                $user = $kasir->findByEmail($email);

                if (!$user) {
                    $error = "Email tidak terdaftar.";
                } else {
                    // Generate OTP random 8 karakter
                    $otp = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);

                    if ($kasir->updateOtp($email, $otp)) {
                        if (Mailer::sendOtp($email, $otp)) {
                            $success = "Kode OTP telah dikirim ke email Anda.";
                            $step = 2;
                        } else {
                            $error = "Gagal mengirim email OTP.";
                        }
                    } else {
                        $error = "Gagal menyimpan OTP.";
                    }
                }
            }

            // STEP 2: User input OTP + password baru
            if (isset($_POST['otp']) && isset($_POST['new_password'])) {
                $email = trim($_POST['email']);
                $otp   = trim($_POST['otp']);
                $newPw = trim($_POST['new_password']);

                $user = $kasir->verifyOtp($email, $otp);

                if (!$user) {
                    $error = "OTP salah atau tidak valid.";
                    $step = 2; // tetap di step 2
                } else {
                    if ($kasir->updatePassword($email, $newPw)) {
                        $success = "Password berhasil diubah. Silakan login.";
                        $step = 1; // kembali ke step 1
                    } else {
                        $error = "Gagal mengubah password.";
                        $step = 2;
                    }
                }
            }
        }

        // kirim variabel ke view
        require __DIR__ . '/../views/forgetPassword.php';
    }

    public function listKasir()
    {
        $kasir = new Kasir();
        $allKasir = $kasir->getAllKasir();

        // pagination
        $perPage = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalData = count($allKasir);
        $totalPages = ceil($totalData / $perPage);
        $start = ($page - 1) * $perPage;
        $dataPage = array_slice($allKasir, $start, $perPage);

        require __DIR__ . '/../views/kasir.php';
    }

}
