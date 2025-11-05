@extends('layouts.app')

@section('title', 'Exchange History - Re-Glow')

@section('styles')
    @vite(['resources/css/waste-exchange/history.css'])
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
    @vite(['resources/js/waste-exchange/history.js'])
@endsection