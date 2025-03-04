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

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Ambil data surat berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM sk_masuk WHERE id = ?");
    $stmt->execute([$id]);
    $surat = $stmt->fetch();
    
    if (!$surat) {
        echo "Surat tidak ditemukan.";
        exit();
    }
}

// Proses Update Data
if (isset($_POST['update'])) {
    $nomor_surat = $_POST['nomor_surat'];
    $satuan_kerja = $_POST['satuan_kerja'];
    $kode_klasifikasi = $_POST['kode_klasifikasi'];
    $sub_klasifikasi = $_POST['sub_klasifikasi'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $nomor_keluar = $_POST['nomor_keluar'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $ringkasan_isi_surat = $_POST['ringkasan_isi_surat'];
    
    // Mengambil nama file lama untuk memastikan file lama dihapus jika file baru di-upload
    $file_lama = $surat['file_surat'];

    // Proses upload file jika ada file yang dipilih
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
        // Mengambil nama file dan mengganti spasi dengan karakter pengganti
        $file_name = basename($_FILES["file_surat"]["name"]);
        $file_name = str_replace(" ", "_", $file_name);  // Ganti spasi dengan underscore

        // Tentukan folder penyimpanan
        $target_dir = "surat_masuk/";
        $target_file = $target_dir . $file_name;
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        // Validasi tipe file yang diizinkan (misal hanya PDF dan gambar)
        $allowed_types = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
        if (in_array($file_type, $allowed_types)) {
            // Pindahkan file yang di-upload ke direktori tujuan
            if (move_uploaded_file($_FILES["file_surat"]["tmp_name"], $target_file)) {
                // Jika file berhasil di-upload, simpan nama file baru di database
                $file_surat = $target_file;

                // Hapus file lama jika ada dan file baru berhasil di-upload
                if ($file_lama && file_exists($file_lama)) {
                    unlink($file_lama);
                }
            } else {
                echo "Terjadi kesalahan saat mengupload file.";
                exit();
            }
        } else {
            echo "Tipe file tidak diizinkan.";
            exit();
        }

    } else {
        // Jika tidak ada file baru, gunakan file lama
        $file_surat = $file_lama;
    }

    // Update data ke database
    $stmt = $pdo->prepare("UPDATE sk_masuk SET nomor_surat = ?, satuan_kerja = ?, kode_klasifikasi = ?, sub_klasifikasi = ?, bulan = ?, tahun = ?, nomor_keluar = ?, tanggal_surat = ?, ringkasan_isi_surat = ?, file_surat = ? WHERE id = ?");
    $stmt->execute([$nomor_surat, $satuan_kerja, $kode_klasifikasi, $sub_klasifikasi, $bulan, $tahun, $nomor_keluar, $tanggal_surat, $ringkasan_isi_surat, $file_surat, $id]);

    // Redirect setelah update
    header('Location: sk_masuk.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\editstyles.css">
    <title>Edit Surat</title>
    <script>
        // Fungsi untuk konfirmasi sebelum update
        function confirmUpdate(event) {
            const confirmation = confirm("Apakah Anda yakin ingin mengubah data surat ini?");
            if (!confirmation) {
                event.preventDefault(); // Membatalkan submit jika tidak konfirmasi
            }
        }

        // Fungsi untuk kembali ke halaman index
        function goBack() {
            window.location.href = 'sk_masuk.php';
        }
    </script>
</head>
<body>

    <div class="content">
        <h2>Edit Surat Keputusan</h2>
        <form method="post" enctype="multipart/form-data" onsubmit="confirmUpdate(event)">
            <input type="text" name="nomor_surat" value="<?= $surat['nomor_surat'] ?>" placeholder="Nomor Surat" required>
            <input type="text" name="satuan_kerja" value="<?= $surat['satuan_kerja'] ?>" placeholder="Satuan Kerja" required>
            <input type="text" name="kode_klasifikasi" value="<?= $surat['kode_klasifikasi'] ?>" placeholder="Kode Klasifikasi" required>
            <input type="text" name="sub_klasifikasi" value="<?= $surat['sub_klasifikasi'] ?>" placeholder="Sub Klasifikasi" required>
            <input type="number" name="bulan" value="<?= $surat['bulan'] ?>" placeholder="Bulan" required>
            <input type="number" name="tahun" value="<?= $surat['tahun'] ?>" placeholder="Tahun" required>
            <input type="text" name="nomor_keluar" value="<?= $surat['nomor_keluar'] ?>" placeholder="Nomor Keluar" required>
            <input type="date" name="tanggal_surat" value="<?= $surat['tanggal_surat'] ?>" required>
            <textarea name="ringkasan_isi_surat" placeholder="Ringkasan Isi Surat" required><?= $surat['ringkasan_isi_surat'] ?></textarea>
            
            <!-- Input untuk file surat -->
            <label for="file_surat">Ubah File Surat:</label>
            <input type="file" name="file_surat" id="file_surat">
            
            <button type="submit" name="update">Update Surat</button>
        </form>

        <!-- Tombol untuk kembali ke index.php -->
        <button class="btn-back" onclick="goBack()">Kembali</button>
    </div>

</body>
</html>
