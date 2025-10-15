<?php
require_once __DIR__ . '/../config/database.php';

class DetailTransaksi
{
    private $db;
    private $table = "t_detail_transaksi";

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Simpan satu detail transaksi
    public function insertDetail($id_transaksi, $id_produk, $qty, $harga)
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table}
            (id_transaksi, id_produk, qty, harga_satuan, subtotal)
            VALUES (:id_transaksi, :id_produk, :qty, :harga_satuan, :subtotal)
        ");

        $subtotal = $qty * $harga;

        return $stmt->execute([
            ':id_transaksi' => $id_transaksi,
            ':id_produk' => $id_produk,
            ':qty' => $qty,
            ':harga_satuan' => $harga,
            ':subtotal' => $subtotal
        ]);
    }
}
