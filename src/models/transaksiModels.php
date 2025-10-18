<?php
require_once __DIR__ . '/../config/database.php';

class Transaksi
{
    private $db;
    private $table = "t_transaksi";

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllTransaksi()
    {
        $stmt = $this->db->query("SELECT id_transaksi, kode_transaksi, id_kasir, id_member, total, bayar, kembalian, tanggal_dibuat FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertTransaksi($id_kasir, $tanggal, $total)
    {
        $stmt = $this->db->prepare("
            INSERT INTO t_transaksi (id_kasir, tanggal_transaksi, total_harga)
            VALUES (:id_kasir, :tanggal, :total)
        ");
        $stmt->execute([
            ':id_kasir' => $id_kasir,
            ':tanggal' => $tanggal,
            ':total' => $total
        ]);

        return $this->db->lastInsertId(); // Penting untuk ambil id transaksi terakhir
    }

    public function getTransaksiById($idTransaksi)
    {
        $stmt = $this->db->prepare("
            SELECT 
                t.id_transaksi,
                t.kode_transaksi,
                t.total,
                t.bayar,
                t.kembalian,
                t.tanggal_dibuat,
                t.id_member,
                m.nama_member,
                m.no_hp AS no_hp_member
            FROM t_transaksi t
            LEFT JOIN t_member m ON t.id_member = m.id_member
            WHERE t.id_transaksi = :id
        ");
        $stmt->execute([':id' => $idTransaksi]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTransaksiByKode($kode_transaksi)
    {
        $stmt = $this->db->prepare("
            SELECT * 
            FROM t_transaksi 
            WHERE kode_transaksi = :kode_transaksi
            LIMIT 1
        ");
        $stmt->execute([':kode_transaksi' => $kode_transaksi]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



}
