function confirmCancel() {
    document.getElementById('cancelModal').style.display = 'flex';
}

function closeCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
}

document.getElementById('confirmCancelBtn').addEventListener('click', async function() {
    try {
        const response = await fetch('/waste-exchange/{{ $transaksi->id_tSampah }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('Transaction cancelled successfully!');
            window.location.href = '{{ route("waste-exchange.history") }}';
        } else {
            alert(data.message || 'Failed to cancel transaction');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }

    closeCancelModal();
});