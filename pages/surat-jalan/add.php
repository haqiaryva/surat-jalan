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
    <h2>Buat Surat Jalan Baru</h2>
    
    <form action="../../process/surat_jalan_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>No. Surat *</label>
                <input type="text" name="no_surat" value="<?php echo $noSurat; ?>" readonly style="background: #ecf0f1;">
            </div>
            
            <div class="form-group">
                <label>Tanggal *</label>
                <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Pelanggan *</label>
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
            <label>Tujuan Pengiriman *</label>
            <textarea name="tujuan" required></textarea>
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
            <label>Keterangan</label>
            <textarea name="keterangan"></textarea>
        </div>
        
        <hr style="margin: 30px 0;">
        
        <h3>Detail Barang</h3>
        <br>
        <button type="button" onclick="addBarangRow()" class="btn btn-primary" style="margin-bottom: 10px;">+ Tambah Barang</button>
        
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
        
        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success">💾 Simpan Surat Jalan</button>
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
    /* Make JS-added inputs match the first row styling */
    .form-control-sm {
        width: 100%;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
        box-sizing: border-box;
        font-size: 14px;
    }
</style>

<?php include '../../template/footer.php'; ?>