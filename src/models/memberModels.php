<?php
require_once __DIR__ . '/../config/database.php';

class Member
{
    private $db;
    private $table = "t_member";

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getMemberById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_member = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMemberByTelp($no_hp)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE no_hp = :no_hp");
        $stmt->execute([':no_hp' => $no_hp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Registrasi member baru
    public function register($nama, $telepon, $email, $poin)
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
                (nama_member, no_hp, email, poin) 
            VALUES 
                (:nama, :telepon, :email, :poin)
        ");
        return $stmt->execute([
            ':nama'     => $nama,
            ':telepon'  => $telepon,
            ':email'    => $email,
            ':poin'   => $poin
        ]);
    }

    // Cari member berdasarkan email
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update informasi member
    public function updateMember($id, $nama, $telepon, $email, $poin)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET nama_member = :nama, email = :email, no_hp = :telepon, poin = :poin 
            WHERE id_member = :id
        ");
        return $stmt->execute([
            ':id'      => $id,
            ':nama'    => $nama,
            ':telepon' => $telepon,
            ':email'   => $email,
            ':poin'  => $poin
        ]);
    }

    // Hapus member berdasarkan ID
    public function deleteMember($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_member = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Ambil semua member
    public function getAllMembers()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY nama_member ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }           

}