-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Mar 2024 pada 04.40
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecomm`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_sepatu`
--

CREATE TABLE `data_sepatu` (
  `id_data_sepatu` int(11) NOT NULL,
  `id_sepatu` int(11) NOT NULL COMMENT 'Relasi dari table "sepatu"',
  `ukuran` int(11) NOT NULL,
  `warna` varchar(50) NOT NULL,
  `stok` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar_sepatu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Detail dari sepatu';

--
-- Dumping data untuk tabel `data_sepatu`
--

INSERT INTO `data_sepatu` (`id_data_sepatu`, `id_sepatu`, `ukuran`, `warna`, `stok`, `harga`, `gambar_sepatu`) VALUES
(4, 1, 36, 'Putih', 47, 1099000, '1193029412_chuck70-white.png'),
(5, 1, 38, 'Putih', 30, 1099000, '1193029412_chuck70-white.png'),
(14, 1, 38, 'Hitam', 21, 1099000, '1612598583_sepatu.png'),
(15, 1, 39, 'Hitam', 32, 1099000, '346599114_sepatu.jpg'),
(16, 1, 39, 'Putih', 26, 1099000, '939677160_chuck70-white.png'),
(17, 2, 36, 'Hitam', 76, 1499000, '601938933_chuck70plus-black.png'),
(18, 2, 36, 'Putih', 49, 1499000, '1657183648_chuck70plus-white.png'),
(19, 3, 36, 'Hitam', 49, 999000, '1418951504_chuck70mini-black.png'),
(20, 1, 37, 'Hitam', 19, 1099000, '1568606837_chuck70-black.jpg'),
(21, 1, 37, 'Putih', 20, 1099000, '765364579_chuck70-white.png'),
(22, 3, 36, 'Putih', 1, 999999, '1530246991_chuck70mini-white.png'),
(23, 10, 38, 'Hitam', 22, 1499999, '272397752_cons-black.jpg'),
(24, 10, 37, 'Hitam', 17, 1499000, '243817217_cons-black.jpg'),
(26, 1, 36, 'Hitam', 20, 1099000, '1122805581_chuck70-black.jpg'),
(35, 1, 40, 'Hitam', 1, 1099000, '1122805581_chuck70-black.jpg'),
(36, 1, 41, 'Hitam', 1, 1099000, '1122805581_chuck70-black.jpg'),
(59, 14, 39, 'Hitam', 1, 1199000, 'starplayer76-default.jpg'),
(60, 14, 39, 'Merah', 1, 1199000, '68714-starplayer76-Merah.jpg'),
(61, 14, 38, 'Hitam', 1, 1199000, 'starplayer76-default.jpg'),
(62, 14, 42, 'Merah', 0, 1299000, '68714-starplayer76-Merah.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesan` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_data_sepatu` int(11) NOT NULL COMMENT 'Relasi dari table "data_sepatu"',
  `jumlah_beli` int(11) NOT NULL,
  `tgl_pesan` date NOT NULL,
  `tgl_exp` date NOT NULL,
  `status` enum('Belum Diterima','Dikemas','Siap Diambil','Selesai','Batal','Tolak','Kadaluarsa','Penilaian') NOT NULL,
  `alasan` text NOT NULL,
  `total_biaya` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id_pesan`, `kode`, `id_user`, `id_data_sepatu`, `jumlah_beli`, `tgl_pesan`, `tgl_exp`, `status`, `alasan`, `total_biaya`) VALUES
(10, '', 1, 3, 1, '2024-03-18', '2024-03-25', 'Tolak', '', 1099000),
(11, '', 1, 3, 1, '2024-03-18', '2024-03-25', 'Penilaian', '', 1099000),
(14, '', 1, 3, 1, '2024-03-18', '2024-03-25', 'Penilaian', '', 1099000),
(15, '', 1, 4, 1, '2024-03-18', '2024-03-25', 'Tolak', '', 1099000),
(16, '', 1, 16, 2, '2024-03-25', '2024-04-01', 'Penilaian', '', 2198000),
(17, '', 1, 19, 1, '2024-03-26', '2024-04-02', 'Penilaian', '', 999000),
(18, '', 1, 16, 1, '2024-03-26', '2024-04-02', 'Penilaian', '', 1099000),
(19, '', 1, 18, 1, '2024-03-27', '2024-03-27', 'Kadaluarsa', 'Anda sudah melewati batas waktu pengambilan ! ! !', 1499000),
(21, 'PSK-1-7', 1, 14, 1, '2024-03-27', '2024-04-03', 'Batal', 'salah\r\n', 1099000),
(22, 'PSK-1-7', 1, 5, 1, '2024-03-27', '2024-04-03', 'Selesai', '', 1099000),
(23, 'PSK-1-8290', 1, 21, 1, '2024-03-27', '2024-04-03', 'Tolak', 'salah', 1099000),
(24, 'PSK-6-1857', 6, 23, 2, '2024-03-28', '2024-04-04', 'Batal', 'Salah jumlah belinya bang ;(', 2999998),
(25, 'PSK-1-4691', 1, 20, 1, '2024-03-28', '2024-04-04', 'Selesai', '', 1099000),
(26, 'PSK-6-7396', 6, 23, 1, '2024-03-28', '2024-04-04', 'Penilaian', '', 1499999),
(27, 'PSK-6-3001', 6, 23, 1, '2024-03-28', '2024-04-04', 'Tolak', 'tolak', 1499999),
(28, 'PSK-6-9563', 6, 16, 1, '2024-03-28', '2024-04-04', 'Penilaian', '', 1099000),
(29, 'PSK-1-7859', 1, 23, 1, '2024-03-29', '2024-04-05', 'Batal', 'Gajadi', 1499999),
(30, 'PSK-1-3805', 1, 23, 1, '2024-03-29', '2024-04-05', 'Tolak', 'tolak\r\n', 1499999),
(31, 'PSK-1-2549', 1, 23, 1, '2024-03-29', '2024-04-05', 'Batal', 'asd', 1499999),
(32, 'PSK-1-6692', 1, 23, 1, '2024-03-29', '2024-04-05', 'Penilaian', '', 1499999),
(33, 'PSK-1-9194', 1, 21, 1, '2024-03-29', '2024-04-05', 'Selesai', '', 1099000),
(34, 'PSK-1-2239', 1, 17, 1, '2024-03-29', '2024-04-05', 'Selesai', '', 1499000),
(35, 'PSK-1-9083', 1, 21, 1, '2024-03-30', '2024-04-06', 'Batal', 'Kas', 1099000),
(36, 'PSK-1-7145', 1, 15, 1, '2024-03-30', '2024-04-06', 'Belum Diterima', '', 1099000),
(37, 'PSK-1-7465', 1, 62, 1, '2024-03-31', '2024-04-07', 'Batal', 'gajadi\r\n', 1199000),
(38, 'PSK-1-8009', 1, 62, 1, '2024-03-31', '2024-04-07', 'Belum Diterima', '', 1299000),
(39, 'PSK-1-7589', 1, 61, 1, '2024-03-31', '2024-04-07', 'Belum Diterima', '', 1199000);

--
-- Trigger `pesanan`
--
DELIMITER $$
CREATE TRIGGER `KURANG_STOK` AFTER INSERT ON `pesanan` FOR EACH ROW BEGIN
-- KURANG STOK
UPDATE data_sepatu SET stok=stok-NEW.jumlah_beli WHERE id_data_sepatu=NEW.id_data_sepatu;

-- GENERATE KODE
-- UPDATE pesanan SET kode="PSK-"+NEW.id_user+"-"+NEW.id_pesan WHERE id_pesan=NEW.id_pesan;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `UP_STOK` AFTER UPDATE ON `pesanan` FOR EACH ROW BEGIN
 IF NEW.status = 'Batal' OR NEW.status = 'Tolak' OR NEW.status = 'Kadaluarsa' THEN
 UPDATE data_sepatu SET stok = stok + NEW.jumlah_beli WHERE data_sepatu.id_data_sepatu = NEW.id_data_sepatu;
 END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sepatu`
--

CREATE TABLE `sepatu` (
  `id_sepatu` int(11) NOT NULL,
  `merk_sepatu` varchar(255) NOT NULL,
  `nama_sepatu` varchar(255) NOT NULL,
  `gambar_sepatu` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sepatu`
--

INSERT INTO `sepatu` (`id_sepatu`, `merk_sepatu`, `nama_sepatu`, `gambar_sepatu`, `deskripsi`) VALUES
(1, 'Converse', 'Chuck 70', '566124467_sepatu.png', ''),
(2, 'Converse', 'Chuck 70 Plus', '1416318907_chuck70plus-black.png', ''),
(3, 'Converse', 'Chuck 70 Mini', '1175618210_chuck70mini-black.png', ''),
(10, 'Converse', 'One Star Pro Sean Pablo', '633352741_cons-black.jpg', 'Sepatu One Star Pro Sean Pablo AS-1 Pro Unisex Sepatu'),
(14, 'Converse', 'Star Player 76', 'starplayer76-default.jpg', 'GAYA KLASIK KONSISTEN Sepatu Star Player 76 tetap mengikuti elemen ikonisnya dan membawakan detail desain yang segar seperti Star Chevron bahan kulit dan warna ala musim semi yang akan meningkatkan penampilan outfit manapun. Fitur Dan Keunggulan Sepatu low-top dengan bahan kanvas di bagian atas Bantalan OrthoLite membantu memberikan kenyamanan optimal Bumper jari kaki tekstur-wajik dan outsole pola batu bata Star Chevron bahan kulit, label lidah, dan pelat logo gaya vintage'),
(15, 'Converse', 'israel mati aja', 'israelmatiaja-default.jpg', 'asodkawodkao');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int(11) NOT NULL,
  `id_pesan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_sepatu` int(11) NOT NULL,
  `ulasan` text NOT NULL,
  `rating` int(1) NOT NULL,
  `tgl_ulasan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ulasan`
--

INSERT INTO `ulasan` (`id_ulasan`, `id_pesan`, `id_user`, `id_sepatu`, `ulasan`, `rating`, `tgl_ulasan`) VALUES
(1, 11, 1, 1, 'amnsd', 5, '2024-03-19'),
(2, 14, 1, 1, 'asda', 3, '2024-03-19'),
(3, 16, 1, 1, 'Sepatunya enak dipakai, wangi', 4, '2024-03-26'),
(4, 0, 1, 3, 'Kecil mungil cabe rawit', 5, '2024-03-26'),
(5, 18, 1, 1, 'fit to me', 5, '2024-03-27'),
(6, 26, 6, 10, 'Fit to me and soft', 5, '2024-03-28'),
(7, 28, 6, 1, 'Kurang baik', 2, '2024-03-28'),
(8, 32, 1, 10, 'Panjang-panjang orang baik', 5, '2024-03-29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `role` enum('Customer','Owner') NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `role`, `username`, `password`) VALUES
(1, 'Customer', 'c', ''),
(2, 'Owner', 'o', ''),
(3, 'Customer', 'local', ''),
(4, 'Customer', 'asd', ''),
(5, 'Customer', 'm', ''),
(6, 'Customer', 'wk', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_sepatu`
--
ALTER TABLE `data_sepatu`
  ADD PRIMARY KEY (`id_data_sepatu`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_data_sepatu` (`id_data_sepatu`);

--
-- Indeks untuk tabel `sepatu`
--
ALTER TABLE `sepatu`
  ADD PRIMARY KEY (`id_sepatu`);

--
-- Indeks untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `data_sepatu`
--
ALTER TABLE `data_sepatu`
  MODIFY `id_data_sepatu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `sepatu`
--
ALTER TABLE `sepatu`
  MODIFY `id_sepatu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
