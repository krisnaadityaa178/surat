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

    // Cek apakah username dan password cocok (tanpa hashing)
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $admin = $stmt->fetch();

    if ($admin) {
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: index.php');
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
    <!-- Add the CSS file or include the CSS in a style tag -->

    <style>
        /* Global Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #2a3f54; /* Dark blue background matching the sidebar */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Container for the Login form */
.login-container {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    width: 400px;
    padding: 30px;
    text-align: center;
}

/* Logo or avatar area */
.login-avatar {
    width: 100px;
    height: 100px;
    margin: 0 auto 20px;
    background-color: #96bf48; /* Green from the avatar in the image */
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 5px solid #e67e22; /* Orange border from the avatar */
}

.login-avatar i {
    font-size: 50px;
    color: white;
}

/* Title Style */
.login-title {
    color: #2a3f54; /* Dark blue matching the sidebar */
    font-size: 24px;
    margin-bottom: 30px;
    font-weight: bold;
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: bold;
}

/* Input Fields */
.form-control {
    width: 100%;
    padding: 12px;
    border-radius: 4px;
    border: 1px solid #ddd;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.form-control:focus {
    border-color: #1a82c3; /* Blue color when focused */
    outline: none;
    box-shadow: 0 0 5px rgba(26, 130, 195, 0.3);
}

/* Button Style */
.btn-login {
    width: 100%;
    padding: 12px;
    background-color: #1a82c3; /* Blue button color from the View buttons */
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-weight: bold;
    margin-top: 10px;
}

.btn-login:hover {
    background-color: #0062a3; /* Darker blue on hover */
}

/* Error message styling */
.error-message {
    color: #e74c3c; /* Red color for error */
    font-size: 14px;
    margin-top: 15px;
    text-align: center;
}

/* Footer */
.login-footer {
    margin-top: 30px;
    color: #555;
    font-size: 14px;
}

/* Copyright text */
.copyright {
    margin-top: 20px;
    color: #999;
    font-size: 12px;
}
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="login-title">Admin Login</div>
        
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
        </form>
        
        <div class="copyright">
            © 2025 Arsip Surat - All Rights Reserved
        </div>
    </div>
</body>
</html>
