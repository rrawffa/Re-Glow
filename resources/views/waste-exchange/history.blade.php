@extends('layouts.app')

@section('title', 'Exchange History - Re-Glow')

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --pink-base: #F9B6C7;
        --green-dark: #20413A;
        --green-light: #BAC2AB;
        --pink-light: #FFF5F7;
        --text-dark: #2D2D2D;
        --text-gray: #666666;
    }

    body {
        font-family: 'DM Sans', sans-serif;
        background: #fafafa;
    }

    h1, h2, h3, h4 {
        font-family: 'Bricolage Grotesque', sans-serif;
    }

    .history-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 5%;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        color: var(--green-dark);
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .page-header p {
        color: var(--text-gray);
    }

    /* Status Progress Bar */
    .status-overview {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    }

    .status-overview h3 {
        color: var(--green-dark);
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .progress-bar {
        height: 8px;
        background: #f0f0f0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 2rem;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        display: flex;
    }

    .progress-segment {
        height: 100%;
        transition: width 0.5s ease;
    }

    .progress-submitted {
        background: var(--pink-base);
    }

    .progress-transit {
        background: var(--green-light);
    }

    .progress-completed {
        background: var(--green-dark);
    }

    .status-items {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .status-item {
        text-align: center;
    }

    .status-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        background: #f5f5f5;
    }

    .status-icon.submitted {
        background: var(--pink-light);
        color: var(--pink-base);
    }

    .status-icon.transit {
        background: #f5f5f0;
        color: var(--green-light);
    }

    .status-icon.completed {
        background: #e8f5e9;
        color: var(--green-dark);
    }

    .status-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--green-dark);
        display: block;
    }

    .status-label {
        color: var(--text-dark);
        font-weight: 600;
        display: block;
        margin-bottom: 0.25rem;
    }

    .status-desc {
        color: var(--text-gray);
        font-size: 0.875rem;
    }

    /* Transactions List */
    .transactions-section h2 {
        color: var(--green-dark);
        margin-bottom: 1.5rem;
        font-weight: 700;
    }

    .transaction-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s;
    }

    .transaction-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .transaction-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .transaction-id {
        font-weight: 700;
        color: var(--green-dark);
        font-size: 1.125rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-badge.submitted {
        background: var(--pink-light);
        color: var(--pink-base);
    }

    .status-badge.processing {
        background: #fff8e1;
        color: #f57c00;
    }

    .status-badge.completed {
        background: #e8f5e9;
        color: var(--green-dark);
    }

    .transaction-date {
        color: var(--text-gray);
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .transaction-details {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .detail-item h4 {
        color: var(--text-gray);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .detail-item p {
        color: var(--text-dark);
        font-weight: 600;
    }

    .transaction-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-edit {
        background: white;
        color: var(--green-dark);
        border: 2px solid var(--green-dark);
    }

    .btn-edit:hover {
        background: var(--green-dark);
        color: white;
    }

    .btn-delete {
        background: white;
        color: #dc3545;
        border: 2px solid #dc3545;
    }

    .btn-delete:hover {
        background: #dc3545;
        color: white;
    }

    .btn-view {
        background: var(--green-dark);
        color: white;
    }

    .btn-view:hover {
        background: #163026;
    }

    .transaction-success {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--green-dark);
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    }

    .empty-icon {
        width: 200px;
        height: 200px;
        margin: 0 auto 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon svg {
        width: 100%;
        height: 100%;
    }

    .empty-state h3 {
        color: var(--green-dark);
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: var(--text-gray);
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-start-exchange {
        background: var(--pink-base);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        font-size: 1.125rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-start-exchange:hover {
        background: #e89fb0;
        transform: translateY(-2px);
    }

    /* Delete Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        text-align: center;
    }

    .modal-icon {
        width: 80px;
        height: 80px;
        background: #ffebee;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        color: #dc3545;
    }

    .modal-content h3 {
        color: var(--green-dark);
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .modal-content p {
        color: var(--text-gray);
        margin-bottom: 2rem;
    }

    .modal-buttons {
        display: flex;
        gap: 1rem;
    }

    .btn-cancel {
        flex: 1;
        background: #f5f5f5;
        color: var(--text-dark);
        padding: 1rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-confirm-delete {
        flex: 1;
        background: #dc3545;
        color: white;
        padding: 1rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-load-more {
        background: var(--green-dark);
        color: white;
        padding: 1rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 2rem auto;
    }

    @media (max-width: 768px) {
        .status-items {
            grid-template-columns: 1fr;
        }

        .transaction-details {
            grid-template-columns: 1fr;
        }

        .transaction-actions {
            flex-direction: column;
        }
    }
</style>
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
                <div class="status-icon submitted">📦</div>
                <span class="status-number">{{ $statusCounts['menunggu'] }}</span>
                <span class="status-label">Submitted</span>
                <span class="status-desc">Items registered</span>
            </div>
            <div class="status-item">
                <div class="status-icon transit">🚚</div>
                <span class="status-number">{{ $statusCounts['diproses'] }}</span>
                <span class="status-label">In Transit</span>
                <span class="status-desc">Being processed</span>
            </div>
            <div class="status-item">
                <div class="status-icon completed">✅</div>
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
                            ✏️ Edit
                        </a>
                        <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $transaction->id_tSampah }})">
                            🗑️ Cancel
                        </button>
                    @endif

                    @if($transaction->status == 'Selesai')
                        <div class="transaction-success">
                            ✅ Transaction completed successfully
                        </div>
                    @endif

                    <a href="{{ route('waste-exchange.show', $transaction->id_tSampah) }}" class="btn-action btn-view" style="margin-left: auto;">
                        👁️ View Details
                    </a>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            @if($transactions->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                {{ $transactions->links() }}
            </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Lotus Flower with Recycle Symbol -->
                        <circle cx="100" cy="100" r="80" fill="#FFF5F7"/>
                        <!-- Lotus petals -->
                        <path d="M100 50 C85 50, 75 60, 75 75 L75 100 L100 100 Z" fill="#F9B6C7" opacity="0.6"/>
                        <path d="M100 50 C115 50, 125 60, 125 75 L125 100 L100 100 Z" fill="#F9B6C7" opacity="0.7"/>
                        <path d="M50 100 C50 85, 60 75, 75 75 L100 75 L100 100 Z" fill="#F9B6C7" opacity="0.8"/>
                        <path d="M150 100 C150 85, 140 75, 125 75 L100 75 L100 100 Z" fill="#F9B6C7" opacity="0.5"/>
                        <path d="M100 150 C85 150, 75 140, 75 125 L75 100 L100 100 Z" fill="#F9B6C7" opacity="0.9"/>
                        <path d="M100 150 C115 150, 125 140, 125 125 L125 100 L100 100 Z" fill="#F9B6C7" opacity="0.6"/>
                        <!-- Recycle symbol in center -->
                        <circle cx="100" cy="100" r="25" fill="#20413A"/>
                        <path d="M100 85 L90 100 L95 100 L95 110 L105 110 L105 100 L110 100 Z" fill="white"/>
                        <path d="M85 105 L95 105 L90 115 L85 105 Z" fill="white"/>
                        <path d="M115 105 L105 105 L110 115 L115 105 Z" fill="white"/>
                    </svg>
                </div>
                <h3>You don't have any transactions yet. Make your first exchange and see the positive impact you can make!</h3>
                <p>Transform your empty cosmetic containers into something beautiful. Join our transparent recycling process and watch your waste become part of the circular economy.</p>
                <a href="{{ route('waste-exchange.create') }}" class="btn-start-exchange">
                    🔄 Start My Exchange Now!
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <div class="modal-icon">🗑️</div>
        <h3>Delete Transaction</h3>
        <p>Are you sure you want to delete this transaction? This action cannot be undone.</p>
        
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button type="button" class="btn-confirm-delete" id="confirmDeleteBtn">Delete</button>
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