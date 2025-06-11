<?php
session_start();

// Konfigurasi database - sesuaikan dengan pengaturan Anda
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "surat_database"; // sesuaikan dengan nama database Anda

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

if ($_POST) {
    $admin_name = trim($_POST['admin_name']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $errors = [];
    
    // Validasi input
    if (empty($admin_name)) {
        $errors[] = "Nama admin tidak boleh kosong";
    }
    
    if (empty($username)) {
        $errors[] = "Username tidak boleh kosong";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username minimal 3 karakter";
    }
    
    if (empty($password)) {
        $errors[] = "Password tidak boleh kosong";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter";
    }
    
    // Cek apakah username sudah ada
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                $errors[] = "Username sudah digunakan, pilih username lain";
            }
        } catch(PDOException $e) {
            $errors[] = "Error database: " . $e->getMessage();
        }
    }
    
    // Jika tidak ada error, simpan data
    if (empty($errors)) {
        try {
            // Hash password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO admin (admin_name, username, password) VALUES (?, ?, ?)");
            $stmt->execute([$admin_name, $username, $hashed_password]);
            
            $_SESSION['success_message'] = "Registrasi berhasil! Silakan login dengan akun baru Anda.";
            header("Location: login.php");
            exit();
            
        } catch(PDOException $e) {
            $errors[] = "Error saat menyimpan data: " . $e->getMessage();
        }
    }
    
    // Jika ada error, simpan dalam session dan redirect kembali
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        $_SESSION['form_data'] = [
            'admin_name' => $admin_name,
            'username' => $username
        ];
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register - Arsip Surat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #2c3e50;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
        }

        .avatar-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a8e6cf, #88d8a3);
            border: 4px solid #ff8a80;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }

        .avatar svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        .form-title {
            text-align: center;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #555;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            background: white;
        }

        .form-group input::placeholder {
            color: #aaa;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .register-btn:hover {
            background: #2980b9;
        }

        .register-btn:active {
            transform: translateY(1px);
        }

        .footer-text {
            text-align: center;
            color: #bbb;
            font-size: 12px;
            margin-top: 25px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="avatar-container">
            <div class="avatar">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 7V9C15 9.55 14.55 10 14 10C13.45 10 13 9.55 13 9V7H11V9C11 9.55 10.55 10 10 10C9.45 10 9 9.55 9 9V7H3V9C3 13.42 6.58 17 11 17H13C17.42 17 21 13.42 21 9Z"/>
                </svg>
            </div>
        </div>
        
        <h2 class="form-title">Admin Register</h2>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="admin_name">Nama Admin</label>
                <input type="text" id="admin_name" name="admin_name" 
                        placeholder="Masukkan nama lengkap" 
                        value="<?php echo isset($_SESSION['form_data']['admin_name']) ? htmlspecialchars($_SESSION['form_data']['admin_name']) : ''; ?>" 
                        required>
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                        placeholder="Masukkan username" 
                        value="<?php echo isset($_SESSION['form_data']['username']) ? htmlspecialchars($_SESSION['form_data']['username']) : ''; ?>" 
                        required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" class="register-btn">REGISTER</button>
        </form>
        
        <div class="login-link">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
        
        <div class="footer-text">
            © 2025 Arsip Surat - All Rights Reserved
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return false;
            }
        });
    </script>
</body>
</html>

<?php
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
?>