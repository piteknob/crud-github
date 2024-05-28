-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Bulan Mei 2024 pada 08.40
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
-- Database: `ci4_produk_api`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `auth_user`
--

CREATE TABLE `auth_user` (
  `auth_user_id` int(10) UNSIGNED NOT NULL,
  `auth_user_user_id` int(10) UNSIGNED NOT NULL,
  `auth_user_email` varchar(100) NOT NULL,
  `auth_user_password` varchar(255) NOT NULL,
  `auth_user_token` varchar(255) NOT NULL,
  `auth_user_date_login` datetime DEFAULT NULL,
  `auth_user_date_expired` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `auth_user`
--

INSERT INTO `auth_user` (`auth_user_id`, `auth_user_user_id`, `auth_user_email`, `auth_user_password`, `auth_user_token`, `auth_user_date_login`, `auth_user_date_expired`) VALUES
(13, 5, 'piteknoob@gmail.com', '123456', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwidWlkIjoiNSIsImVtYWlsIjoicGl0ZWtub29iQGdtYWlsLmNvbSJ9.zs3qKndYrlllnu6lhioucFqciM4pVSkUtb8f9sS_PjY', '2024-05-15 10:26:17', '2024-05-15 11:26:17'),
(14, 7, 'pitek@gmail.com', '123456', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwidWlkIjoiNyIsImVtYWlsIjoicGl0ZWtAZ21haWwuY29tIn0.ancvTdXxTuHCg96Vp2OjO-r9YUmC5lgDW7bC63ascm0', '2024-05-15 11:38:50', '2024-05-15 12:38:50'),
(15, 6, 'vino@gmail.com', '123456', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwidWlkIjoiNiIsImVtYWlsIjoidmlub0BnbWFpbC5jb20ifQ.vJsRtp3MyS6RP8G-Svz37Bfo3IymU1Zal1zYO74Fo40', '2024-05-15 11:08:25', '2024-05-15 12:08:25'),
(16, 8, 'nugger@gmail.com', '123456', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwidWlkIjoiOCIsImVtYWlsIjoibnVnZ2VyQGdtYWlsLmNvbSJ9.QR4GqpDzogMwsOUqfVLYz_xeCxLtTDG5RFAEgxkWlKo', '2024-05-15 11:38:38', '2024-05-15 12:38:38'),
(17, 9, 'floyd@gmail.com', '123456', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwidWlkIjoiOSIsImVtYWlsIjoiZmxveWRAZ21haWwuY29tIn0.iGZE2ZalBOqwhm71G4x_599gFa73oj7_iMSsRfy68xQ', '2024-05-28 13:34:46', '2024-05-28 14:34:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `category`
--

CREATE TABLE `category` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_deleted_at`) VALUES
(1, 'Makanan', NULL),
(2, 'Minuman', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_stock`
--

CREATE TABLE `log_stock` (
  `log_stock_id` int(10) UNSIGNED NOT NULL,
  `log_stock_product_id` int(10) UNSIGNED NOT NULL,
  `log_stock_product_name` varchar(50) NOT NULL,
  `log_stock_status` enum('add','reduce','','') NOT NULL,
  `log_stock_value` int(11) NOT NULL,
  `log_stock_category_id` int(10) UNSIGNED NOT NULL,
  `log_stock_category_name` varchar(50) NOT NULL,
  `log_stock_unit_id` int(10) UNSIGNED NOT NULL,
  `log_stock_unit_name` varchar(50) NOT NULL,
  `log_stock_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_stock`
--

INSERT INTO `log_stock` (`log_stock_id`, `log_stock_product_id`, `log_stock_product_name`, `log_stock_status`, `log_stock_value`, `log_stock_category_id`, `log_stock_category_name`, `log_stock_unit_id`, `log_stock_unit_name`, `log_stock_date`) VALUES
(6, 7, 'Permen', 'add', 55, 1, 'Makanan', 4, 'Pcs', '2024-05-02 08:34:33'),
(7, 7, 'Permen', 'reduce', 15, 1, 'Makanan', 4, 'Pcs', '2024-05-02 08:34:38'),
(8, 7, 'Permen', 'reduce', 15, 1, 'Makanan', 4, 'Pcs', '2024-05-02 08:34:39'),
(9, 16, 'Mie Goreng', 'reduce', 20, 1, 'Makanan', 4, 'Pcs', '2024-05-02 09:04:57'),
(10, 7, 'Permen', 'add', 40, 1, 'Makanan', 4, 'Pcs', '2024-05-02 09:04:57'),
(11, 7, 'Permen', 'reduce', 20, 1, 'Makanan', 4, 'Pcs', '2024-05-02 09:06:19'),
(12, 16, 'Mie Goreng', 'add', 40, 1, 'Makanan', 4, 'Pcs', '2024-05-02 09:06:19'),
(13, 7, 'Permen', 'reduce', 20, 1, 'Makanan', 4, 'Pcs', '2024-05-02 09:26:41'),
(14, 16, 'Mie Goreng', 'add', 40, 1, 'Makanan', 4, 'Pcs', '2024-05-02 09:26:41'),
(15, 26, 'Nugget', 'reduce', 100, 1, 'Makanan', 3, 'Kardus', '2024-05-07 10:59:18'),
(16, 21, 'Mie Rebus', 'reduce', 100, 1, 'Makanan', 4, 'Pcs', '2024-05-07 10:59:43'),
(17, 169, 'Sapi', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-16 16:26:15'),
(18, 169, 'Sapi', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-16 16:26:24'),
(19, 169, 'Sapi', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-16 16:28:50'),
(20, 246, 'pessi', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-17 13:23:55'),
(21, 246, 'pessi', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-17 13:24:00'),
(22, 246, 'pessi', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-17 13:25:13'),
(23, 246, 'pessi', 'add', 10, 1, 'Makanan', 1, 'Piring', '2024-05-17 13:26:26'),
(24, 246, 'pessi', 'add', 10, 1, 'Makanan', 1, 'Piring', '2024-05-17 13:26:31'),
(25, 244, 'dodo', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-17 13:40:44'),
(26, 245, 'penaldo', 'add', 20, 1, 'Makanan', 1, 'Piring', '2024-05-17 13:40:44'),
(27, 244, 'dodo', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-17 13:42:44'),
(28, 245, 'penaldo', 'add', 20, 1, 'Makanan', 1, 'Piring', '2024-05-17 13:42:44'),
(29, 246, 'pessi', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-20 14:51:58'),
(30, 246, 'pessi', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-20 14:52:03'),
(31, 244, 'dodo', 'reduce', 10, 1, 'Makanan', 1, 'Piring', '2024-05-20 14:52:07'),
(32, 245, 'penaldo', 'add', 20, 1, 'Makanan', 1, 'Piring', '2024-05-20 14:52:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_category_id` int(10) UNSIGNED NOT NULL,
  `product_category_name` varchar(50) NOT NULL,
  `product_created_at` datetime DEFAULT NULL,
  `product_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_category_id`, `product_category_name`, `product_created_at`, `product_updated_at`) VALUES
(244, 'dodo', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(245, 'penaldo', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(246, 'pessi', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(250, 'Soto', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(251, 'Sate', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(252, 'Ayam', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(253, 'Bebek', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(254, 'Ayam', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(255, 'Bebek', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(266, 'pitek', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(267, 'mentok', 1, 'Makanan', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(268, 'pitek', 2, 'Minuman', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(269, 'mentok', 2, 'Minuman', '2024-05-27 09:27:01', '2024-05-27 11:17:01'),
(270, 'es jeruk', 2, 'Minuman', NULL, NULL),
(271, 'es teh', 2, 'Minuman', NULL, NULL),
(272, 'es kelapa', 2, 'Minuman', NULL, NULL),
(273, 'es air', 2, 'Minuman', NULL, NULL),
(274, 'Telur Rebus', 1, 'Makanan', NULL, NULL),
(275, 'Telur Goreng', 1, 'Makanan', NULL, NULL),
(276, 'Telur Ceplok', 1, 'Makanan', NULL, NULL),
(277, 'Telur Dadar', 1, 'Makanan', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_stock`
--

CREATE TABLE `product_stock` (
  `product_stock_id` int(10) UNSIGNED NOT NULL,
  `product_stock_product_id` int(10) UNSIGNED NOT NULL,
  `product_stock_product_name` varchar(50) NOT NULL,
  `product_stock_unit_id` int(10) UNSIGNED NOT NULL,
  `product_stock_unit_name` varchar(50) NOT NULL,
  `product_stock_value` int(11) NOT NULL,
  `product_stock_price_buy` int(10) UNSIGNED NOT NULL,
  `product_stock_price_sell` int(10) UNSIGNED NOT NULL,
  `product_stock_in` int(10) UNSIGNED NOT NULL,
  `product_stock_out` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_stock`
--

INSERT INTO `product_stock` (`product_stock_id`, `product_stock_product_id`, `product_stock_product_name`, `product_stock_unit_id`, `product_stock_unit_name`, `product_stock_value`, `product_stock_price_buy`, `product_stock_price_sell`, `product_stock_in`, `product_stock_out`) VALUES
(237, 244, 'dodo', 1, 'Piring', 70, 100, 21313, 0, 30),
(238, 245, 'penaldo', 1, 'Piring', 260, 100, 250, 60, 0),
(239, 246, 'pessi', 1, 'Piring', 70, 100, 21313, 20, 50),
(243, 250, 'Soto', 1, 'Piring', 100, 100, 200, 0, 0),
(244, 251, 'Sate', 1, 'Piring', 200, 100, 250, 0, 0),
(245, 252, 'Ayam', 1, 'Piring', 100, 100, 200, 0, 0),
(246, 253, 'Bebek', 1, 'Piring', 200, 100, 250, 0, 0),
(247, 254, 'Ayam', 1, 'Piring', 100, 100, 200, 0, 0),
(248, 255, 'Bebek', 1, 'Piring', 200, 100, 250, 0, 0),
(259, 266, 'pitek', 1, 'Piring', 100, 100, 200, 0, 0),
(260, 267, 'mentok', 1, 'Piring', 200, 100, 250, 0, 0),
(261, 268, 'pitek', 2, 'Gelas', 100, 100, 200, 0, 0),
(262, 269, 'mentok', 2, 'Gelas', 200, 100, 250, 0, 0),
(263, 270, 'es jeruk', 2, 'Gelas', 100, 100, 200, 0, 0),
(264, 271, 'es teh', 2, 'Gelas', 200, 100, 250, 0, 0),
(265, 272, 'es kelapa', 2, 'Gelas', 100, 100, 200, 0, 0),
(266, 273, 'es air', 2, 'Gelas', 200, 100, 250, 0, 0),
(267, 274, 'Telur Rebus', 1, 'Piring', 100, 100, 200, 0, 0),
(268, 275, 'Telur Goreng', 1, 'Piring', 200, 100, 250, 0, 0),
(269, 276, 'Telur Ceplok', 1, 'Piring', 100, 100, 200, 0, 0),
(270, 277, 'Telur Dadar', 1, 'Piring', 200, 100, 250, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit`
--

CREATE TABLE `unit` (
  `unit_id` int(10) UNSIGNED NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `unit_deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `unit`
--

INSERT INTO `unit` (`unit_id`, `unit_name`, `unit_deleted_at`) VALUES
(1, 'Piring', NULL),
(2, 'Gelas', NULL),
(3, 'Kardus', NULL),
(4, 'Pcs', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `user_password`) VALUES
(5, 'piteknoob@gmail.com', '$2y$10$seIB84ya.oVUw.zRSrfDiOgo954ZOJ1pTZuj8vbzf1o0cZjFM7.SS'),
(6, 'vino@gmail.com', '$2y$10$8Xf0m/2VH9hAybLLienxbeB7jxdOtZgH1JtCrvfzO5Oo0roCluxKK'),
(7, 'pitek@gmail.com', '$2y$10$exBdM43njtRxbrLJv8SO0eDlklEzX0TkE8YZciAtehIXi4EHHD2CG'),
(8, 'nugger@gmail.com', '$2y$10$4snIdSzoQcUlzybSdqsQGeLqrDO7PiKblSMc/Si5WsltkQBPKNWpi'),
(9, 'floyd@gmail.com', '$2y$10$I0WAyiz6VmmJo6krZ.dYYOeWU/j7sKCnKE1gMuj.EYLUqdOLP1mJq'),
(10, 'thomasrovino@gmail.com', '$2y$10$1yzCCftTwq1gRO2v5PXdn.MDLhCn2ymtNk/cKCxuRUfcdBk0L0xJK');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `auth_user`
--
ALTER TABLE `auth_user`
  ADD PRIMARY KEY (`auth_user_id`),
  ADD KEY `FK_auth_user_user` (`auth_user_user_id`);

--
-- Indeks untuk tabel `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indeks untuk tabel `log_stock`
--
ALTER TABLE `log_stock`
  ADD PRIMARY KEY (`log_stock_id`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `FK_product_category` (`product_category_id`);

--
-- Indeks untuk tabel `product_stock`
--
ALTER TABLE `product_stock`
  ADD PRIMARY KEY (`product_stock_id`),
  ADD KEY `fk_product_stock_product` (`product_stock_product_id`),
  ADD KEY `fk_product_stock_unit` (`product_stock_unit_id`);

--
-- Indeks untuk tabel `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `auth_user`
--
ALTER TABLE `auth_user`
  MODIFY `auth_user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `log_stock`
--
ALTER TABLE `log_stock`
  MODIFY `log_stock_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=278;

--
-- AUTO_INCREMENT untuk tabel `product_stock`
--
ALTER TABLE `product_stock`
  MODIFY `product_stock_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT untuk tabel `unit`
--
ALTER TABLE `unit`
  MODIFY `unit_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `auth_user`
--
ALTER TABLE `auth_user`
  ADD CONSTRAINT `FK_auth_user_user` FOREIGN KEY (`auth_user_user_id`) REFERENCES `user` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_product_category` FOREIGN KEY (`product_category_id`) REFERENCES `category` (`category_id`);

--
-- Ketidakleluasaan untuk tabel `product_stock`
--
ALTER TABLE `product_stock`
  ADD CONSTRAINT `fk_product_stock_product` FOREIGN KEY (`product_stock_product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `fk_product_stock_unit` FOREIGN KEY (`product_stock_unit_id`) REFERENCES `unit` (`unit_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
