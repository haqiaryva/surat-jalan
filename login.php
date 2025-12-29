<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'invalid') {
        $error = 'Username atau password salah!';
    } elseif ($_GET['error'] == 'logout') {
        $error = 'Anda telah berhasil logout.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Surat Jalan</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --danger-color: #ef4444;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 450px;
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2.5rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.95rem;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.1rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.2);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-danger {
            background: #fee;
            color: #c00;
            border: 1px solid #fcc;
        }
        
        .alert-success {
            background: #efe;
            color: #060;
            border: 1px solid #cfc;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
            
            .login-logo {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }
        }
        
        /* Custom Modal for Login Error */
        .login-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .login-modal.show {
            opacity: 1;
            visibility: visible;
        }
        
        .login-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }
        
        .login-modal-content {
            position: relative;
            background: white;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            transition: all 0.3s ease;
        }
        
        .login-modal.show .login-modal-content {
            transform: scale(1);
        }
        
        .login-modal-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4);
        }
        
        .login-modal-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        }
        
        .login-modal-content h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }
        
        .login-modal-content p {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .login-modal-btn {
            padding: 0.875rem 2.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }
        
        .login-modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.4);
        }
    </style>
</head>
<body>
    <!-- Custom Modal for Errors -->
    <div class="login-modal" id="loginModal">
        <div class="login-modal-overlay" onclick="closeLoginModal()"></div>
        <div class="login-modal-content">
            <div class="login-modal-icon" id="modalIcon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h3 id="modalTitle">Login Gagal</h3>
            <p id="modalMessage">Username atau password yang Anda masukkan salah!</p>
            <button class="login-modal-btn" onclick="closeLoginModal()">Coba Lagi</button>
        </div>
    </div>
    
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-truck-fast"></i>
            </div>
            <h1>CV. Panca Karya Nova</h1>
            <p>Sistem Surat Jalan</p>
        </div>
        
        <form action="process/login_process.php" method="POST">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Masukkan username" required autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Login
            </button>
        </form>
        
        <div class="login-footer">
            <p>&copy; <?php echo date('Y'); ?> CV. Panca Karya Nova. All rights reserved.</p>
        </div>
    </div>
    
    <script>
        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('show');
            document.body.style.overflow = '';
            
            // Remove query parameters from URL after closing modal
            if (window.location.search) {
                const url = window.location.pathname;
                window.history.replaceState({}, document.title, url);
            }
        }
        
        // Show modal if there's an error
        <?php if (isset($_GET['error'])): ?>
            window.onload = function() {
                const modal = document.getElementById('loginModal');
                const icon = document.getElementById('modalIcon');
                const title = document.getElementById('modalTitle');
                const message = document.getElementById('modalMessage');
                
                <?php if ($_GET['error'] == 'invalid'): ?>
                    icon.innerHTML = '<i class="fas fa-times-circle"></i>';
                    icon.classList.remove('success');
                    title.textContent = 'Login Gagal';
                    message.textContent = 'Username atau password yang Anda masukkan salah!';
                <?php elseif ($_GET['error'] == 'logout'): ?>
                    icon.innerHTML = '<i class="fas fa-check-circle"></i>';
                    icon.classList.add('success');
                    title.textContent = 'Berhasil Logout';
                    message.textContent = 'Anda telah berhasil keluar dari sistem.';
                <?php endif; ?>
                
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            };
        <?php endif; ?>
    </script>
</body>
</html>
