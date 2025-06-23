document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const editModal = document.getElementById('editModal');
    const closeBtn = document.querySelector('.close');
    // Edit button handlers
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            // Populate all fields including user and type
             document.getElementById('editId').value = this.dataset.id;
            document.getElementById('editUsername').value = this.dataset.username;
             document.getElementById('editEmail').value = this.dataset.email;
     
      
            
                editModal.style.display = 'block';
        });
    });


        // Delete Button Handlers (admin version)
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const user_Id = this.dataset.id;
            const role = this.closest('tr').querySelector('.role-badge').textContent.toLowerCase();
            
            if (confirm(`Delete this ${role}?`)) {
                
                
                fetch('admin_delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${user_Id}`
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
                    alert(`Failed to delete ${role}`);
                });
            }
        });
    });


    
   
    
    // // Form submission
    // document.getElementById('editUserForm').addEventListener('submit', function(e) {
    //     e.preventDefault();
        
    //     fetch(window.location.href, {
    //         method: 'POST',
    //         body: new FormData(this)
    //     })
    //     .then(response => {
    //         if (response.ok) {
    //             window.location.reload();
    //         }
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //     });
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
    

});




