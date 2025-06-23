// assets/js/transactions.js
document.addEventListener('DOMContentLoaded', function() {
    console.log("Document ready - transaction handlers initializing");
    
    // Edit Transaction Modal
    const editModal = document.getElementById('editModal');
    const closeBtn = document.querySelector('.close');
    
    // Edit Button Handlers
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            console.log("Edit clicked for transaction:", this.dataset.id);
            document.getElementById('editId').value = this.dataset.id;
            document.getElementById('editAmount').value = this.dataset.amount;
            document.getElementById('editDescription').value = this.dataset.description;
            document.getElementById('editDate').value = this.dataset.date;
            editModal.style.display = 'block';
        });
    });

    // Delete Button Handlers
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this transaction?')) {
                console.log("Delete requested for:", this.dataset.id);
                fetch('delete_transaction.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: this.dataset.id })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    console.log("Success:", data);
                    window.location.reload();
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error deleting transaction");
                });
            }
        });
    });

    // Close Modal
    closeBtn.addEventListener('click', function() {
        editModal.style.display = 'none';
    });

    // Edit Form Submission
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log("Edit form submitted");
            
            fetch('edit_transaction.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: this.elements.id.value,
                    amount: this.elements.amount.value,
                    description: this.elements.description.value,
                    date: this.elements.date.value
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                console.log("Success:", data);
                window.location.reload();
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error updating transaction");
            });
        });
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    });
});