<?php
include '../../template/header.php';

$db = new Database();
$conn = $db->connect();

// Generate nomor surat otomatis
function generateNoSurat($conn) {
    $tahun = date('Y');
    $bulan = date('m');
    $prefix = "SJ/$tahun/$bulan/";
    
    $query = "SELECT no_surat FROM surat_jalan WHERE no_surat LIKE ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $search = $prefix . "%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastNo = $row['no_surat'];
        $parts = explode('/', $lastNo);
        $urut = intval(end($parts)) + 1;
    } else {
        $urut = 1;
    }
    
    return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
}

$noSurat = generateNoSurat($conn);

// Ambil data pelanggan untuk dropdown
$pelangganQuery = "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC";
$pelangganResult = $conn->query($pelangganQuery);
?>

<div class="card">
    <h2><i class="fas fa-file-medical"></i> Buat Surat Jalan Baru</h2>
    
    <form action="../../process/surat_jalan_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label><i class="fas fa-hashtag"></i> No. Surat *</label>
                <input type="text" name="no_surat" value="<?php echo $noSurat; ?>" readonly style="background: var(--light-bg); cursor: not-allowed;">
            </div>
            
            <div class="form-group">
                <label><i class="far fa-calendar-alt"></i> Tanggal *</label>
                <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-file-invoice"></i> Nomor PO *</label>
            <input type="text" name="nomor_po" placeholder="Masukkan nomor PO" required>
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-building"></i> Pelanggan *</label>
            <select name="id_pelanggan" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php while ($pelanggan = $pelangganResult->fetch_assoc()): ?>
                    <option value="<?php echo $pelanggan['id']; ?>">
                        <?php echo htmlspecialchars($pelanggan['nama_pelanggan']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-map-marked-alt"></i> Tujuan Pengiriman *</label>
            <textarea name="tujuan" required placeholder="Masukkan alamat tujuan pengiriman"></textarea>
        </div>
        
        <!-- <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Kendaraan *</label>
                <input type="text" name="kendaraan" placeholder="Ex: B 1234 ABC" required>
            </div>
            
            <div class="form-group">
                <label>Nama Sopir *</label>
                <input type="text" name="sopir" required>
            </div>
        </div> -->
        
        <div class="form-group">
            <label><i class="fas fa-sticky-note"></i> Keterangan</label>
            <textarea name="keterangan" placeholder="Keterangan tambahan (opsional)"></textarea>
        </div>
        
        <hr style="margin: 30px 0; border: none; border-top: 2px dashed var(--border-color);">
        
        <h3 style="display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-boxes" style="color: var(--primary-color);"></i> Detail Barang</h3>
        <br>
        <button type="button" onclick="addBarangRow()" class="btn btn-primary" style="margin-bottom: 15px;"><i class="fas fa-plus-circle"></i> Tambah Barang</button>
        
        <table id="barangTable" style="font-size: 14px;">
            <tr>
                <th width="50">No</th>
                <th>Nama Barang</th>
                <th width="100">Jumlah</th>
                <th width="100">Satuan</th>
                <!-- <th width="100">Berat (Kg)</th> -->
                <th width="150">Keterangan</th>
                <th width="80">Aksi</th>
            </tr>
            <tr>
                <td>1</td>
                <td><input type="text" name="nama_barang[]" required style="width: 100%; padding: 5px;"></td>
                <td><input type="number" name="jumlah[]" required style="width: 100%; padding: 5px;"></td>
                <td><input type="text" name="satuan[]" required style="width: 100%; padding: 5px;" placeholder="Pcs/Dus"></td>
                <!-- <td><input type="number" step="0.01" name="berat[]" style="width: 100%; padding: 5px;"></td> -->
                <td><input type="text" name="ket_barang[]" style="width: 100%; padding: 5px;"></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Hapus</button></td>
            </tr>
        </table>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Surat Jalan</button>
            <a href="list.php" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>

<style>
    #barangTable input {
        border: 2px solid var(--border-color);
        border-radius: 6px;
    }
    /* Make JS-added inputs match the first row styling */
    .form-control-sm {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 2px solid var(--border-color);
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 0.875rem;
        font-family: inherit;
        transition: all 0.3s ease;
    }
    .form-control-sm:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
</style>

<?php include '../../template/footer.php'; ?>