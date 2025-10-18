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

    public function getDetailByTransaksi($idTransaksi)
    {
        $stmt = $this->db->prepare("
            SELECT 
                d.id_detail,
                d.id_transaksi,
                d.id_produk,
                d.qty,
                d.harga,
                p.nama_produk
            FROM t_detail_transaksi d
            JOIN t_produk p ON d.id_produk = p.id_produk
            WHERE d.id_transaksi = :id
        ");
        $stmt->execute([':id' => $idTransaksi]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
