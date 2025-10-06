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
}
