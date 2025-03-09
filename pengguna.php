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


// Ambil data admin berdasarkan session
$stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

// Gunakan foto default jika admin belum mengunggah foto
$photoPath = $admin['photo'] ? "uploads/" . $admin['photo'] : "uploads/default.png";


// Fungsi untuk menambah pengguna
if (isset($_POST['create_user'])) {
    $admin_name = $_POST['admin_name'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Simpan password dalam bentuk teks biasa (tanpa hash)

    // Set default role sebagai 'admin'
    $role = 'admin';
    // Menyimpan data pengguna ke database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admin (admin_name, username, password) VALUES (?, ?, ?)");
    $stmt->execute([$admin_name, $username, $hashedPassword]);

    // Redirect ke halaman yang sama setelah data disimpan
    header('Location: pengguna.php');
    exit();
}


if (isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $admin_name = $_POST['admin_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data admin lama untuk memeriksa apakah ada foto yang perlu dihapus
    $stmt = $pdo->prepare("SELECT photo FROM admin WHERE id = ?");
    $stmt->execute([$user_id]);
    $admin = $stmt->fetch();
    $oldPhoto = $admin['photo'];

    // Proses upload foto jika ada
    $photo = $oldPhoto;
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "uploads/";
        $newPhoto = time() . "_" . basename($_FILES['photo']['name']);
        $targetFilePath = $targetDir . $newPhoto;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
            // Hapus foto lama jika ada
            if ($oldPhoto && file_exists($targetDir . $oldPhoto)) {
                unlink($targetDir . $oldPhoto);
            }
            $photo = $newPhoto;
        }
    }

    // Update password hanya jika diisi
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admin SET admin_name = ?, username = ?, password = ?, photo = ? WHERE id = ?");
        $stmt->execute([$admin_name, $username, $hashedPassword, $photo, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE admin SET admin_name = ?, username = ?, photo = ? WHERE id = ?");
        $stmt->execute([$admin_name, $username, $photo, $user_id]);
    }

    header('Location: pengguna.php');
    exit();
}

// Ambil data pengguna dari database
$stmt = $pdo->query("SELECT * FROM admin");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style\styles.css">
    <title>Admin - Pengguna</title>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
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
            <a href="pengaturan.php">
                <i class="fas fa-cogs"></i> Pengaturan
            </a>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

    <!-- Content -->
    <div class="content">
        <div class="header">
            <h2>Pengguna</h2>
            <a href="javascript:void(0);" class="btn btn-view" id="openModalBtn">+ Add New User</a>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>Administrator</th>
                <th>Username</th>
                <th>Password</th>
                <th>Picture</th><!-- Menambahkan kolom untuk password -->
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['admin_name'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['password'] ?></td>
                <td><?= $user['photo']?></td>
                <!-- Menampilkan password -->
                <td>
            <a href="javascript:void(0);" onclick="openEditModal(<?= $user['id'] ?>, '<?= $user['admin_name'] ?>', '<?= $user['username'] ?>')" class="btn btn-edit">Edit</a>
            <a href="pengguna.php?delete_user=<?= $user['id'] ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
        </table>
    </div>

    <!-- Add New User Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h3>Tambah Pengguna</h3>
            <form method="POST" enctype="multipart/form-data">
            <input type="text" name="admin_name" placeholder="Nama Admin" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="file" name="photo" accept="image/*">
            <button type="submit" name="create_user">Tambah Pengguna</button>
        </form>

        </div>
    </div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Edit Pengguna</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" id="edit_user_id" name="user_id">
            <input type="text" id="edit_admin_name" name="admin_name" placeholder="Admin Name" required>
            <input type="text" id="edit_username" name="username" placeholder="Username" required>
            <input type="password" id="edit_password" name="password" placeholder="Password (Kosongkan jika tidak ingin mengubah)">
            
            <!-- Input untuk unggah foto -->
            <input type="file" name="photo" accept="image/*">
            
            <button type="submit" name="edit_user">Update Pengguna</button>
        </form>
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
        // Get the modal
        var modal = document.getElementById("addModal");
        var editModal = document.getElementById("editModal");

        // Get the button that opens the modal
        var btn = document.getElementById("openModalBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close-btn");

        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        for (var i = 0; i < span.length; i++) {
            span[i].onclick = function() {
                modal.style.display = "none";
                editModal.style.display = "none";
            }
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal || event.target == editModal) {
                modal.style.display = "none";
                editModal.style.display = "none";
            }
        }

        // Function to open edit modal and fill data
        function openEditModal(id, admin_name, username) {
        document.getElementById("edit_user_id").value = id;
        document.getElementById("edit_admin_name").value = admin_name;
        document.getElementById("edit_username").value = username;
        editModal.style.display = "block";
    }

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
                // Simulasikan proses edit selesai (misalnya, setelah redirect)
        setTimeout(function() {
            hideLoading();
        }, 2000); // Ganti dengan logika asli Anda
            });
        });
    </script>
</body>
</html>
