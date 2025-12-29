<?php
require_once 'database.php';

$db = new Database();
$conn = $db->connect();

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql)) {
    echo "✅ Tabel users berhasil dibuat!<br>";
    
    // Insert data default
    $username = "admin";
    $password = password_hash("admin123", PASSWORD_DEFAULT);
    $nama_lengkap = "Administrator";
    $role = "admin";
    
    $check = $conn->query("SELECT * FROM users WHERE username = 'admin'");
    if ($check->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $password, $nama_lengkap, $role);
        
        if ($stmt->execute()) {
            echo "✅ User default berhasil dibuat!<br>";
            echo "<br><strong>Login Credentials:</strong><br>";
            echo "Username: <strong>admin</strong><br>";
            echo "Password: <strong>admin123</strong><br>";
        } else {
            echo "❌ Error insert user: " . $conn->error . "<br>";
        }
    } else {
        echo "ℹ️ User admin sudah ada.<br>";
    }
} else {
    echo "❌ Error: " . $conn->error . "<br>";
}

echo "<br><a href='../login.php'>Login Sekarang</a>";
?>
