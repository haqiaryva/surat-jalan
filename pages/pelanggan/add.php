<?php include '../../template/header.php'; ?>

<div class="card">
    <h2><i class="fas fa-user-plus"></i> Tambah Pelanggan Baru</h2>
    
    <form action="../../process/pelanggan_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label><i class="fas fa-building"></i> Nama Pelanggan *</label>
            <input type="text" name="nama_pelanggan" required placeholder="Masukkan nama pelanggan">
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-map-marker-alt"></i> Alamat *</label>
            <textarea name="alamat" required placeholder="Masukkan alamat lengkap"></textarea>
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-phone"></i> Telepon *</label>
            <input type="text" name="telepon" required placeholder="Contoh: 021-1234567">
        </div>
        
        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email</label>
            <input type="email" name="email" placeholder="email@example.com">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Data</button>
            <a href="list.php" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>

<?php include '../../template/footer.php'; ?>
