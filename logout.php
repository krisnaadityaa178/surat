<!--Created By: Nadia Aprilia Siregar 
    Created Date: 2025-02-27
    this project is for BBPSDMP (Center for Human Resources Development and Research of Communication and Informatics Medan)
-->

<?php
session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Hapus cookie session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), // Nama cookie session
        '', // Nilai kosong
        time() - 42000, // Waktu kedaluwarsa di masa lalu
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Set header untuk mencegah caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

header('Location: login.php'); // Redirect ke halaman login
exit();
?>
