<?php include '../../template/header.php'; ?>

<div class="card">
    <h2>Tambah Pelanggan Baru</h2>
    
    <form action="../../process/pelanggan_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label>Nama Pelanggan *</label>
            <input type="text" name="nama_pelanggan" required>
        </div>
        
        <div class="form-group">
            <label>Alamat *</label>
            <textarea name="alamat" required></textarea>
        </div>
        
        <div class="form-group">
            <label>Telepon *</label>
            <input type="text" name="telepon" required>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email">
        </div>
        
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="list.php" class="btn btn-danger">Batal</a>
    </form>
</div>

<?php include '../../template/footer.php'; ?>
