<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
session_start();
include('config.php');

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data admin berdasarkan session
$stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

// Gunakan foto default jika admin belum mengunggah foto
$photoPath = $admin['photo'] ? "uploads/" . $admin['photo'] : "uploads/default.png";

// Ambil data log penghapusan
$query = "SELECT log_hapus_surat.*, admin.admin_name 
          FROM log_hapus_surat 
          JOIN admin ON log_hapus_surat.admin_id = admin.id 
          ORDER BY waktu_hapus DESC";
$stmt = $pdo->query($query);
$log_data = $stmt->fetchAll();

// Jika ada permintaan file dari AJAX
if (isset($_GET['file'])) {
    $file_name = urldecode($_GET['file']); // Decode URL
    $file_path = __DIR__ . "/file_dihapus/" . basename($file_name); // Pastikan path aman

    if (file_exists($file_path)) {
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=\"" . basename($file_path) . "\"");
        readfile($file_path);
        exit();
    } else {
        echo "File tidak ditemukan.";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Penghapusan Surat</title>
    <style>
        /* Styling untuk Tabel dan Modal */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
        }
        h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
        }
        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #007bff;
            color: white;
            text-transform: uppercase;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 80%;
            max-width: 700px;
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
        }
        .close {
            position: absolute;
            top: 5px;
            right: 15px;
            font-size: 40px;
            cursor: pointer;
            color: red;
            transition: color 0.3s ease;
        }
        .close:hover {
            color: #000;
        }
        iframe {
            width: 100%;
            height: 500px;
            border: none;
            border-radius: 5px;
        }

        /* Tombol Kembali */
.back-button {
    display: block;
    width: 200px;
    margin: 20px auto; /* Tengah secara horizontal */
    padding: 10px;
    background: red;
    color: white;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    transition: background 0.3s;
}

.back-button:hover {
    background: #28a745; /* Warna hijau saat hover */
}

    </style>
</head>
<body>

    <h2>Log Penghapusan Surat</h2>
    <table border="1">
        <tr>
            <th>Administrator</th>
            <th>ID Surat</th>
            <th>Kategori</th>
            <th>Waktu Hapus</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($log_data as $log): ?>
        <tr>
            <td><?= htmlspecialchars($log['admin_name']); ?></td>
            <td><?= htmlspecialchars($log['surat_id']); ?></td>
            <td><?= htmlspecialchars($log['kategori_surat']); ?></td>
            <td><?= htmlspecialchars($log['waktu_hapus']); ?></td>
            <td>
                <button onclick="openModal('<?= urlencode($log['file_surat']); ?>')">View</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="index.php" class="back-button">Kembali</a>

    <!-- Modal -->
    <div id="fileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <iframe id="fileViewer" src="" allowfullscreen></iframe>
        </div>
    </div>

    <script>
        function openModal(fileName) {
            let filePath = "file_dihapus/" + fileName; // Pastikan path benar
            document.getElementById("fileViewer").src = "?file=" + encodeURIComponent(filePath);
            document.getElementById("fileModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("fileModal").style.display = "none";
            document.getElementById("fileViewer").src = "";
        }

        window.onclick = function(event) {
            let modal = document.getElementById("fileModal");
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>

</body>
</html>
