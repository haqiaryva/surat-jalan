<?php
require_once 'database.php';

$db = new Database();
$conn = $db->connect();

// Buat database jika belum ada
$conn->query("CREATE DATABASE IF NOT EXISTS db_surat_jalan");
$conn->select_db("db_surat_jalan");

// Tabel perusahaan
$sql1 = "CREATE TABLE IF NOT EXISTS perusahaan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_perusahaan VARCHAR(200),
    alamat TEXT,
    telepon VARCHAR(20),
    email VARCHAR(100),
    logo VARCHAR(255)
)";

// Tabel pelanggan
$sql2 = "CREATE TABLE IF NOT EXISTS pelanggan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_pelanggan VARCHAR(200),
    alamat TEXT,
    telepon VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Tabel surat_jalan
$sql3 = "CREATE TABLE IF NOT EXISTS surat_jalan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    no_surat VARCHAR(50) UNIQUE,
    nomor_po VARCHAR(100),
    tanggal DATE,
    id_pelanggan INT,
    tujuan TEXT,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id)
)";

// Tabel detail_surat_jalan
$sql4 = "CREATE TABLE IF NOT EXISTS detail_surat_jalan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_surat_jalan INT,
    nama_barang VARCHAR(200),
    jumlah INT,
    satuan VARCHAR(50),
    berat DECIMAL(10,2),
    keterangan TEXT,
    FOREIGN KEY (id_surat_jalan) REFERENCES surat_jalan(id) ON DELETE CASCADE
)";

// Tabel users
$sql5 = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$conn->query($sql1);
$conn->query($sql2);
$conn->query($sql3);
$conn->query($sql4);
$conn->query($sql5);

// Insert data perusahaan default
$check = $conn->query("SELECT * FROM perusahaan LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO perusahaan (nama_perusahaan, alamat, telepon, email) 
                  VALUES ('PT. Contoh Jaya', 'Jl. Contoh No. 123', '021-12345678', 'info@contohjaya.com')");
}

// Insert user default
$checkUser = $conn->query("SELECT * FROM users WHERE username = 'admin'");
if ($checkUser->num_rows == 0) {
    $username = "admin";
    $password = password_hash("admin123", PASSWORD_DEFAULT);
    $nama_lengkap = "Administrator";
    $role = "admin";
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $nama_lengkap, $role);
    $stmt->execute();
}

echo "✅ Database dan tabel berhasil dibuat!<br>";
echo "✅ User default berhasil dibuat!<br>";
echo "<br><strong>Login Credentials:</strong><br>";
echo "Username: <strong>admin</strong><br>";
echo "Password: <strong>admin123</strong><br>";
echo "<br><a href='../login.php'>Login Sekarang</a>";
?>