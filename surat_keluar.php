<!--Created By: Nadia Aprilia Siregar 
    Created Date: 2025-02-27
    this project is for BBPSDMP (Center for Human Resources Development and Research of Communication and Informatics Medan)
-->

<?php
session_start();
include('config.php');

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Fungsi untuk menghapus data surat
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Menyiapkan statement untuk memastikan data ada
    $stmt = $pdo->prepare("SELECT * FROM surat_keluar WHERE id = ?"); // Ganti 'surat' menjadi 'surat_keluar'
    $stmt->execute([$delete_id]);
    $surat = $stmt->fetch();

    // Pastikan surat ditemukan sebelum menghapus
    if ($surat) {
        // Menghapus data dari tabel surat_keluar berdasarkan ID
        $stmt = $pdo->prepare("DELETE FROM surat_keluar WHERE id = ?"); // Ganti 'surat' menjadi 'surat_keluar'
        $isDeleted = $stmt->execute([$delete_id]);

        // Cek apakah penghapusan berhasil
        //if ($isDeleted) {
            // Berikan pesan sukses dan redirect
            //$_SESSION['message'] = "Surat berhasil dihapus.";
            //header('Location: surat_keluar.php');
            //exit();  // Pastikan exit agar tidak ada output lain yang dikirim
        //} else {
            // Jika gagal, beri pesan error
            //$_SESSION['error'] = "Gagal menghapus surat.";
            //header('Location: surat_keluar.php');
            //exit();
        //}
    //} else {
        // Jika surat tidak ditemukan, beri pesan error
        //$_SESSION['error'] = "Surat tidak ditemukan.";
        //header('Location: surat_keluar.php');
        //exit();
    }
}

// Operasi Create (Tambah Data)
if (isset($_POST['create'])) {
    $nomor_surat = $_POST['nomor_surat'];
    $satuan_kerja = $_POST['satuan_kerja'];
    $kode_klasifikasi = $_POST['kode_klasifikasi'];
    $sub_klasifikasi = $_POST['sub_klasifikasi'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $nomor_keluar = $_POST['nomor_keluar'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $ringkasan_isi_surat = $_POST['ringkasan_isi_surat'];

    // Proses upload file
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
        $file_name = $_FILES['file_surat']['name'];
        $file_tmp = $_FILES['file_surat']['tmp_name'];
        $file_type = $_FILES['file_surat']['type'];
        $file_size = $_FILES['file_surat']['size'];

        // Tentukan lokasi penyimpanan file
        $upload_dir = 'surat_keluar/';
        $file_path = $upload_dir . basename($file_name);

        // Pastikan file yang di-upload adalah tipe yang diinginkan
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];

        if (in_array($file_type, $allowed_types)) {
            // Pindahkan file ke direktori yang ditentukan
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Simpan data ke database
                $stmt = $pdo->prepare("INSERT INTO surat_keluar (nomor_surat, satuan_kerja, kode_klasifikasi, sub_klasifikasi, bulan, tahun, nomor_keluar, tanggal_surat, ringkasan_isi_surat, file_surat) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nomor_surat, $satuan_kerja, $kode_klasifikasi, $sub_klasifikasi, $bulan, $tahun, $nomor_keluar, $tanggal_surat, $ringkasan_isi_surat, $file_path]);

                // Redirect ke halaman yang sama setelah data disimpan
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "File upload failed.";
            }
        } else {
            echo "Invalid file type.";
        }
    } else {
        echo "No file uploaded or error in uploading file.";
    }
}

// Fungsi untuk memindahkan surat masuk ke surat keluar
if (isset($_GET['move_id'])) {
    $move_id = $_GET['move_id'];

    // Ambil data surat masuk berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM surat WHERE id = ?"); // Mengambil data surat masuk
    $stmt->execute([$move_id]);
    $surat = $stmt->fetch();

    if ($surat) {
        // Masukkan data ke tabel surat_keluar
        $stmt = $pdo->prepare("INSERT INTO surat_keluar (nomor_surat, satuan_kerja, kode_klasifikasi, sub_klasifikasi, bulan, tahun, nomor_keluar, tanggal_surat, ringkasan_isi_surat, file_surat) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([ 
            $surat['nomor_surat'], 
            $surat['satuan_kerja'], 
            $surat['kode_klasifikasi'], 
            $surat['sub_klasifikasi'], 
            $surat['bulan'], 
            $surat['tahun'], 
            $surat['nomor_keluar'], 
            $surat['tanggal_surat'], 
            $surat['ringkasan_isi_surat'], 
            $surat['file_surat']
        ]);

        // Hapus data dari tabel surat (surat masuk)
        $stmt = $pdo->prepare("DELETE FROM surat WHERE id = ?");
        $stmt->execute([$move_id]);

        // Redirect ke halaman surat keluar setelah data dipindahkan
        header('Location: surat_keluar.php');
        exit();
    }
}

// Ambil data surat keluar dari database
$stmt = $pdo->query("SELECT * FROM surat_keluar");
$surat_keluar_data = $stmt->fetchAll();

// Ambil data surat masuk dari database
$stmt = $pdo->query("SELECT * FROM surat");
$surat_masuk_data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/styles.css">
    <title>Admin Surat Keluar</title>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
            <div class="profile">
                <img src="https://www.w3schools.com/w3images/avatar2.png" alt="Admin">
                <h3>Hi Admin</h3>
                <p>Administrator</p>
            </div>

            <a href="sk_masuk.php">
                <i class="fas fa-scroll"></i> Surat Keputusan Masuk
            </a>
            <a href="sk_keluar.php">
                <i class="fas fa-sign-out-alt"></i> Surat Keputusan Keluar
            </a>
            <a href="index.php">
                <i class="fas fa-inbox"></i> Surat Masuk
            </a>
            <a href="surat_keluar.php">
                <i class="fas fa-envelope"></i> Surat Keluar
            </a>
            <a href="pengguna.php">
                <i class="fas fa-users"></i> Pengguna
            </a>
            <a href="pengaturan.php">
                <i class="fas fa-cogs"></i> Pengaturan
            </a>
        </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h2>Surat Keluar</h2>
            <a href="javascript:void(0);" class="btn btn-view" id="openModalBtn">+ Add New Surat Keluar</a>
        </div>

        <!-- Tampilkan pesan sukses atau error -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table>
            <tr>
                <th>Nomor Surat</th>
                <th>Satuan Kerja</th>
                <th>Kode Klasifikasi</th>
                <th>Sub Klasifikasi</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Nomor Keluar</th>
                <th>Tanggal Surat</th>
                <th>Ringkasan Isi Surat</th>
                <th>File Surat</th>
                <th>Action</th>
            </tr>
            <?php foreach ($surat_keluar_data as $surat): ?>
            <tr>
                <td><?= $surat['nomor_surat'] ?></td>
                <td><?= $surat['satuan_kerja'] ?></td>
                <td><?= $surat['kode_klasifikasi'] ?></td>
                <td><?= $surat['sub_klasifikasi'] ?></td>
                <td>
                    <?php
                        // Konversi bulan menjadi nama bulan
                        $bulan_nama = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember'
                        ];
                        echo $bulan_nama[$surat['bulan']];
                    ?>
                </td>
                <td><?= $surat['tahun'] ?></td>
                <td><?= $surat['nomor_keluar'] ?></td>
                <td><?= $surat['tanggal_surat'] ?></td>
                <td><?= $surat['ringkasan_isi_surat'] ?></td>
                <td>
                    <?php
                    // Menampilkan pratinjau file berdasarkan tipe
                    $file_path = $surat['file_surat'];
                    $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);

                    if (in_array($file_ext, ['pdf']))  {
                        echo "<a href='#' onclick='openModal(\"$file_path\")' class='btn-view-file'>view</a>";
                    }
                    ?>
                </td>
                <td>
                    <div class="action-buttons">
                    <a href="surat_keluar.php?delete_id=<?= $surat['id'] ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Modal untuk menampilkan surat masuk -->
    <div id="addModal" class="modal-out">
        <div class="modal-content-out">
            <span class="close-btn-out">&times;</span>
            <h3>Pilih Surat Masuk</h3>
            <table>
                <tr>
                    <th>Nomor Surat</th>
                    <th>Satuan Kerja</th>
                    <th>Kode Klasifikasi</th>
                    <th>Sub Klasifikasi</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Nomor Keluar</th>
                    <th>Tanggal Surat</th>
                    <th>Ringkasan Isi Surat</th>
                    <th>File Surat</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($surat_masuk_data as $surat): ?>
                <tr>
                    <td><?= $surat['nomor_surat'] ?></td>
                    <td><?= $surat['satuan_kerja'] ?></td>
                    <td><?= $surat['kode_klasifikasi'] ?></td>
                    <td><?= $surat['sub_klasifikasi'] ?></td>
                    <td>
                        <?php
                            // Konversi bulan menjadi nama bulan
                            $bulan_nama = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ];
                            echo $bulan_nama[$surat['bulan']];
                        ?>
                    </td>
                    <td><?= $surat['tahun'] ?></td>
                    <td><?= $surat['nomor_keluar'] ?></td>
                    <td><?= $surat['tanggal_surat'] ?></td>
                    <td><?= $surat['ringkasan_isi_surat'] ?></td>
                    <td>
                        <?php
                        // Menampilkan pratinjau file berdasarkan tipe
                        $file_path = $surat['file_surat'];
                        $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);

                        if (in_array($file_ext, ['pdf']))  {
                            echo "<a href='#' onclick='openModal(\"$file_path\")' class='btn-view-file'>View</a>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="action-buttons-out">
                            <a href="surat_keluar.php?move_id=<?= $surat['id'] ?>" class="btn btn-move" onclick="return confirm('Apakah Anda yakin ingin memindahkan surat ini ke Surat Keluar?')">Move to Surat Keluar</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

            <!-- Loading Spinner -->
        <div id="loading-spinner" class="loading-spinner">
            <div class="spinner"></div>
            <p>Tunggu Sebentar....</p>
        </div>

                    <!-- Modal untuk menampilkan file -->
                    <div id="fileModal" class="modal-file">
        <div class="modal-content-file">
            <span class="close-btn-file" onclick="closeModal()">&times;</span>
            <h3>Pratinjau File</h3>
            <div id="filePreview"></div>    
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Arsip Surat - Nadia Aprilia Siregar</p>
    </footer>

    <script>
        // Get the modal
        document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById("openModalBtn");
        var modal = document.getElementById("addModal");
        var span = document.getElementsByClassName("close-btn-out")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });

                // Fungsi untuk menampilkan loading spinner
                function showLoading() {
            document.getElementById('loading-spinner').style.display = 'flex';
        }

        // Fungsi untuk menyembunyikan loading spinner
        function hideLoading() {
            document.getElementById('loading-spinner').style.display = 'none';
        }

        // Tampilkan spinner saat halaman dimuat
        window.addEventListener('load', function() {
            showLoading();
            // Sembunyikan spinner setelah 2 detik (simulasi proses loading)
            setTimeout(function() {
                hideLoading();
            }, 2000); // Ganti dengan proses asli Anda
        });

        // Tampilkan spinner saat form dikirim
        document.querySelector('form').addEventListener('submit', function() {
            showLoading();
        });

        // Tampilkan spinner saat tombol "Delete" diklik
        document.querySelectorAll('.btn-delete').forEach(function(button) {
            button.addEventListener('click', function() {
                showLoading();
            });
        });

        // Tampilkan spinner saat tombol "Edit" diklik
        document.querySelectorAll('.btn-edit').forEach(function(button) {
            button.addEventListener('click', function() {
                showLoading();
            });
        });

        // Modal functionality for viewing files
        function openModal(filePath) {
            var modal = document.getElementById('fileModal');
            modal.style.display = "block";

            var filePreview = document.getElementById('filePreview');
            var downloadLink = document.getElementById('downloadLink');

            var fileExtension = filePath.split('.').pop().toLowerCase();

            if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                filePreview.innerHTML = `<img src="${filePath}" style="max-width: 100%; height: auto;" alt="File Preview">`;
            } else if (fileExtension === 'pdf') {
                filePreview.innerHTML = '<iframe src="${filePath}" width="100%" height="500px" style="border: none;"></iframe>';
            } else {
                filePreview.innerHTML = '<p>File type is not supported for preview. <a href="${filePath}" target="_blank">Download file</a></p>';
            }
        }

        function closeModal() {
            var modal = document.getElementById('fileModal');
            modal.style.display = "none";
        }
    </script>
</body>
</html>