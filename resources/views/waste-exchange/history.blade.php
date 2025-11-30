@extends('layouts.app')

@section('title', 'Exchange History - Re-Glow')

@section('styles')
    @vite(['resources/css/waste-exchange/history.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="history-container">
    <div class="page-header">
        <h1>Exchange History</h1>
        <p>Track your cosmetic waste recycling journey and environmental impact</p>
    </div>

    <!-- Status Overview -->
    <div class="status-overview">
        <h3>Your Recycling Impact: Transaction Status</h3>
        
        <div class="progress-bar">
            <div class="progress-fill">
                @php
                    $total = $statusCounts['menunggu'] + $statusCounts['diproses'] + $statusCounts['selesai'];
                    $submittedPercent = $total > 0 ? ($statusCounts['menunggu'] / $total) * 100 : 0;
                    $transitPercent = $total > 0 ? ($statusCounts['diproses'] / $total) * 100 : 0;
                    $completedPercent = $total > 0 ? ($statusCounts['selesai'] / $total) * 100 : 0;
                @endphp
                <div class="progress-segment progress-submitted" style="width: {{ $submittedPercent }}%"></div>
                <div class="progress-segment progress-transit" style="width: {{ $transitPercent }}%"></div>
                <div class="progress-segment progress-completed" style="width: {{ $completedPercent }}%"></div>
            </div>
        </div>

        <div class="status-items">
            <div class="status-item">
                <div class="status-icon submitted">
                    <i class="bi bi-box"></i>
                </div>
                <span class="status-number">{{ $statusCounts['menunggu'] }}</span>
                <span class="status-label">Submitted</span>
                <span class="status-desc">Items registered</span>
            </div>
            <div class="status-item">
                <div class="status-icon transit">
                    <i class="bi bi-truck"></i>
                </div>
                <span class="status-number">{{ $statusCounts['diproses'] }}</span>
                <span class="status-label">In Transit</span>
                <span class="status-desc">Being processed</span>
            </div>
            <div class="status-item">
                <div class="status-icon completed">
                    <i class="bi bi-check-circle"></i>
                </div>
                <span class="status-number">{{ $statusCounts['selesai'] }}</span>
                <span class="status-label">Completed</span>
                <span class="status-desc">Successfully recycled</span>
            </div>
        </div>
    </div>

    <!-- Transactions List or Empty State -->
    <div class="transactions-section">
        @if($transactions->count() > 0)
            <h2>Recent Transactions</h2>
            
            @foreach($transactions as $transaction)
            <div class="transaction-card">
                <div class="transaction-header">
                    <div>
                        <div class="transaction-id">Swap ID: #RG-{{ str_pad($transaction->id_tSampah, 5, '0', STR_PAD_LEFT) }}</div>
                        <div class="transaction-date">{{ $transaction->tgl_tSampah->format('M d, Y') }}</div>
                    </div>
                    <span class="status-badge {{ strtolower(str_replace(' ', '-', $transaction->status)) }}">
                        {{ $transaction->status }}
                    </span>
                </div>

                <div class="transaction-details">
                    <div class="detail-item">
                        <h4>Items</h4>
                        <p>{{ $transaction->details->sum('quantity') }} items total</p>
                    </div>
                    <div class="detail-item">
                        <h4>Drop Point</h4>
                        <p>{{ $transaction->dropPoint->nama_lokasi }}</p>
                    </div>
                    <div class="detail-item">
                        <h4>{{ $transaction->status == 'Selesai' ? 'Points Earned' : 'Estimated Points' }}</h4>
                        <p style="color: var(--pink-base);">+{{ $transaction->total_poin }} points</p>
                    </div>
                </div>

                <div class="transaction-actions">
                    @if($transaction->status == 'Menunggu')
                        <a href="{{ route('waste-exchange.edit', $transaction->id_tSampah) }}" class="btn-action btn-edit">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $transaction->id_tSampah }})">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    @endif

                    @if($transaction->status == 'Selesai')
                        <div class="transaction-success">
                            <i class="bi bi-check-circle"></i> Transaction completed successfully
                        </div>
                    @endif

                    <a href="{{ route('waste-exchange.show', $transaction->id_tSampah) }}" class="btn-action btn-view" style="margin-left: auto;">
                        <i class="bi bi-eye"></i> Details
                    </a>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="pagination-container">
                {{ $transactions->links('pagination::bootstrap-4') }} 
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <img src="/assets/re-glow.svg" alt="Recycle Icon">
                </div>
                <h3>You don't have any transactions yet. Make your first exchange and see the positive impact you can make!</h3>
                <p>Transform your empty cosmetic containers into something beautiful. Join our transparent recycling process and watch your waste become part of the circular economy.</p>
                <a href="{{ route('waste-exchange.create') }}" class="btn-start-exchange">
                    <i class="bi bi-arrow-repeat"></i> Start My Exchange Now!
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="bi bi-trash"></i>
        </div>
        <h3>Delete Transaction</h3>
        <p>Are you sure you want to delete this transaction? This action cannot be undone.</p>
        
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeDeleteModal()">
                <i class="bi bi-x"></i> Cancel
            </button>
            <button type="button" class="btn-confirm-delete" id="confirmDeleteBtn">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection