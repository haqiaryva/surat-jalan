<?php
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->connect();

$action = $_POST['action'] ?? $_GET['action'];

if ($action == 'add') {
    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    
    $stmt = $conn->prepare("INSERT INTO pelanggan (nama_pelanggan, alamat, telepon, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $alamat, $telepon, $email);
    
    if ($stmt->execute()) {
        header("Location: ../pages/pelanggan/list.php?msg=success");
    } else {
        header("Location: ../pages/pelanggan/list.php?msg=error");
    }
    exit();
}

if ($action == 'edit') {
    $id = $_POST['id'];
    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    
    $stmt = $conn->prepare("UPDATE pelanggan SET nama_pelanggan=?, alamat=?, telepon=?, email=? WHERE id=?");
    $stmt->bind_param("ssssi", $nama, $alamat, $telepon, $email, $id);
    
    if ($stmt->execute()) {
        header("Location: ../pages/pelanggan/list.php?msg=updated");
    } else {
        header("Location: ../pages/pelanggan/list.php?msg=error");
    }
    exit();
}

if ($action == 'delete') {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM pelanggan WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../pages/pelanggan/list.php?msg=deleted");
    } else {
        header("Location: ../pages/pelanggan/list.php?msg=error");
    }
    exit();
}
?>

// ============================================
// FILE: pages/surat-jalan/list.php
// ============================================
<?php
include '../../includes/header.php';

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
    <h2>📋 Daftar Surat Jalan</h2>
    <a href="add.php" class="btn btn-success">+ Buat Surat Jalan Baru</a>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert alert-success">Surat jalan berhasil disimpan!</div>
    <?php endif; ?>
    
    <!-- Filter -->
    <div style="background: #ecf0f1; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
            <input type="text" name="no_surat" placeholder="No. Surat" value="<?php echo $_GET['no_surat'] ?? ''; ?>" style="padding: 8px;">
            <input type="text" name="pelanggan" placeholder="Nama Pelanggan" value="<?php echo $_GET['pelanggan'] ?? ''; ?>" style="padding: 8px;">
            <input type="date" name="tanggal_dari" value="<?php echo $_GET['tanggal_dari'] ?? ''; ?>" style="padding: 8px;">
            <input type="date" name="tanggal_sampai" value="<?php echo $_GET['tanggal_sampai'] ?? ''; ?>" style="padding: 8px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="list.php" class="btn btn-danger">Reset</a>
        </form>
    </div>
    
    <table>
        <tr>
            <th>No</th>
            <th>No. Surat</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Tujuan</th>
            <th>Kendaraan</th>
            <th>Aksi</th>
        </tr>
        <?php 
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td><strong>" . $row['no_surat'] . "</strong></td>";
                echo "<td>" . date('d/m/Y', strtotime($row['tanggal'])) . "</td>";
                echo "<td>" . htmlspecialchars($row['nama_pelanggan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['tujuan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['kendaraan']) . "</td>";
                echo "<td>
                        <a href='print.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm' target='_blank'>Cetak</a>
                        <a href='edit.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                        <a href='../../process/surat_jalan_process.php?action=delete&id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirmDelete()'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Belum ada data surat jalan</td></tr>";
        }
        ?>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>