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

$conn->query($sql1);
$conn->query($sql2);
$conn->query($sql3);
$conn->query($sql4);

// Insert data perusahaan default
$check = $conn->query("SELECT * FROM perusahaan LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO perusahaan (nama_perusahaan, alamat, telepon, email) 
                  VALUES ('PT. Contoh Jaya', 'Jl. Contoh No. 123', '021-12345678', 'info@contohjaya.com')");
}

echo "Database dan tabel berhasil dibuat!<br>";
echo "<a href='../index.php'>Kembali ke Dashboard</a>";
?>