<?php
include '../../template/header.php';

$db = new Database();
$conn = $db->connect();

$id = $_GET['id'];

// Ambil data surat jalan
$query = "SELECT * FROM surat_jalan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$surat = $stmt->get_result()->fetch_assoc();

// Ambil detail barang
$detailQuery = "SELECT * FROM detail_surat_jalan WHERE id_surat_jalan = ?";
$stmtDetail = $conn->prepare($detailQuery);
$stmtDetail->bind_param("i", $id);
$stmtDetail->execute();
$details = $stmtDetail->get_result();

// Ambil data pelanggan untuk dropdown
$pelangganQuery = "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC";
$pelangganResult = $conn->query($pelangganQuery);
?>

<div class="card">
    <h2>Edit Surat Jalan</h2>
    
    <form action="../../process/surat_jalan_process.php" method="POST">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo $surat['id'] ?? ''; ?>">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>No. Surat *</label>
                <input type="text" value="<?php echo $surat['no_surat'] ?? ''; ?>" readonly style="background: #ecf0f1;">
            </div>
            
            <div class="form-group">
                <label>Tanggal *</label>
                <input type="date" name="tanggal" value="<?php echo $surat['tanggal'] ?? ''; ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Pelanggan *</label>
            <select name="id_pelanggan" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php while ($pelanggan = $pelangganResult->fetch_assoc()): ?>
                    <option value="<?php echo $pelanggan['id']; ?>" 
                            <?php echo ($pelanggan['id'] == ($surat['id_pelanggan'] ?? '')) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($pelanggan['nama_pelanggan']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Tujuan Pengiriman *</label>
            <textarea name="tujuan" required><?php echo htmlspecialchars($surat['tujuan'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan"><?php echo htmlspecialchars($surat['keterangan'] ?? ''); ?></textarea>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <h3>Detail Barang</h3>
        <button type="button" onclick="addBarangRow()" class="btn btn-primary" style="margin-bottom: 10px;">+ Tambah Barang</button>
        
        <table id="barangTable" style="font-size: 14px;">
            <tr>
                <th width="50">No</th>
                <th>Nama Barang</th>
                <th width="100">Jumlah</th>
                <th width="100">Satuan</th>
                <th width="100">Berat (Kg)</th>
                <th width="150">Keterangan</th>
                <th width="80">Aksi</th>
            </tr>
            <?php 
            $no = 1;
            while ($detail = $details->fetch_assoc()): 
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><input type="text" name="nama_barang[]" value="<?php echo htmlspecialchars($detail['nama_barang']); ?>" required style="width: 100%; padding: 5px;"></td>
                <td><input type="number" name="jumlah[]" value="<?php echo $detail['jumlah']; ?>" required style="width: 100%; padding: 5px;"></td>
                <td><input type="text" name="satuan[]" value="<?php echo htmlspecialchars($detail['satuan']); ?>" required style="width: 100%; padding: 5px;"></td>
                <td><input type="number" step="0.01" name="berat[]" value="<?php echo $detail['berat']; ?>" style="width: 100%; padding: 5px;"></td>
                <td><input type="text" name="ket_barang[]" value="<?php echo htmlspecialchars($detail['keterangan']); ?>" style="width: 100%; padding: 5px;"></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Hapus</button></td>
            </tr>
            <?php endwhile; ?>
        </table>
        
        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">💾 Update Surat Jalan</button>
            <a href="list.php" class="btn btn-danger">Batal</a>
        </div>
    </form>
</div>

<style>
    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }
    #barangTable input {
        border: 1px solid #ddd;
        border-radius: 3px;
    }
</style>

<?php include '../../template/footer.php'; ?>
