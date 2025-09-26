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

}
