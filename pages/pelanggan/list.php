<?php
include '../../template/header.php';

$db = new Database();
$conn = $db->connect();

// Ambil semua pelanggan
$query = "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC";
$result = $conn->query($query);
?>

<div class="card">
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-users"></i> Data Pelanggan</h2>
        <div class="page-actions">
            <a href="add.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Tambah Pelanggan</a>
        </div>
    </div>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <script>
            window.onload = function() {
                showAlert('Berhasil!', 'Data pelanggan berhasil disimpan.', 'success');
            };
        </script>
    <?php endif; ?>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
        <script>
            window.onload = function() {
                showAlert('Berhasil Diperbarui!', 'Data pelanggan berhasil diperbarui.', 'success');
            };
        </script>
    <?php endif; ?>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <script>
            window.onload = function() {
                showAlert('Berhasil Dihapus!', 'Data pelanggan berhasil dihapus.', 'success');
            };
        </script>
    <?php endif; ?>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
        <script>
            window.onload = function() {
                showAlert('Gagal!', 'Terjadi kesalahan saat memproses data. Silakan coba lagi.', 'error');
            };
        </script>
    <?php endif; ?>
    
    <div class="table-responsive">
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
                echo "<td><strong style='color: var(--primary-color);'>" . htmlspecialchars($row['nama_pelanggan']) . "</strong></td>";
                echo "<td><i class='fas fa-map-marker-alt'></i> " . htmlspecialchars($row['alamat']) . "</td>";
                echo "<td><i class='fas fa-phone'></i> " . htmlspecialchars($row['telepon']) . "</td>";
                echo "<td><i class='fas fa-envelope'></i> " . htmlspecialchars($row['email']) . "</td>";
                echo "<td>
                        <div class='table-actions'>
                            <a href='edit.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm' title='Edit data'><i class='fas fa-edit'></i></a>
                            <a href='../../process/pelanggan_process.php?action=delete&id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirmDelete()' title='Hapus data'><i class='fas fa-trash'></i></a>
                        </div>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'><div class='empty-state'><i class='fas fa-user-slash'></i><p>Belum ada data pelanggan</p></div></td></tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../../template/footer.php'; ?>