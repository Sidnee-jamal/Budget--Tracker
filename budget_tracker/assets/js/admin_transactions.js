// assets/js/transactions.js
document.addEventListener('DOMContentLoaded', function() {
    console.log("Admin transaction manager initialized");
    
    // Modal elements
    const editModal = document.getElementById('editModal');
    const closeBtn = document.querySelector('.close');
    
    // Edit Button Handlers (updated for admin)
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            console.log("Admin edit clicked for:", this.dataset.id);
            
            // Populate all fields including user and type
            document.getElementById('editId').value = this.dataset.id;
            document.getElementById('editUserId').value = this.dataset.userId;
            document.getElementById('editType').value = this.dataset.type;
            document.getElementById('editAmount').value = this.dataset.amount;
            document.getElementById('editDescription').value = this.dataset.description;
            document.getElementById('editDate').value = this.dataset.date;
            
            editModal.style.display = 'block';
        });
    });

    // Delete Button Handlers (admin version)
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const transactionId = this.dataset.id;
            const transactionType = this.closest('tr').querySelector('.type-badge').textContent.toLowerCase();
            
            if (confirm(`Delete this ${transactionType} transaction?`)) {
                console.log(`Admin deleting transaction ${transactionId}`);
                
                fetch('admin_delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${transactionId}`
                })
                .then(response => {
                    if (!response.ok) throw new Error('Deletion failed');
                    return response.text();
                })
                .then(data => {
                    console.log("Delete success:", data);
                    location.reload();
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert(`Failed to delete ${transactionType} transaction`);
                });
            }
        });
    });

    // Enhanced Edit Form Submission (admin)
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const transactionId = formData.get('id');
            
            console.log(`Admin submitting edits for transaction ${transactionId}`);
            
            fetch('admin_edit_transaction.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Update failed');
                return response.text();
            })
            .then(data => {
                console.log("Update success:", data);
                location.reload();
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error updating transaction");
            });
        });
    }

    // Modal close handlers
    // closeBtn.addEventListener('click', () => editModal.style.display = 'none');
    
    // window.addEventListener('click', (event) => {
    //     if (event.target === editModal) {
    //         editModal.style.display = 'none';
    //     }
    // });




     
    // Improved close function that works with styling
    function closeModal() {
        editModal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Re-enable scrolling
    }
    
    // Close button handler
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    } else {
        console.error('Close button not found! Check your HTML');
    }
    
    // Close when clicking outside modal content
    window.addEventListener('click', function(event) {
        if (event.target === editModal) {
            closeModal();
        }
    });
    
    // Close with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && editModal.style.display === 'block') {
            closeModal();
        }
    });
    

    // Additional Admin Features (optional)
    // 1. Bulk selection
    // 2. Export functionality
    // 3. User filtering
});