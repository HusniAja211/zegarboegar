<?php
class SessionManager
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
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
        session_destroy();
        $_SESSION = [];
    }
}
