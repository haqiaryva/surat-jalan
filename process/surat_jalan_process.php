<?php
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->connect();

$action = $_POST['action'] ?? $_GET['action'];

if ($action == 'add') {
    $conn->begin_transaction();
    
    try {
        // Insert surat jalan
        $no_surat = $_POST['no_surat'];
        $tanggal = $_POST['tanggal'];
        $id_pelanggan = $_POST['id_pelanggan'];
        $tujuan = $_POST['tujuan'];
        // $kendaraan = $_POST['kendaraan'];
        // $sopir = $_POST['sopir'];
        $keterangan = $_POST['keterangan'];
        
        $stmt = $conn->prepare("INSERT INTO surat_jalan (no_surat, tanggal, id_pelanggan, tujuan, keterangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $no_surat, $tanggal, $id_pelanggan, $tujuan, $keterangan);
        $stmt->execute();
        
        $id_surat_jalan = $conn->insert_id;
        
        // Insert detail barang
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $satuan = $_POST['satuan'];
        $berat = $_POST['berat'];
        $ket_barang = $_POST['ket_barang'];
        
        $stmt2 = $conn->prepare("INSERT INTO detail_surat_jalan (id_surat_jalan, nama_barang, jumlah, satuan, berat, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
        
        for ($i = 0; $i < count($nama_barang); $i++) {
            $stmt2->bind_param("isisds", $id_surat_jalan, $nama_barang[$i], $jumlah[$i], $satuan[$i], $berat[$i], $ket_barang[$i]);
            $stmt2->execute();
        }
        
        $conn->commit();
        header("Location: ../pages/surat-jalan/print.php?id=" . $id_surat_jalan);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

if ($action == 'edit') {
    $conn->begin_transaction();
    
    try {
        $id = $_POST['id'];
        $tanggal = $_POST['tanggal'];
        $id_pelanggan = $_POST['id_pelanggan'];
        $tujuan = $_POST['tujuan'];
        $keterangan = $_POST['keterangan'];
        
        // Update surat jalan
        $stmt = $conn->prepare("UPDATE surat_jalan SET tanggal=?, id_pelanggan=?, tujuan=?,keterangan=? WHERE id=?");
        $stmt->bind_param("sissi", $tanggal, $id_pelanggan, $tujuan, $keterangan, $id);
        $stmt->execute();
        
        // Hapus detail lama
        $conn->query("DELETE FROM detail_surat_jalan WHERE id_surat_jalan = $id");
        
        // Insert detail baru
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $satuan = $_POST['satuan'];
        $berat = $_POST['berat'];
        $ket_barang = $_POST['ket_barang'];
        
        $stmt2 = $conn->prepare("INSERT INTO detail_surat_jalan (id_surat_jalan, nama_barang, jumlah, satuan, berat, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
        
        for ($i = 0; $i < count($nama_barang); $i++) {
            $stmt2->bind_param("isisds", $id, $nama_barang[$i], $jumlah[$i], $satuan[$i], $berat[$i], $ket_barang[$i]);
            $stmt2->execute();
        }
        
        $conn->commit();
        header("Location: ../pages/surat-jalan/list.php?msg=success");
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

if ($action == 'delete') {
    $id = $_GET['id'];
    
    // Detail akan terhapus otomatis karena ON DELETE CASCADE
    $stmt = $conn->prepare("DELETE FROM surat_jalan WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../pages/surat-jalan/list.php?msg=deleted");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>