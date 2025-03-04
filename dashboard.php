<?php
session_start();
include('config.php');

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data jumlah surat masuk
$stmt_in = $pdo->query("SELECT COUNT(*) FROM surat WHERE nomor_surat = 'masuk'");
$jumlah_surat_masuk = $stmt_in->fetchColumn();

// Ambil data jumlah surat keluar
$stmt_out = $pdo->query("SELECT COUNT(*) FROM surat WHERE nomor_surat = 'keluar'");
$jumlah_surat_keluar = $stmt_out->fetchColumn();

// Ambil data jumlah pengguna
$stmt_users = $pdo->query("SELECT COUNT(*) FROM admin");
$jumlah_pengguna = $stmt_users->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>Dashboard Admin</title>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile">
            <img src="https://www.w3schools.com/w3images/avatar2.png" alt="Admin">
            <h3>Hi Admin</h3>
            <p>Administrator</p>
        </div>
        <a href="index.php">Home</a>
        <a href="pengguna.php">Pengguna</a>
        <a href="surat_keluar.php">Surat Keluar</a>
        <a href="pengaturan.php">Pengaturan</a>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h2>Dashboard Admin</h2>
        </div>

        <!-- Dashboard Stats -->
        <div class="dashboard">
            <div class="stat-box">
                <h3>Jumlah Surat Masuk</h3>
                <p><?= $jumlah_surat_masuk ?></p>
            </div>
            <div class="stat-box">
                <h3>Jumlah Surat Keluar</h3>
                <p><?= $jumlah_surat_keluar ?></p>
            </div>
            <div class="stat-box">
                <h3>Jumlah Pengguna</h3>
                <p><?= $jumlah_pengguna ?></p>
            </div>
        </div>

        <!-- Other content (e.g., tables) -->
        <!-- Add your other content here, such as a table of Surat Masuk -->
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Arsip Surat - All Rights Reserved</p>
    </footer>

</body>
</html>
