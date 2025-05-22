-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Bulan Mei 2025 pada 10.02
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
-- Database: `surat_database`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `admin_name`, `username`, `password`, `photo`) VALUES
(14, 'Nadia Aprilia', 'admin12', '$2y$12$Bv6nm0LC1yfgo3Yk9md9Vehxqn2pN.nsG2XsROsm5tKJuFFjZETFK', '1741532766_testpp.jpeg'),
(16, 'Nadia Aprilia', 'admin', '$2a$04$Nu.pgnOcTzPu.QTvPlg5lefJYNr0csM/i3m2nTaNNWmNHMIEsrNQa', '1742648254_3062f77e-550f-4962-9ccb-74a353b13717.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_hapus_surat`
--

CREATE TABLE `log_hapus_surat` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `surat_id` int(11) DEFAULT NULL,
  `kategori_surat` enum('Surat Masuk','Surat Keluar','SK Masuk','SK Keluar') DEFAULT NULL,
  `waktu_hapus` timestamp NOT NULL DEFAULT current_timestamp(),
  `nomor_surat` varchar(50) DEFAULT NULL,
  `satuan_kerja` varchar(100) DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `status_pulih` tinyint(1) DEFAULT 0,
  `file_surat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_hapus_surat`
--

INSERT INTO `log_hapus_surat` (`id`, `admin_id`, `surat_id`, `kategori_surat`, `waktu_hapus`, `nomor_surat`, `satuan_kerja`, `bulan`, `tahun`, `status_pulih`, `file_surat`) VALUES
(7, 16, 20, 'Surat Keluar', '2025-03-28 17:05:37', NULL, NULL, NULL, NULL, 0, 'D:\\xampp\\htdocs\\surat/file_dihapus/DAFTAR AGENDA SURAT BBPSDMP KOMINFO MEDAN - Google Spreadsheet.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sk_keluar`
--

CREATE TABLE `sk_keluar` (
  `id` int(11) NOT NULL,
  `nomor_surat` varchar(25) DEFAULT NULL,
  `satuan_kerja` varchar(25) DEFAULT NULL,
  `kode_klasifikasi` varchar(25) DEFAULT NULL,
  `sub_klasifikasi` varchar(25) DEFAULT NULL,
  `bulan` int(11) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `nomor_keluar` varchar(25) DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `ringkasan_isi_surat` text DEFAULT NULL,
  `file_surat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sk_keluar`
--

INSERT INTO `sk_keluar` (`id`, `nomor_surat`, `satuan_kerja`, `kode_klasifikasi`, `sub_klasifikasi`, `bulan`, `tahun`, `nomor_keluar`, `tanggal_surat`, `ringkasan_isi_surat`, `file_surat`) VALUES
(8, '1669', 'BBPSDMP', 'KU', '01.1998', 2, 2025, '1669/BBPSDMP', '2025-02-12', 'tesss', 'sk_keluar/DAFTAR AGENDA SURAT BBPSDMP KOMINFO MEDAN - Google Spreadsheet.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sk_masuk`
--

CREATE TABLE `sk_masuk` (
  `id` int(11) NOT NULL,
  `nomor_surat` varchar(25) NOT NULL,
  `satuan_kerja` varchar(25) DEFAULT NULL,
  `kode_klasifikasi` varchar(25) DEFAULT NULL,
  `sub_klasifikasi` varchar(25) DEFAULT NULL,
  `bulan` int(11) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `nomor_keluar` varchar(25) DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `ringkasan_isi_surat` text DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sk_masuk`
--

INSERT INTO `sk_masuk` (`id`, `nomor_surat`, `satuan_kerja`, `kode_klasifikasi`, `sub_klasifikasi`, `bulan`, `tahun`, `nomor_keluar`, `tanggal_surat`, `ringkasan_isi_surat`, `file_surat`) VALUES
(21, '1669', 'BBPSDMP', 'FR', '01.165', 3, 2025, '166/BBPSDMP', '2025-03-12', 'tes sk masuk', 'sk_masuk/kominfo 1.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat`
--

CREATE TABLE `surat` (
  `id` int(11) NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `satuan_kerja` varchar(50) NOT NULL,
  `kode_klasifikasi` varchar(50) NOT NULL,
  `sub_klasifikasi` varchar(50) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nomor_keluar` varchar(25) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `ringkasan_isi_surat` text NOT NULL,
  `file_surat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_keluar`
--

CREATE TABLE `surat_keluar` (
  `id` int(11) NOT NULL,
  `nomor_surat` varchar(255) NOT NULL,
  `satuan_kerja` varchar(255) NOT NULL,
  `kode_klasifikasi` varchar(255) NOT NULL,
  `sub_klasifikasi` varchar(255) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nomor_keluar` varchar(255) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `ringkasan_isi_surat` text NOT NULL,
  `file_surat` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_hapus_surat`
--
ALTER TABLE `log_hapus_surat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indeks untuk tabel `sk_keluar`
--
ALTER TABLE `sk_keluar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sk_masuk`
--
ALTER TABLE `sk_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `surat_keluar`
--
ALTER TABLE `surat_keluar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `log_hapus_surat`
--
ALTER TABLE `log_hapus_surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `sk_keluar`
--
ALTER TABLE `sk_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `sk_masuk`
--
ALTER TABLE `sk_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `surat`
--
ALTER TABLE `surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `surat_keluar`
--
ALTER TABLE `surat_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `log_hapus_surat`
--
ALTER TABLE `log_hapus_surat`
  ADD CONSTRAINT `log_hapus_surat_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
