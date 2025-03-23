-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Mar 2025 pada 18.43
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
(8, '1669', 'BBPSDMP', 'KU', '01.1998', 2, 2025, '1669/BBPSDMP', '2025-02-12', 'tesss', 'sk_keluar/DAFTAR AGENDA SURAT BBPSDMP KOMINFO MEDAN - Google Spreadsheet.pdf'),
(10, '167', 'BBPSDMP', 'TA', '01.1998', 2, 2025, '167/BBPSDMP', '2025-02-12', 'ddfdfd', 'sk_keluar/DAFTAR AGENDA SURAT BBPSDMP KOMINFO MEDAN - Google Spreadsheet.pdf');

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

--
-- Dumping data untuk tabel `surat`
--

INSERT INTO `surat` (`id`, `nomor_surat`, `satuan_kerja`, `kode_klasifikasi`, `sub_klasifikasi`, `bulan`, `tahun`, `nomor_keluar`, `tanggal_surat`, `ringkasan_isi_surat`, `file_surat`) VALUES
(25, '166990', 'BBPSDMP', 'KA', '01.1998', 2, 2025, '1669776/BBPSDMP', '2025-02-12', 'test ', 'surat_masuk/Daftar Hadir Peserta 25 Februari 2025.pdf');

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
-- Dumping data untuk tabel `surat_keluar`
--

INSERT INTO `surat_keluar` (`id`, `nomor_surat`, `satuan_kerja`, `kode_klasifikasi`, `sub_klasifikasi`, `bulan`, `tahun`, `nomor_keluar`, `tanggal_surat`, `ringkasan_isi_surat`, `file_surat`, `created_at`) VALUES
(17, '1669', 'BBPSDMP', 'TA', '01.165', 2, 2025, '1669/BBPSDMP', '2025-02-12', 'tes', 'surat_keluar/DAFTAR AGENDA SURAT BBPSDMP KOMINFO MEDAN - Google Spreadsheet.pdf', '2025-03-09 12:55:26');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
