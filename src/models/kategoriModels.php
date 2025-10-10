<?php
require_once __DIR__ . '/../config/database.php';

class Kategori
{
    private $db;
    private $table = "t_kategori_produk";

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllKategori()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY nama_kategori ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKategoriById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_kategori = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addKategori($nama)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nama_kategori, tanggal_dibuat) VALUES (?, NOW())");
        return $stmt->execute([$nama]);
    }

    public function updateKategori($id, $nama)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET nama_kategori = ?, tanggal_diubah = NOW() WHERE id_kategori = ?");
        return $stmt->execute([$nama, $id]);
    }

    public function deleteKategori($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_kategori = ?");
        return $stmt->execute([$id]);
    }
}
