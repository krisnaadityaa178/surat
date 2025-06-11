<!--Created By: Nadia Aprilia Siregar 
    Created Date: 2025-02-27
    this project is for BBPSDMP (Center for Human Resources Development and Research of Communication and Informatics Medan)
-->

<!-- surat_masuk -->
<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Jika HTTPS digunakan
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
        $upload_dir = 'surat_masuk/';
        $file_path = $upload_dir . basename($file_name);

        // Pastikan file yang di-upload adalah tipe yang diinginkan
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];

        if (in_array($file_type, $allowed_types)) {
            // Pindahkan file ke direktori yang ditentukan
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Simpan data ke database
                $stmt = $pdo->prepare("INSERT INTO surat (nomor_surat, satuan_kerja, kode_klasifikasi, sub_klasifikasi, bulan, tahun, nomor_keluar, tanggal_surat, ringkasan_isi_surat, file_surat) 
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

// Fungsi untuk menghapus surat masuk dan file terkait
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $admin_id = $_SESSION['admin_id']; // Ambil ID admin dari session

    // Ambil informasi file sebelum menghapus
    $stmt = $pdo->prepare("SELECT file_surat FROM surat WHERE id = ?");
    $stmt->execute([$delete_id]);
    $surat = $stmt->fetch();

    if ($surat && !empty($surat['file_surat'])) {
        $file_path = $surat['file_surat'];
        if (file_exists($file_path)) {
            unlink($file_path); // Hapus file dari folder
        }
    }

    // Catat penghapusan di log
    $stmt = $pdo->prepare("INSERT INTO log_hapus_surat (admin_id, surat_id, kategori_surat) VALUES (?, ?, 'Surat Masuk')");
    $stmt->execute([$admin_id, $delete_id]);

    // Hapus data dari database
    $stmt = $pdo->prepare("DELETE FROM surat WHERE id = ?");
    $stmt->execute([$delete_id]);

    //$_SESSION['message'] = "Surat Masuk berhasil dihapus dan dicatat dalam log.";
    header('Location:index.php');
    exit();
}

// Ambil data dari database untuk ditampilkan
$stmt = $pdo->query("SELECT * FROM surat");
$surat_data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style\styles.css">
    <style>
    .logo-container {
    display: flex;
    align-items: center;
    justify-content: center;
    padding-bottom: 10px;
    gap: 10px;
    }

    .logo {
        width: 130px; /* Pastikan ukuran kecil */
        height: auto;
        max-width: 100%;
    }
    </style>
    <title>Admin Surat</title>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
    <div class="logo-container">
    <img src="logo\Gambar_WhatsApp_2025-03-09_pukul_22.23.47_64dd9a5b-removebg-preview.png" alt="BBPSDMP Medan Logo" class="logo">
        </div>
            <div class="profile">
                <img src="<?php echo $photoPath; ?>" alt="Foto Admin" width="100">
                <h3>Hi, <?= htmlspecialchars($_SESSION['admin_name']) ?></h3>
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
            <a href="log_hapus.php">
                <i class="fas fa-history"></i> Surat Dihapus
            </a>
            </a>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>


    <!-- Content -->
    <div class="content">
        <div class="header">
            <h2>Surat Masuk</h2>
            <a href="javascript:void(0);" class="btn btn-view" id="openModalBtn">+ Add New Surat Masuk</a>
        </div>
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
            <?php foreach ($surat_data as $surat): ?>
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

                <!-- Kolom Action: Membungkus tombol-tombol di dalam div action-buttons -->
                <td>
                    <div class="action-buttons">
                        <a href="edittabel.php?id=<?= $surat['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="index.php?delete_id=<?= $surat['id'] ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        </table>
    </div>

    <!-- Add New Surat Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h3>Tambah Surat Masuk</h3>
            <form method="post" enctype="multipart/form-data">
            <div style="position: relative;">
                <input type="text" id="nomor_surat" name="nomor_surat" placeholder="Nomor Surat" required>
                <i id="alert-icon" class="fas fa-exclamation-circle" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: red; display: none;"></i>
            </div>
            <small id="warning-text" style="color: red; display: none;">Nomor surat sudah digunakan!</small>
                <input type="text" name="satuan_kerja" placeholder="Satuan Kerja" required>
                <input type="text" name="kode_klasifikasi" placeholder="Kode Klasifikasi" required>
                <input type="text" name="sub_klasifikasi" placeholder="Sub Klasifikasi" required>
                <input type="text" name="bulan" placeholder="Bulan" required>
                <input type="number" name="tahun" placeholder="Tahun" required>
                <input type="text" name="nomor_keluar" placeholder="Nomor Keluar" required>
                <input type="date" name="tanggal_surat" required>
                <textarea name="ringkasan_isi_surat" placeholder="Ringkasan Isi Surat" required></textarea>
                
                <!-- Input untuk meng-upload file -->
                <input type="file" name="file_surat" accept=".pdf,.doc,.docx,.jpg,.png" required>

                <button type="submit" name="create">Tambah Surat</button>
            </form>
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
        <p>&copy; 2025 Arsip Surat - Nadia Aprilia Siregar</p>
    </footer>

    <script>
        document.getElementById("nomor_surat").addEventListener("input", function() {
        let nomorInput = this.value.trim();
        let existingNumbers = Array.from(document.querySelectorAll("table tr td:first-child"))
                                    .map(td => td.textContent.trim());
        let alertIcon = document.getElementById("alert-icon");
        let warningText = document.getElementById("warning-text");

        if (existingNumbers.includes(nomorInput)) {
            alertIcon.style.display = "inline"; // Tampilkan tanda seru
            warningText.style.display = "block"; // Tampilkan teks peringatan
        } else {
            alertIcon.style.display = "none"; // Sembunyikan tanda seru
            warningText.style.display = "none"; // Sembunyikan teks peringatan
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

        // Get the modal
        var modal = document.getElementById("addModal");

        // Get the button that opens the modal
        var btn = document.getElementById("openModalBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close-btn")[0];

        // When the user clicks on the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

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
                filePreview.innerHTML = `<iframe src="${filePath}" width="100%" height="500px" style="border: none;"></iframe>`;
            } else {
                filePreview.innerHTML = `<p>File type is not supported for preview. <a href="${filePath}" target="_blank">Download file</a></p>`;
            }
        }

        function closeModal() {
            var modal = document.getElementById('fileModal');
            modal.style.display = "none";
        }
    </script>

</body>
</html>
