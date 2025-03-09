-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Mar 2025 pada 15.37
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
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(2, 'admin', 'password123'),
(8, 'admin1', 'password');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `sk_keluar`
--
ALTER TABLE `sk_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `sk_masuk`
--
ALTER TABLE `sk_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `surat`
--
ALTER TABLE `surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `surat_keluar`
--
ALTER TABLE `surat_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
