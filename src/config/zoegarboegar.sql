-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 12, 2025 at 12:49 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zoegarboegar`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_detail_transaksi`
--

CREATE TABLE `t_detail_transaksi` (
  `id_detail` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `fid_kode_transaksi` varchar(50) NOT NULL,
  `id_produk` int NOT NULL,
  `qty` int NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_detail_transaksi`
--

INSERT INTO `t_detail_transaksi` (`id_detail`, `id_transaksi`, `fid_kode_transaksi`, `id_produk`, `qty`, `harga`, `subtotal`, `tanggal_dibuat`, `tanggal_diubah`) VALUES
(13, 20, 'TRX25101707273298', 7, 1, 15000.00, 15000.00, '2025-10-17 07:27:32', '2025-10-17 07:27:32'),
(14, 21, 'TRX25101805022269', 7, 1, 15000.00, 15000.00, '2025-10-18 05:02:22', '2025-10-18 05:02:22'),
(15, 22, 'TRX25101805092323', 6, 1, 7000.00, 7000.00, '2025-10-18 05:09:23', '2025-10-18 05:09:23'),
(16, 22, 'TRX25101805092323', 7, 1, 15000.00, 15000.00, '2025-10-18 05:09:23', '2025-10-18 05:09:23'),
(17, 23, 'TRX25101805224247', 7, 1, 15000.00, 15000.00, '2025-10-18 05:22:42', '2025-10-18 05:22:42'),
(18, 24, 'TRX25101902072981', 7, 1, 15000.00, 15000.00, '2025-10-19 02:07:29', '2025-10-19 02:07:29'),
(19, 25, 'TRX25102107542513', 9, 1, 6000.00, 6000.00, '2025-10-21 07:54:25', '2025-10-21 07:54:25'),
(20, 25, 'TRX25102107542513', 6, 1, 7000.00, 7000.00, '2025-10-21 07:54:26', '2025-10-21 07:54:26'),
(21, 26, 'TRX25102203465570', 9, 1, 6000.00, 6000.00, '2025-10-22 03:46:55', '2025-10-22 03:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `t_kasir`
--

CREATE TABLE `t_kasir` (
  `id_kasir` int NOT NULL,
  `nama_kasir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email_kasir` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nomor_telepon_kasir` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password_kasir` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` enum('Aktif','Tidak Aktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Aktif',
  `gambar_kasir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '/images/default_pfp/defaultPFP.jpg',
  `otp_akun` varchar(8) NOT NULL DEFAULT 'null',
  `tanggal_diubuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `terakhir_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_kasir`
--

INSERT INTO `t_kasir` (`id_kasir`, `nama_kasir`, `email_kasir`, `nomor_telepon_kasir`, `password_kasir`, `status`, `gambar_kasir`, `otp_akun`, `tanggal_diubuat`, `tanggal_diubah`, `terakhir_login`) VALUES
(1, 'HusniNice', 'husnimubarak5177@gmail.com', '085781197648', '$2y$10$oOmcJILIqp4EsXimnAg6OOkRcyb.pzeTFR5GW4D5dOejhHOghPtA.', 'Aktif', '/images/pfp/1_HusniNice1.png', 'null', '2025-09-25 14:07:44', '2025-10-14 15:09:01', '2025-10-22 13:48:56'),
(11, 'Dina Marlina', 'dina.marlina@example.com', '082345678901', 'hashed_password_2', 'Tidak Aktif', '/images/default_pfp/defaultPFP.jpg', 'null', '2025-10-19 01:40:13', '2025-10-19 01:40:13', NULL),
(12, 'Rudi Hartono1', 'rudi.hartono@example.com', '083456789012', 'hashed_password_3', 'Tidak Aktif', '/images/default_pfp/defaultPFP.jpg', 'null', '2025-10-19 01:40:13', '2025-10-19 02:01:25', NULL),
(15, 'Satria', 'satriafarel40@gmail.com', '085781197648', '$2y$10$px4VdMEgUc4DPq1i.g3SheAGs98DcxLG8eFLogYOhML/VGW3jxlg.', 'Aktif', '/images/default_pfp/defaultPFP.jpg', 'null', '2025-10-21 07:38:46', '2025-10-21 07:38:46', '2025-10-21 14:38:59');

-- --------------------------------------------------------

--
-- Table structure for table `t_kategori_produk`
--

CREATE TABLE `t_kategori_produk` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_kategori_produk`
--

INSERT INTO `t_kategori_produk` (`id_kategori`, `nama_kategori`, `tanggal_dibuat`, `tanggal_diubah`) VALUES
(1, 'Minuman', '2025-10-08 06:58:10', '2025-10-22 02:16:06'),
(8, 'Makanan', '2025-10-22 02:33:46', '2025-10-22 02:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `t_member`
--

CREATE TABLE `t_member` (
  `id_member` int NOT NULL,
  `nama_member` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `poin` int DEFAULT '0',
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_member`
--

INSERT INTO `t_member` (`id_member`, `nama_member`, `no_hp`, `email`, `poin`, `tanggal_dibuat`, `tanggal_diubah`) VALUES
(1, 'Husni1', '6285781197648', 'husnimubarak5177@gmail.com', 680, '2025-10-13 02:12:02', '2025-10-22 03:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `t_produk`
--

CREATE TABLE `t_produk` (
  `id_produk` int NOT NULL,
  `kode_produk` varchar(50) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `id_kategori` int DEFAULT NULL,
  `modal` decimal(12,2) DEFAULT '0.00',
  `harga_jual` decimal(12,2) NOT NULL,
  `keuntungan` decimal(12,2) NOT NULL,
  `stok` int DEFAULT '0',
  `kadaluarsa` date DEFAULT NULL,
  `deskripsi` text,
  `gambar` varchar(100) NOT NULL,
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_produk`
--

INSERT INTO `t_produk` (`id_produk`, `kode_produk`, `barcode`, `nama_produk`, `id_kategori`, `modal`, `harga_jual`, `keuntungan`, `stok`, `kadaluarsa`, `deskripsi`, `gambar`, `tanggal_dibuat`, `tanggal_diubah`) VALUES
(6, 'MNM001', NULL, 'Es Sugus', 1, 5000.00, 7000.00, 2000.00, 0, '2025-10-16', 'Segar', '/images/produk/prod_68eef311ef869_logo es sugus.jpg', '2025-10-15 01:04:17', '2025-10-16 07:01:23'),
(7, 'MKN001', NULL, 'Ayam Saus Tiram', NULL, 10000.00, 15000.00, 5000.00, 0, '2025-10-17', 'Asam Manis', '/images/produk/prod_68f042238abda_Ayam Saus Tiram.jpeg', '2025-10-16 00:53:55', '2025-10-19 02:07:29'),
(8, 'MKN002', 'ZB-MKN002-8', 'Ayam Serundeng', NULL, 10000.00, 15000.00, 5000.00, 10, '2025-10-21', 'Asin', '/images/produk/prod_68f62b0db18ff_Ayam Serundeng.jpeg', '2025-10-20 12:29:01', '2025-10-20 12:29:02'),
(9, 'MNM002', 'ZB-MNM002-9', 'Minuman', 1, 4000.00, 6000.00, 2000.00, 18, '2025-10-21', 'adbjbd', '/images/produk/prod_68f73b40b3149_Ayam Saus Tiram.jpeg', '2025-10-21 07:50:24', '2025-10-22 03:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `t_transaksi`
--

CREATE TABLE `t_transaksi` (
  `id_transaksi` int NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `id_kasir` int NOT NULL,
  `id_member` int DEFAULT NULL,
  `total` decimal(12,2) NOT NULL,
  `bayar` decimal(12,2) NOT NULL,
  `kembalian` decimal(12,2) NOT NULL,
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `t_transaksi`
--

INSERT INTO `t_transaksi` (`id_transaksi`, `kode_transaksi`, `id_kasir`, `id_member`, `total`, `bayar`, `kembalian`, `tanggal_dibuat`, `tanggal_diubah`) VALUES
(20, 'TRX25101707273298', 1, 1, 15000.00, 11000.00, 0.00, '2025-10-17 07:27:32', '2025-10-17 07:27:32'),
(21, 'TRX25101805022269', 1, 1, 15000.00, 20000.00, 5000.00, '2025-10-18 05:02:22', '2025-10-18 05:02:22'),
(22, 'TRX25101805092323', 1, 1, 22000.00, 30000.00, 8000.00, '2025-10-18 05:09:23', '2025-10-18 05:09:23'),
(23, 'TRX25101805224247', 1, 1, 15000.00, 20000.00, 5000.00, '2025-10-18 05:22:42', '2025-10-18 05:22:42'),
(24, 'TRX25101902072981', 1, 1, 15000.00, 20000.00, 5000.00, '2025-10-19 02:07:29', '2025-10-19 02:07:29'),
(25, 'TRX25102107542513', 1, 1, 13000.00, 15000.00, 2000.00, '2025-10-21 07:54:25', '2025-10-21 07:54:25'),
(26, 'TRX25102203465570', 1, 1, 6000.00, 20000.00, 14000.00, '2025-09-30 03:46:55', '2025-10-22 07:05:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_detail_transaksi`
--
ALTER TABLE `t_detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_transaksi` (`id_transaksi`),
  ADD KEY `fk_detail_produk` (`id_produk`),
  ADD KEY `fid_kode_transaksi` (`fid_kode_transaksi`);

--
-- Indexes for table `t_kasir`
--
ALTER TABLE `t_kasir`
  ADD PRIMARY KEY (`id_kasir`);

--
-- Indexes for table `t_kategori_produk`
--
ALTER TABLE `t_kategori_produk`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `t_member`
--
ALTER TABLE `t_member`
  ADD PRIMARY KEY (`id_member`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `t_produk`
--
ALTER TABLE `t_produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `kode_produk` (`kode_produk`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `fk_produk_kategori` (`id_kategori`);

--
-- Indexes for table `t_transaksi`
--
ALTER TABLE `t_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `fk_transaksi_kasir` (`id_kasir`),
  ADD KEY `fk_transaksi_member` (`id_member`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_detail_transaksi`
--
ALTER TABLE `t_detail_transaksi`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `t_kasir`
--
ALTER TABLE `t_kasir`
  MODIFY `id_kasir` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `t_kategori_produk`
--
ALTER TABLE `t_kategori_produk`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `t_member`
--
ALTER TABLE `t_member`
  MODIFY `id_member` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_produk`
--
ALTER TABLE `t_produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `t_transaksi`
--
ALTER TABLE `t_transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_detail_transaksi`
--
ALTER TABLE `t_detail_transaksi`
  ADD CONSTRAINT `fk_detail_produk` FOREIGN KEY (`id_produk`) REFERENCES `t_produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `t_transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_produk`
--
ALTER TABLE `t_produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `t_kategori_produk` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `t_transaksi`
--
ALTER TABLE `t_transaksi`
  ADD CONSTRAINT `fk_transaksi_kasir` FOREIGN KEY (`id_kasir`) REFERENCES `t_kasir` (`id_kasir`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_member` FOREIGN KEY (`id_member`) REFERENCES `t_member` (`id_member`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
