 </div>
    
    <div class="custom-modal" id="confirmModal">
        <div class="custom-modal-overlay" onclick="closeConfirmModal()"></div>
        <div class="custom-modal-content">
            <div class="custom-modal-icon" id="confirmModalIcon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 id="confirmModalTitle">Konfirmasi</h3>
            <p id="confirmModalMessage">Apakah Anda yakin?</p>
            <div class="custom-modal-actions">
                <button class="btn-cancel" onclick="closeConfirmModal()">Batal</button>
                <a href="#" id="confirmModalBtn" class="btn-confirm">Ya, Lanjutkan</a>
            </div>
        </div>
    </div>
    
    <div class="custom-modal" id="alertModal">
        <div class="custom-modal-overlay" onclick="closeAlertModal()"></div>
        <div class="custom-modal-content">
            <div class="custom-modal-icon" id="alertModalIcon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 id="alertModalTitle">Berhasil</h3>
            <p id="alertModalMessage">Operasi berhasil dilakukan.</p>
            <div class="custom-modal-actions">
                <button class="btn-confirm" onclick="closeAlertModal()" style="width: 100%;">OK</button>
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete() {
            return showConfirm('Hapus Data', 'Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan.', 'danger');
        }
        
        function showConfirm(title, message, type = 'warning') {
            event.preventDefault();
            const modal = document.getElementById('confirmModal');
            const icon = document.getElementById('confirmModalIcon');
            const titleEl = document.getElementById('confirmModalTitle');
            const messageEl = document.getElementById('confirmModalMessage');
            const confirmBtn = document.getElementById('confirmModalBtn');
            
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            if (type === 'danger') {
                icon.innerHTML = '<i class="fas fa-trash-alt"></i>';
                icon.style.background = 'linear-gradient(135deg, var(--danger-color), var(--danger-dark))';
                confirmBtn.style.background = 'linear-gradient(135deg, var(--danger-color), var(--danger-dark))';
            } else if (type === 'warning') {
                icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                icon.style.background = 'linear-gradient(135deg, var(--warning-color), var(--warning-dark))';
                confirmBtn.style.background = 'linear-gradient(135deg, var(--warning-color), var(--warning-dark))';
            } else if (type === 'logout') {
                icon.innerHTML = '<i class="fas fa-sign-out-alt"></i>';
                icon.style.background = 'linear-gradient(135deg, var(--danger-color), var(--danger-dark))';
                confirmBtn.style.background = 'linear-gradient(135deg, var(--danger-color), var(--danger-dark))';
            }
            
            const triggerLink = event.target.closest('a');
            if (triggerLink) {
                confirmBtn.href = triggerLink.href;
                confirmBtn.onclick = null;
            }
            
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            return false;
        }
        
        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        function showAlert(title, message, type = 'success') {
            const modal = document.getElementById('alertModal');
            const icon = document.getElementById('alertModalIcon');
            const titleEl = document.getElementById('alertModalTitle');
            const messageEl = document.getElementById('alertModalMessage');
            
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            if (type === 'success') {
                icon.innerHTML = '<i class="fas fa-check-circle"></i>';
                icon.style.background = 'linear-gradient(135deg, var(--success-color), var(--secondary-dark))';
            } else if (type === 'error') {
                icon.innerHTML = '<i class="fas fa-times-circle"></i>';
                icon.style.background = 'linear-gradient(135deg, var(--danger-color), var(--danger-dark))';
            } else if (type === 'info') {
                icon.innerHTML = '<i class="fas fa-info-circle"></i>';
                icon.style.background = 'linear-gradient(135deg, var(--primary-color), var(--primary-dark))';
            }
            
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        
        function closeAlertModal() {
            const modal = document.getElementById('alertModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
            
            // Remove query parameters from URL after closing alert
            if (window.location.search) {
                const url = window.location.pathname;
                window.history.replaceState({}, document.title, url);
            }
        }
        
        function addBarangRow() {
            const table = document.getElementById('barangTable');
            const rowCount = table.rows.length;
            const row = table.insertRow(rowCount);
            
            row.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" name="nama_barang[]" required class="form-control-sm"></td>
                <td><input type="number" name="jumlah[]" required class="form-control-sm"></td>
                <td><input type="text" name="satuan[]" required class="form-control-sm" placeholder="Pcs/Dus"></td>
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
        
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdownMenu');
            dropdown.classList.toggle('show');
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('.user-dropdown')) {
                const dropdown = document.getElementById('userDropdownMenu');
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        });
        
        function showLogoutModal(logoutUrl) {
            event.preventDefault();
            showConfirm('Konfirmasi Logout', 'Yakin ingin keluar dari sistem?', 'logout');
            document.getElementById('confirmModalBtn').href = logoutUrl;
        }
    </script>
</body>
</html>