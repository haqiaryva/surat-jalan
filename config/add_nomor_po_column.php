<?php
require_once 'database.php';

$db = new Database();
$conn = $db->connect();

// Cek apakah kolom nomor_po sudah ada
$check = $conn->query("SHOW COLUMNS FROM surat_jalan LIKE 'nomor_po'");

if ($check->num_rows == 0) {
    // Tambahkan kolom nomor_po
    $sql = "ALTER TABLE surat_jalan ADD COLUMN nomor_po VARCHAR(100) AFTER no_surat";
    
    if ($conn->query($sql)) {
        echo "✅ Kolom nomor_po berhasil ditambahkan ke tabel surat_jalan!<br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }
} else {
    echo "Kolom nomor_po sudah ada di tabel surat_jalan.<br>";
}

echo "<br><a href='../index.php'>Kembali ke Dashboard</a>";
?>
