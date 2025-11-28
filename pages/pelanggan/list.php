<?php
include '../../template/header.php';

$db = new Database();
$conn = $db->connect();

// Ambil semua pelanggan
$query = "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC";
$result = $conn->query($query);
?>

<div class="card">
    <h2>Data Pelanggan</h2>
    <a href="add.php" class="btn btn-primary">+ Tambah Pelanggan</a>
    <br><br>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert alert-success">Data berhasil disimpan!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success">Data berhasil dihapus!</div>
    <?php endif; ?>
    
    <table>
        <tr>
            <th>No</th>
            <th>Nama Pelanggan</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
        <?php 
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($row['nama_pelanggan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                echo "<td>" . htmlspecialchars($row['telepon']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>
                        <a href='edit.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='../../process/pelanggan_process.php?action=delete&id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirmDelete()'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data pelanggan</td></tr>";
        }
        ?>
    </table>
</div>

<?php include '../../template/footer.php'; ?>