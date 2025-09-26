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

   
}
