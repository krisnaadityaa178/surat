<!--Created By: Nadia Aprilia Siregar 
    Created Date: 2025-02-27
    this project is for BBPSDMP (Center for Human Resources Development and Research of Communication and Informatics Medan)
-->

<?php
session_start();
include('config.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username di database
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    // Verifikasi password
    if ($admin && password_verify($password, $admin['password'])) {
        // Simpan data admin di session
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['admin_name']; // Simpan nama admin di session
        header('Location: index.php');
        exit();
    } else {
        $error = "Username atau Password salah";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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

        /* Container for the Login form - SAMA DENGAN REGISTER */
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px; /* SAMA DENGAN REGISTER */
        }

        /* Avatar container - KONSISTEN DENGAN REGISTER */
        .avatar-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-avatar {
            width: 80px; /* SAMA DENGAN REGISTER */
            height: 80px; /* SAMA DENGAN REGISTER */
            border-radius: 50%;
            background: linear-gradient(135deg, #a8e6cf, #88d8a3);
            border: 4px solid #ff8a80;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }

        .login-avatar i {
            font-size: 40px; /* SAMA DENGAN REGISTER SVG SIZE */
            color: white;
        }

        /* Title Style - SAMA DENGAN REGISTER */
        .login-title {
            text-align: center;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        /* Form Group - SAMA DENGAN REGISTER */
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

        /* Input Fields - SAMA DENGAN REGISTER */
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            background: white;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        /* Button Style - SAMA DENGAN REGISTER */
        .btn-login {
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

        .btn-login:hover {
            background: #2980b9;
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        /* Link styling - SAMA DENGAN REGISTER */
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

        /* Footer - SAMA DENGAN REGISTER */
        .copyright {
            text-align: center;
            color: #bbb;
            font-size: 12px;
            margin-top: 25px;
        }

        /* Error message styling - KONSISTEN DENGAN ALERT REGISTER */
        .error-message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            text-align: center;
        }

        /* Success message styling - UNTUK PESAN SUKSES REGISTER */
        .success-message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="avatar-container">
            <div class="login-avatar">
                <i class="fas fa-user"></i>
            </div>
        </div>
        
        <div class="login-title">Admin Login</div>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" name="login" class="btn-login">LOGIN</button>

            <div class="login-link">
                <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            </div>
        </form>
        
        <div class="copyright">
            © 2025 Arsip Surat - All Rights Reserved
        </div>
    </div>
</body>
</html>