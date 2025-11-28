<?php
session_start();
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Surat Jalan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .navbar h1 {
            display: inline-block;
            font-size: 24px;
        }
        .navbar nav {
            display: inline-block;
            float: right;
            margin-top: 5px;
        }
        .navbar nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .navbar nav a:hover {
            background: #34495e;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card h2 {
            margin-bottom: 20px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-sm {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 3px;
        }
        /* .btn-lg {
            padding: 14px 28px;
            font-size: 16px;
            border-radius: 6px;
        } */
        .btn-primary {
            background: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background: #2980b9;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .btn-success:hover {
            background: #229954;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background: #e67e22;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #34495e;
            color: white;
        }
        table tr:hover {
            background: #f5f5f5;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #2c3e50;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .stat-card h3 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .stat-card p {
            font-size: 16px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>CV. Panca Karya Nova</h1>
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
        ?>
        <nav>
            <a href="<?php echo $basePath; ?>index.php">Dashboard</a>
            <a href="<?php echo $pelangganPath; ?>">Pelanggan</a>
            <a href="<?php echo $suratJalanPath; ?>">Surat Jalan</a>
            <!-- <a href="<?php echo str_replace('list.php', 'add.php', $suratJalanPath); ?>">Buat Baru</a> -->
        </nav>
    </div>
    <div class="container">