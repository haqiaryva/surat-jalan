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
    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-users"></i>
        </div>
        <h3><?php echo $totalPelanggan; ?></h3>
        <p>Total Pelanggan</p>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        <h3><?php echo $totalSuratJalan; ?></h3>
        <p>Total Surat Jalan</p>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <h3><?php echo $suratBulanIni; ?></h3>
        <p>Surat Bulan Ini</p>
    </div>
</div>

<div class="card">
    <h2><i class="fas fa-chart-line"></i> Dashboard</h2>
    <p style="color: var(--text-secondary); font-size: 1.05rem;">Selamat datang di Sistem Otomatisasi Surat Jalan. Pilih menu di atas untuk memulai.</p>
    
    <h3 style="margin-top: 30px; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-clock" style="color: var(--primary-color);"></i> Surat Jalan Terbaru</h3>
    <?php
    $query = "SELECT sj.*, p.nama_pelanggan 
              FROM surat_jalan sj 
              LEFT JOIN pelanggan p ON sj.id_pelanggan = p.id 
              ORDER BY sj.created_at DESC LIMIT 5";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        echo "<div class='table-responsive'>";
        echo "<table>";
        echo "<tr><th>No. Surat</th><th>Tanggal</th><th>Pelanggan</th><th>Tujuan</th><th>Aksi</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($row['no_surat']) . "</strong></td>";
            echo "<td><i class='far fa-calendar'></i> " . date('d/m/Y', strtotime($row['tanggal'])) . "</td>";
            echo "<td><i class='far fa-building'></i> " . htmlspecialchars($row['nama_pelanggan']) . "</td>";
            echo "<td><i class='fas fa-map-marker-alt'></i> " . htmlspecialchars($row['tujuan']) . "</td>";
            echo "<td>
                    <div class='table-actions'>
                        <a href='pages/surat-jalan/print.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm' target='_blank' title='Cetak surat jalan'><i class='fas fa-print'></i></a>
                    </div>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p style='text-align: center; padding: 2rem; color: var(--text-secondary);'><i class='fas fa-inbox' style='font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.2;'></i>Belum ada surat jalan.</p>";
    }
    ?>
</div>

<?php include 'template/footer.php'; ?>