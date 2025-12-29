<?php
include '../../template/header.php';

$db = new Database();
$conn = $db->connect();

// Filter
$where = "1=1";
$params = [];
$types = "";

if (!empty($_GET['no_surat'])) {
    $where .= " AND sj.no_surat LIKE ?";
    $params[] = "%" . $_GET['no_surat'] . "%";
    $types .= "s";
}

if (!empty($_GET['pelanggan'])) {
    $where .= " AND p.nama_pelanggan LIKE ?";
    $params[] = "%" . $_GET['pelanggan'] . "%";
    $types .= "s";
}

if (!empty($_GET['tanggal_dari'])) {
    $where .= " AND sj.tanggal >= ?";
    $params[] = $_GET['tanggal_dari'];
    $types .= "s";
}

if (!empty($_GET['tanggal_sampai'])) {
    $where .= " AND sj.tanggal <= ?";
    $params[] = $_GET['tanggal_sampai'];
    $types .= "s";
}

$query = "SELECT sj.*, p.nama_pelanggan 
          FROM surat_jalan sj 
          LEFT JOIN pelanggan p ON sj.id_pelanggan = p.id 
          WHERE $where
          ORDER BY sj.created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="card">
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-file-alt"></i> Daftar Surat Jalan</h2>
        <div class="page-actions">
            <a href="add.php" class="btn btn-success"><i class="fas fa-plus-circle"></i> Buat Surat Jalan</a>
        </div>
    </div>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <script>
            window.onload = function() {
                showAlert('Berhasil!', 'Surat jalan berhasil disimpan.', 'success');
            };
        </script>
    <?php endif; ?>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
        <script>
            window.onload = function() {
                showAlert('Berhasil Diperbarui!', 'Surat jalan berhasil diperbarui.', 'success');
            };
        </script>
    <?php endif; ?>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <script>
            window.onload = function() {
                showAlert('Berhasil Dihapus!', 'Surat jalan berhasil dihapus.', 'success');
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
    
    <!-- Filter -->
    <div class="filter-section">
        <h3 class="filter-title">
            <i class="fas fa-filter"></i> Filter Data
        </h3>
        <form method="GET" class="filter-grid">
            <input type="text" name="no_surat" placeholder="No. Surat" value="<?php echo $_GET['no_surat'] ?? ''; ?>">
            <input type="text" name="pelanggan" placeholder="Nama Pelanggan" value="<?php echo $_GET['pelanggan'] ?? ''; ?>">
            <input type="date" name="tanggal_dari" placeholder="Tanggal Dari" value="<?php echo $_GET['tanggal_dari'] ?? ''; ?>">
            <input type="date" name="tanggal_sampai" placeholder="Tanggal Sampai" value="<?php echo $_GET['tanggal_sampai'] ?? ''; ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
            <a href="list.php" class="btn btn-danger"><i class="fas fa-redo"></i> Reset</a>
        </form>
    </div>
    
    <div class="table-responsive">
    <table>
        <tr>
            <th>No</th>
            <th>No. Surat</th>
            <th>Nomor PO</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Tujuan</th>
            <!-- <th>Kendaraan</th> -->
            <th>Aksi</th>
        </tr>
        <?php 
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td><strong style='color: var(--primary-color);'>" . htmlspecialchars($row['no_surat']) . "</strong></td>";
                echo "<td>" . (!empty($row['nomor_po']) ? '<i class="fas fa-file-invoice"></i> ' . htmlspecialchars($row['nomor_po']) : '<span style="color: var(--text-secondary); font-style: italic;">-</span>') . "</td>";
                echo "<td><i class='far fa-calendar'></i> " . date('d/m/Y', strtotime($row['tanggal'])) . "</td>";
                echo "<td><i class='far fa-building'></i> " . htmlspecialchars($row['nama_pelanggan']) . "</td>";
                echo "<td><i class='fas fa-map-marker-alt'></i> " . htmlspecialchars($row['tujuan']) . "</td>";
                // echo "<td>" . htmlspecialchars($row['kendaraan']) . "</td>";
                echo "<td>
                        <div class='table-actions'>
                            <a href='print.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm' target='_blank' title='Cetak surat jalan'><i class='fas fa-print'></i></a>
                            <a href='edit.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm' title='Edit data'><i class='fas fa-edit'></i></a>
                            <a href='../../process/surat_jalan_process.php?action=delete&id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirmDelete()' title='Hapus data'><i class='fas fa-trash'></i></a>
                        </div>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'><div class='empty-state'><i class='fas fa-inbox'></i><p>Belum ada data surat jalan</p></div></td></tr>";
        }
        ?>
    </table>
    </div>
</div>

<?php include '../../template/footer.php'; ?>