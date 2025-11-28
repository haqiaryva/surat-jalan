<?php
include '../../template/header.php';

$db = new Database();
$conn = $db->connect();

$id = $_GET['id'];
$query = "SELECT * FROM pelanggan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<div class="card">
    <h2>Edit Pelanggan</h2>
    
    <form action="../../process/pelanggan_process.php" method="POST">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        
        <div class="form-group">
            <label>Nama Pelanggan *</label>
            <input type="text" name="nama_pelanggan" value="<?php echo htmlspecialchars($data['nama_pelanggan']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Alamat *</label>
            <textarea name="alamat" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Telepon *</label>
            <input type="text" name="telepon" value="<?php echo htmlspecialchars($data['telepon']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
        </div>
        
        <button type="submit" class="btn btn-success">Update</button>
        <a href="list.php" class="btn btn-danger">Batal</a>
    </form>
</div>

<?php include '../../template/footer.php'; ?>