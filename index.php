<?php
include 'template/header.php';

$db = new Database();
$conn = $db->connect();

// Statistik
$totalPelanggan = $conn->query("SELECT COUNT(*) as total FROM pelanggan")->fetch_assoc()['total'];
$totalSuratJalan = $conn->query("SELECT COUNT(*) as total FROM surat_jalan")->fetch_assoc()['total'];
$suratBulanIni = $conn->query("SELECT COUNT(*) as total FROM surat_jalan WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())")->fetch_assoc()['total'];
?>

<div class="dashboard-stats">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #100C08 100%);">
        <h3><?php echo $totalPelanggan; ?></h3>
        <p>Total Pelanggan</p>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #100C08 100%);">
        <h3><?php echo $totalSuratJalan; ?></h3>
        <p>Total Surat Jalan</p>
    </div>
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #100C08 100%);">
        <h3><?php echo $suratBulanIni; ?></h3>
        <p>Surat Bulan Ini</p>
    </div>
</div>

<div class="card">
    <h2>Dashboard</h2>
    <p>Selamat datang di Sistem Otomatisasi Surat Jalan. Pilih menu di atas untuk memulai.</p>
    
    <h3 style="margin-top: 30px;">Surat Jalan Terbaru</h3>
    <?php
    $query = "SELECT sj.*, p.nama_pelanggan 
              FROM surat_jalan sj 
              LEFT JOIN pelanggan p ON sj.id_pelanggan = p.id 
              ORDER BY sj.created_at DESC LIMIT 5";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>No. Surat</th><th>Tanggal</th><th>Pelanggan</th><th>Tujuan</th><th>Aksi</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['no_surat'] . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['tanggal'])) . "</td>";
            echo "<td>" . $row['nama_pelanggan'] . "</td>";
            echo "<td>" . $row['tujuan'] . "</td>";
            echo "<td>
                    <a href='pages/surat-jalan/print.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm' target='_blank'>Cetak</a>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Belum ada surat jalan.</p>";
    }
    ?>
</div>

<?php include 'template/footer.php'; ?>