let deleteTransactionId = null;

function confirmDelete(id) {
    deleteTransactionId = id;
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    deleteTransactionId = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
    if (!deleteTransactionId) return;

    try {
        const response = await fetch(`/waste-exchange/${deleteTransactionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('Transaction deleted successfully!');
            window.location.reload();
        } else {
            alert(data.message || 'Failed to delete transaction');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while deleting the transaction');
    }

    closeDeleteModal();
});

// Close modal on outside click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});