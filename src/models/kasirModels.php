<?php
require_once __DIR__ . '/../config/database.php';

class Kasir
{
    private $db;
    private $table = "t_kasir";

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Registrasi kasir baru
    public function register($nama, $email, $telepon, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
                (nama_kasir, email_kasir, nomor_telepon_kasir, password_kasir) 
            VALUES 
                (:nama, :email, :telepon, :password)
        ");
        return $stmt->execute([
            ':nama'     => $nama,
            ':email'    => $email,
            ':telepon'  => $telepon,
            ':password' => $hashedPassword
        ]);
    }

    // Cari kasir berdasarkan email
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email_kasir = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verifikasi login
    public function verifyLogin($email, $password)
    {
        $kasir = $this->findByEmail($email);
        if ($kasir && password_verify($password, $kasir['password_kasir'])) {
            return $kasir;
        }
        return false;
    }

    // Update OTP berdasarkan email
    public function updateOtp($email, $otp)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET otp_akun = :otp, tanggal_diubah = NOW() 
            WHERE email_kasir = :email
        ");
        return $stmt->execute([
            ':otp'   => $otp,
            ':email' => $email
        ]);
    }

    // Cek OTP valid atau tidak
    public function verifyOtp($email, $otp)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE email_kasir = :email AND otp_akun = :otp
        ");
        $stmt->execute([
            ':email' => $email,
            ':otp'   => $otp
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update password baru + reset OTP
    public function updatePassword($email, $password)
    {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET password_kasir = :password, otp_akun = 'null', tanggal_diubah = NOW() 
            WHERE email_kasir = :email
        ");
        return $stmt->execute([
            ':password' => $hashed,
            ':email'    => $email
        ]);
    }

    public function getAllKasir()
    {
        $stmt = $this->db->query("SELECT id_kasir, nama_kasir, email_kasir, nomor_telepon_kasir, status FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
}
