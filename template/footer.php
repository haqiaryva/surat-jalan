 </div>
    <script>
        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus data ini?');
        }
        
        function addBarangRow() {
            const table = document.getElementById('barangTable');
            const rowCount = table.rows.length;
            const row = table.insertRow(rowCount);
            
            row.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" name="nama_barang[]" required class="form-control-sm"></td>
                <td><input type="number" name="jumlah[]" required class="form-control-sm"></td>
                <td><input type="text" name="satuan[]" required class="form-control-sm"></td>
                <td><input type="number" step="0.01" name="berat[]" class="form-control-sm"></td>
                <td><input type="text" name="ket_barang[]" class="form-control-sm"></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Hapus</button></td>
            `;
        }
        
        function removeRow(btn) {
            const row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateRowNumbers();
        }
        
        function updateRowNumbers() {
            const table = document.getElementById('barangTable');
            for (let i = 1; i < table.rows.length; i++) {
                table.rows[i].cells[0].innerHTML = i;
            }
        }
    </script>
</body>
</html>