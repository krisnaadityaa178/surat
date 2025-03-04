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

// Fungsi untuk memindahkan surat masuk ke surat keluar
if (isset($_GET['move_id'])) {
    $move_id = $_GET['move_id'];

    // Ambil data surat masuk berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM sk_masuk WHERE id = ?"); // Mengambil data surat masuk
    $stmt->execute([$move_id]);
    $surat = $stmt->fetch();

    if ($surat) {
        // Tentukan lokasi file asal dan tujuan
        $file_surat = $surat['file_surat'];
        $upload_dir_keluar = 'sk_keluar/'; // Lokasi folder surat keluar
        $file_surat_tujuan = $upload_dir_keluar . basename($file_surat);

        // Memindahkan file ke folder surat keluar
        if (rename($file_surat, $file_surat_tujuan)) {
            // Masukkan data ke tabel surat_keluar
            $stmt = $pdo->prepare("INSERT INTO sk_keluar (nomor_surat, satuan_kerja, kode_klasifikasi, sub_klasifikasi, bulan, tahun, nomor_keluar, tanggal_surat, ringkasan_isi_surat, file_surat) 
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
                $file_surat_tujuan
            ]);

            // Hapus data dari tabel surat (surat masuk)
            $stmt = $pdo->prepare("DELETE FROM sk_masuk WHERE id = ?");
            $stmt->execute([$move_id]);

            // Redirect ke halaman surat keluar setelah data dipindahkan
            header('Location: sk_keluar.php');
            exit();
        } else {
            echo "Gagal memindahkan file surat.";
        }
    }
}

// Fungsi untuk memindahkan SK Masuk ke SK Keluar
if (isset($_GET['move_id'])) {
    $move_id = $_GET['move_id'];

    // Ambil data SK Masuk berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM sk_masuk WHERE id = ?");
    $stmt->execute([$move_id]);
    $sk = $stmt->fetch();

    if ($sk) {
        // Masukkan data ke tabel sk_keluar
        $stmt = $pdo->prepare("INSERT INTO sk_keluar (nomor_surat, satuan_kerja, kode_klasifikasi, sub_klasifikasi, bulan, tahun, nomor_keluar, tanggal_surat, ringkasan_isi_surat, file_surat) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([ 
            $sk['nomor_surat'], 
            $sk['satuan_kerja'], 
            $sk['kode_klasifikasi'], 
            $sk['sub_klasifikasi'], 
            $sk['bulan'], 
            $sk['tahun'], 
            $sk['nomor_keluar'], 
            $sk['tanggal_surat'], 
            $sk['ringkasan_isi_surat'], 
            $sk['file_surat']
        ]);

        // Hapus data dari tabel sk_masuk
        $stmt = $pdo->prepare("DELETE FROM sk_masuk WHERE id = ?");
        $stmt->execute([$move_id]);

        $_SESSION['message'] = "Surat Keputusan berhasil dipindahkan ke Surat Keputusan Keluar.";
        header('Location: sk_keluar.php');
        exit();
    }
}

// Fungsi untuk menghapus surat masuk dan file terkait
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Ambil informasi file sebelum menghapus data dari database
    $stmt = $pdo->prepare("SELECT file_surat FROM sk_keluar WHERE id = ?");
    $stmt->execute([$delete_id]);
    $surat = $stmt->fetch();

    if ($surat && !empty($surat['file_surat'])) {
        $file_path = $surat['file_surat'];
        
        // Cek apakah file ada sebelum dihapus
        if (file_exists($file_path)) {
            unlink($file_path); // Hapus file dari folder
        }
    }

    // Hapus data dari database setelah file dihapus
    $stmt = $pdo->prepare("DELETE FROM sk_keluar WHERE id = ?");
    $stmt->execute([$delete_id]);

    // Redirect setelah data dihapus
    header('Location: sk_keluar.php');
    exit();
}

// Ambil data Surat Keputusan Keluar
$stmt = $pdo->query("SELECT * FROM sk_keluar");
$sk_keluar_data = $stmt->fetchAll();

// Ambil data Surat Keputusan Masuk
$stmt = $pdo->query("SELECT * FROM sk_masuk");
$sk_masuk_data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/styles.css">
    <title>Surat Keputusan Keluar</title>
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
            <h2>Surat Keputusan Keluar</h2>
            <!-- Tombol untuk membuka modal -->
            <a href="javascript:void(0);" class="btn btn-view" id="openModalBtn">+ Add New Surat Keputusan Keluar</a>
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
            <?php foreach ($sk_keluar_data as $sk): ?>
            <tr>
                <td><?= $sk['nomor_surat'] ?></td>
                <td><?= $sk['satuan_kerja'] ?></td>
                <td><?= $sk['kode_klasifikasi'] ?></td>
                <td><?= $sk['sub_klasifikasi'] ?></td>
                <td>
                    <?php
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
                    echo $bulan_nama[$sk['bulan']];
                    ?>
                </td>
                <td><?= $sk['tahun'] ?></td>
                <td><?= $sk['nomor_keluar'] ?></td>
                <td><?= $sk['tanggal_surat'] ?></td>
                <td><?= $sk['ringkasan_isi_surat'] ?></td>
                <td>
                <?php
                    $file_path = $sk['file_surat'];
                    $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);
                    $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png', 'docx', 'xlsx', 'txt'];
                    
                    if (in_array($file_ext, $allowed_extensions)) {
                        echo "<a href='#' onclick='openModal(\"$file_path\")' class='btn-view-file'>view</a>";
                    }                   
                    ?>
                </td>
                <td>
                    <a href="sk_keluar.php?delete_id=<?= $sk['id'] ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus surat keputusan ini?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Modal untuk menampilkan SK Masuk -->
    <div id="addModal" class="modal-out">
        <div class="modal-content-out">
            <span class="close-btn-out">&times;</span>
            <h3>Pilih Surat Keputusan Masuk</h3>
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
                <?php foreach ($sk_masuk_data as $sk): ?>
                <tr>
                    <td><?= $sk['nomor_surat'] ?></td>
                    <td><?= $sk['satuan_kerja'] ?></td>
                    <td><?= $sk['kode_klasifikasi'] ?></td>
                    <td><?= $sk['sub_klasifikasi'] ?></td>
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
                        echo $bulan_nama[$sk['bulan']];
                        ?>
                    </td>
                    <td><?= $sk['tahun'] ?></td>
                    <td><?= $sk['nomor_keluar'] ?></td>
                    <td><?= $sk['tanggal_surat'] ?></td>
                    <td><?= $sk['ringkasan_isi_surat'] ?></td>
                    <td>
                    <?php
                        $file_path = $sk['file_surat'];
                        $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);
                        $allowed_extensions = ['pdf'];
                        
                        if (in_array($file_ext, $allowed_extensions)) {
                            echo "<a href='#' onclick='openModal(\"$file_path\")' class='btn-view-file'>view</a>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="action-buttons-out">
                            <a href="sk_keluar.php?move_id=<?= $sk['id'] ?>" class="btn btn-move" onclick="return confirm('Apakah Anda yakin ingin memindahkan surat ini ke Surat Keputusan Keluar?')">Move to SK Keluar</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

                    <!-- Modal untuk menampilkan file -->
                    <div id="fileModal" class="modal-file">
                        <div class="modal-content-file">
                            <span class="close-btn-file" onclick="closeModal()">&times;</span>
                            <h3>Pratinjau File</h3>
                            <div id="filePreview"></div>    
                        </div>
                    </div>

                                <!-- Loading Spinner -->
        <div id="loading-spinner" class="loading-spinner">
            <div class="spinner"></div>
            <p>Tunggu Sebentar....</p>
        </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Arsip Surat Keputusan - Nadia Aprilia Siregar</p>
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

            if (['pdf'].includes(fileExtension)) {
                filePreview.innerHTML = `<iframe src="${filePath}" width="100%" height="500px" style="border: none;"></iframe>`;
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