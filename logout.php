<!--Created By: Nadia Aprilia Siregar 
    Created Date: 2025-02-27
    this project is for BBPSDMP (Center for Human Resources Development and Research of Communication and Informatics Medan)
-->

<?php
session_start(); // Mulai session

// Hapus session untuk logout
session_destroy();

// Arahkan kembali ke halaman login
header('Location: login.php');
exit();
?>
