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

    public function getKasirById($id)
    {
        $stmt = $this->db->prepare("SELECT id_kasir, nama_kasir, email_kasir, nomor_telepon_kasir, status, gambar_kasir FROM {$this->table} WHERE id_kasir = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteKasirById($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_kasir = ?");
        return $stmt->execute([$id]);
    }

    public function updateKasirById($id, $nama, $email, $telepon, $status, $gambar, $password = null)
    {
        if ($password) {
            $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET nama_kasir = :nama,
                email_kasir = :email,
                nomor_telepon_kasir = :telepon,
                status = :status,
                gambar_kasir = :gambar,
                password_kasir = :password,
                tanggal_diubah = NOW()
            WHERE id_kasir = :id
        ");
            return $stmt->execute([
                ':nama' => $nama,
                ':email' => $email,
                ':telepon' => $telepon,
                ':status' => $status,
                ':gambar' => $gambar,
                ':password' => $password,
                ':id' => $id
            ]);
        } else {
            $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET nama_kasir = :nama,
                email_kasir = :email,
                nomor_telepon_kasir = :telepon,
                status = :status,
                gambar_kasir = :gambar,
                tanggal_diubah = NOW()
            WHERE id_kasir = :id
        ");
            return $stmt->execute([
                ':nama' => $nama,
                ':email' => $email,
                ':telepon' => $telepon,
                ':status' => $status,
                ':gambar' => $gambar,
                ':id' => $id
            ]);
        }
    }

    public function updatePartial($id, $data)
    {
        if (empty($data)) return false;

        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        // Tambahkan waktu update
        $fields[] = "tanggal_diubah = NOW()";

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id_kasir = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateLastActivity($id)
    {
        $stmt = $this->db->prepare("UPDATE t_kasir SET terakhir_login = NOW() WHERE id_kasir = ?");
        $stmt->execute([$id]);
    }

    public function deactivateInactiveKasir($days = 7)
    {
        $stmt = $this->db->prepare("
        UPDATE t_kasir 
        SET status = 'Tidak Aktif' 
        WHERE terakhir_login IS NOT NULL 
        AND TIMESTAMPDIFF(DAY, terakhir_login, NOW()) >= ?
        AND status = 'Aktif'
    ");
        $stmt->execute([$days]);
    }
}
