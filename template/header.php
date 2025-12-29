<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Determine redirect path
    if (strpos($_SERVER['SCRIPT_NAME'], '/pages/') !== false) {
        header("Location: ../../login.php");
    } else {
        header("Location: login.php");
    }
    exit();
}

require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Surat Jalan - CV. Panca Karya Nova</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <?php
    $cssPath = '';
    if (strpos($_SERVER['SCRIPT_NAME'], '/pages/') !== false) {
        $cssPath = '../../css/style.css';
    } else {
        $cssPath = 'css/style.css';
    }
    ?>
    <link rel="stylesheet" href="<?php echo $cssPath; ?>">
</head>
<body>
    <div class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <div class="navbar-logo">
                    <i class="fas fa-truck-fast"></i>
                </div>
                <h1>CV. Panca Karya Nova</h1>
            </div>
            <?php
            $currentScript = $_SERVER['SCRIPT_NAME'];
            
            if (strpos($currentScript, '/pages/') !== false) {
                $basePath = '../../';
                $pelangganPath = '../pelanggan/list.php';
                $suratJalanPath = '../surat-jalan/list.php';
            } else {
                $basePath = '';
                $pelangganPath = 'pages/pelanggan/list.php';
                $suratJalanPath = 'pages/surat-jalan/list.php';
            }
            
            $isDashboard = false;
            $isPelanggan = false;
            $isSuratJalan = false;
            
            if (preg_match('/^\/.*index\.php$/', $currentScript) || $currentScript === '/index.php') {
                if (strpos($currentScript, '/pages/') === false) {
                    $isDashboard = true;
                }
            }
            
            if (strpos($currentScript, 'pelanggan') !== false && strpos($currentScript, '/pages/pelanggan/') !== false) {
                $isPelanggan = true;
                $isDashboard = false;
            }
            
            if (strpos($currentScript, 'surat-jalan') !== false && strpos($currentScript, '/pages/surat-jalan/') !== false) {
                $isSuratJalan = true;
                $isDashboard = false;
            }
            ?>
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
            <nav id="mainNav">
                <a href="<?php echo $basePath; ?>index.php" class="<?php echo $isDashboard ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?php echo $pelangganPath; ?>" class="<?php echo $isPelanggan ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Pelanggan
                </a>
                <a href="<?php echo $suratJalanPath; ?>" class="<?php echo $isSuratJalan ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i> Surat Jalan
                </a>
                <div class="user-dropdown">
                    <button class="user-info" onclick="toggleUserDropdown()">
                        <i class="fas fa-user-circle"></i>
                        <span>Administrator</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" id="userDropdownMenu">
                        <div class="dropdown-item user-name">
                            <i class="fas fa-user"></i>
                            <span><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></span>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo $basePath; ?>process/logout_process.php" class="dropdown-item logout-item" onclick="showLogoutModal('<?php echo $basePath; ?>process/logout_process.php')">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
                <a href="<?php echo $basePath; ?>process/logout_process.php" class="logout-mobile" onclick="showLogoutModal('<?php echo $basePath; ?>process/logout_process.php')">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>
    </div>
    
    <script>
        function toggleMobileMenu() {
            const nav = document.getElementById('mainNav');
            const toggle = document.querySelector('.mobile-menu-toggle');
            const icon = toggle.querySelector('i');
            
            nav.classList.toggle('show');
            
            // Toggle icon
            if (nav.classList.contains('show')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('#mainNav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        const nav = document.getElementById('mainNav');
                        const toggle = document.querySelector('.mobile-menu-toggle');
                        const icon = toggle.querySelector('i');
                        
                        nav.classList.remove('show');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            });
        });
        
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('mainNav');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (!nav.contains(event.target) && !toggle.contains(event.target) && nav.classList.contains('show')) {
                const icon = toggle.querySelector('i');
                nav.classList.remove('show');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
        
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const nav = document.getElementById('mainNav');
                const toggle = document.querySelector('.mobile-menu-toggle');
                const icon = toggle.querySelector('i');
                
                nav.classList.remove('show');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    </script>
    
    <div class="container">