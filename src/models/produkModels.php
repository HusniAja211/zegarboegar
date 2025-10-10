<?php
require_once __DIR__ . '/../config/database.php';

class Produk
{
    private $db;
    private $table = "t_produk";

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllProduk()
    {
        $stmt = $this->db->query("SELECT id_produk, kode_produk, barcode, nama_produk, 
                                         id_kategori, harga_beli, modal, harga_jual 
                                         stok, kadaluarsa, deskripsi, tanggal_dibuat  FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProdukById($id)
    {
        $stmt = $this->db->prepare("SELECT id_produk, kode_produk, barcode, nama_produk, 
                                         id_kategori, modal, harga_jual, keuntungan 
                                         stok, kadaluarsa, deskripsi, tanggal_dibuat FROM {$this->table} WHERE id_produk = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllKategori()
    {
        $stmt = $this->db->query("SELECT nama_kategori FROM t_kategori_produk ORDER BY nama_kategori ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getProdukByKategori($namaKategori)
    {
        $stmt = $this->db->prepare("
        SELECT 
            p.id_produk,
            p.kadaluarsa,
            p.kode_produk AS kode,
            p.barcode,
            p.nama_produk AS nama,
            p.harga_jual AS harga,
            p.stok,
            IFNULL(p.gambar, '/images/default_pfp/defaultPFP.jpg') AS gambar,
            k.nama_kategori
        FROM t_produk p
        INNER JOIN t_kategori_produk k ON p.id_kategori = k.id_kategori
        WHERE k.nama_kategori = :kategori
        ORDER BY p.nama_produk ASC
    ");
        $stmt->execute([':kategori' => $namaKategori]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProduk($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (kode_produk, nama_produk, id_kategori, modal, harga_jual, keuntungan, stok, kadaluarsa, deskripsi, gambar, tanggal_dibuat)
            VALUES 
            (:kode_produk, :nama_produk, :id_kategori, :modal, :harga_jual, :keuntungan, :stok, :kadaluarsa, :deskripsi, :gambar, NOW())
        ");
        //Itu nanti di atas tambahin field barcode

        return $stmt->execute([
            ':kode_produk'  => $data['kode_produk'],
            // ':barcode'      => $data['barcode'],
            ':nama_produk'  => $data['nama_produk'],
            ':id_kategori'  => $data['id_kategori'],
            ':modal'        => $data['modal'],
            ':harga_jual'   => $data['harga_jual'],
            ':keuntungan'   => $data['keuntungan'],
            ':stok'         => $data['stok'],
            ':kadaluarsa'   => $data['kadaluarsa'],
            ':deskripsi'    => $data['deskripsi'],
            ':gambar'       => $data['gambar']
        ]);
    }

    public function updateProduk($id, $data)
    {
        // Siapkan bagian SET query hanya untuk field yang dikirim
        $fields = [];
        $params = [':id_produk' => $id];

        foreach ($data as $key => $value) {
            // Lewati jika null atau kosong string, kecuali angka 0
            if ($value === null || $value === '') continue;

            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        // Jangan lanjut jika tidak ada field yang diupdate
        if (empty($fields)) return false;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id_produk = :id_produk";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteProduk($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_produk = :id_produk";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id_produk' => $id]);
    }


    public function isKodeExists($kode)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM t_produk WHERE kode_produk = :kode");
        $stmt->execute([':kode' => $kode]);
        return $stmt->fetchColumn() > 0;
    }

    public function isNamaExists($nama)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM t_produk WHERE nama_produk = :nama");
        $stmt->execute([':nama' => $nama]);
        return $stmt->fetchColumn() > 0;
    }

}
