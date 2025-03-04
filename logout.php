<?php
session_start(); // Mulai session

// Hapus session untuk logout
session_destroy();

// Arahkan kembali ke halaman login
header('Location: login.php');
exit();
?>
