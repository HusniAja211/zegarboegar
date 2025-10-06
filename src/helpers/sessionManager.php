<?php
class SessionManager
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {

            // Pastikan session disimpan di folder project (bukan temp OS)
            $path = __DIR__ . '/../../storage/session';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            ini_set('session.save_path', $path);

            // Atur cookie agar tetap terbaca di POST request
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '', // biarkan kosong untuk domain saat ini
                'secure' => false, // true kalau pakai HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
        }
    }

    // Simpan data kasir ke session
    public static function login(array $kasir)
    {
        self::start();
        $_SESSION['kasir'] = [
            'id'      => $kasir['id_kasir'],
            'nama'    => $kasir['nama_kasir'],
            'email'   => $kasir['email_kasir'],
            'telepon' => $kasir['nomor_telepon_kasir'],
            'pfp' => $kasir['gambar_kasir'] ?? null,
            'status'  => $kasir['status']
        ];
    }

    // Ambil data kasir
    public static function kasir()
    {
        self::start();
        return $_SESSION['kasir'] ?? null;
    }

    // Cek apakah sudah login
    public static function isLoggedIn(): bool
    {
        self::start();
        return isset($_SESSION['kasir']);
    }

    // Logout
    public static function logout()
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }
}
