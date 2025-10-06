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

    public function detailKasir($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $kasirModel = new Kasir();
        $detailKasir = $kasirModel->getKasirById($id);

        if (!$detailKasir) {
            // kalau ID tidak ada, redirect atau tampilkan 404
            header("Location: /kasir?error=notfound");
            exit;
        }

        require __DIR__ . '/../views/detailKasir.php';
    }

    public function deleteKasir($id)
    {
        SessionManager::start();

        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $kasirModel = new Kasir();
        $kasir = $kasirModel->getKasirById($id);

        if (!$kasir) {
            header("Location: /kasir?error=notfound");
            exit;
        }

        // 🔹 Cek status
        if (isset($kasir['status']) && $kasir['status'] === 'Aktif') {
            // Tidak boleh hapus kasir aktif
            header("Location: /kasir?error=active");
            exit;
        }

        // 🔹 Hapus hanya jika "Tidak Aktif"
        if ($kasirModel->deleteKasirById($id)) {
            header("Location: /kasir?success=deleted");
            exit;
        } else {
            header("Location: /kasir?error=failed");
            exit;
        }
    }

    public function updateKasir($id)
    {
        SessionManager::start();
        if (!SessionManager::isLoggedIn()) {
            header("Location: /login?error=unauthorized");
            exit;
        }

        $kasirModel = new Kasir();
        $kasir = $kasirModel->getKasirById($id);

        if (!$kasir) {
            header("Location: /kasir?error=notfound");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = [];

            // Hanya update jika ada input
            if (!empty($_POST['nama_kasir'])) {
                $updateData['nama_kasir'] = trim($_POST['nama_kasir']);
            }
            if (!empty($_POST['email_kasir'])) {
                $updateData['email_kasir'] = trim($_POST['email_kasir']);
            }
            if (!empty($_POST['nomor_telepon_kasir'])) {
                $updateData['nomor_telepon_kasir'] = trim($_POST['nomor_telepon_kasir']);
            }
            if (!empty($_POST['status'])) {
                $updateData['status'] = trim($_POST['status']);
            }

            // 🔐 Password baru (opsional)
            $passwordBaru = trim($_POST['password_baru'] ?? '');
            $konfirmasiPassword = trim($_POST['konfirmasi_password'] ?? '');
            if (!empty($passwordBaru)) {
                if ($passwordBaru !== $konfirmasiPassword) {
                    header("Location: /kasir/detail/$id?error=password_mismatch");
                    exit;
                }
                $updateData['password_kasir'] = password_hash($passwordBaru, PASSWORD_BCRYPT);
            }

            // 🖼️ Upload foto baru (opsional)
            if (isset($_FILES['gambar_kasir']) && $_FILES['gambar_kasir']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['gambar_kasir']['tmp_name'];
                $fileType = $_FILES['gambar_kasir']['type'];

                // Tentukan ekstensi file
                $ext = pathinfo($_FILES['gambar_kasir']['name'], PATHINFO_EXTENSION);
                $ext = strtolower($ext);

                // Validasi tipe file
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array($ext, $allowed)) {
                    header("Location: /kasir/detail/$id?error=invalid_filetype");
                    exit;
                }

                // Folder tujuan
                $targetDir = __DIR__ . '/../../public/images/pfp/';
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                // 🔹 Buat nama file unik: id_namakasir.ekstensi
                $namaKasirBersih = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $kasir['nama_kasir']));
                $fileName = "{$id}_{$namaKasirBersih}.{$ext}";
                $targetPath = $targetDir . $fileName;

                // Hapus file lama (jika bukan default)
                if (!empty($kasir['gambar_kasir']) && strpos($kasir['gambar_kasir'], 'default_pfp') === false) {
                    $oldFile = __DIR__ . '/../../public' . $kasir['gambar_kasir'];
                    if (file_exists($oldFile)) unlink($oldFile);
                }

                // Simpan file baru
                if (move_uploaded_file($fileTmp, $targetPath)) {
                    // Simpan hanya relative path agar lebih fleksibel
                    $updateData['gambar_kasir'] = '/images/pfp/' . $fileName;
                } else {
                    header("Location: /kasir/detail/$id?error=upload_failed");
                    exit;
                }
            }
            // ✅ Jalankan update hanya jika ada data diubah
            if (!empty($updateData)) {
                $kasirModel->updatePartial($id, $updateData);
                header("Location: /kasir/detail/$id?success=updated");
                exit;
            } else {
                header("Location: /kasir/detail/$id?info=no_changes");
                exit;
            }
        }
    }
}
