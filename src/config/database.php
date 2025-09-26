<?php
class Database {
    private $host = "localhost";
    private $dbname = "zoegarboegar";
    private $username = "root";
    private $password = "";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Koneksi berhasil"; // Debugging opsional
        } catch (PDOException $e) {
            echo "Koneksi gagal: " . $e->getMessage();
        }
    }

    // Method untuk ambil koneksi (dipakai di Model)
    public function getConnection() {
        return $this->conn;
    }
}
