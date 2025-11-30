@extends('layouts.app')

@section('title', 'Transaction Details - Re-Glow')

@section('styles')
    @vite(['resources/css/waste-exchange/show.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<div class="detail-container">
    <a href="{{ route('waste-exchange.history') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Back to History
    </a>

    <div class="page-header">
        <h1>Transaction Details</h1>
        <div class="transaction-id-badge">
            <span class="id-text">Transaction ID: <strong>#RG-{{ str_pad($transaksi->id_tSampah, 5, '0', STR_PAD_LEFT) }}</strong></span>
            <span class="status-badge {{ strtolower($transaksi->status) }}">{{ $transaksi->status }}</span>
        </div>
    </div>

    <div class="detail-grid">
        <!-- Left Column -->
        <div>
            <!-- Transaction Progress -->
            <div class="detail-card">
                <h3>Transaction Progress</h3>
                <div class="timeline">
                    @php
                        $statusIcons = [
                            'Menunggu' => 'bi-box-seam', 
                            'Diproses' => 'bi-truck', 
                            'Selesai' => 'bi-recycle', 
                            'Completed' => 'bi-check-circle'
                        ];
                        $currentStatus = $transaksi->status;
                    @endphp

                    @foreach($transaksi->riwayat as $index => $riwayat)
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $riwayat->status == $currentStatus ? 'current' : 'active' }}">
                            <i class="bi {{ $statusIcons[$riwayat->status] ?? 'bi-pin' }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>{{ $riwayat->status }}</h4>
                            <div class="timeline-date">{{ $riwayat->tanggal_update->format('M d, Y \a\t g:i A') }}</div>
                            <div class="timeline-desc">
                                @if($riwayat->status == 'Menunggu')
                                    Transaction submitted successfully
                                @elseif($riwayat->status == 'Diproses')
                                    Items received at collection point
                                @elseif($riwayat->status == 'Selesai')
                                    Items being sorted and processed
                                @else
                                    Points will be credited to your account
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($currentStatus != 'Selesai')
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="timeline-content">
                            <h4 style="color: #999;">Completed</h4>
                            <div class="timeline-date">Pending</div>
                            <div class="timeline-desc">Points will be credited to your account</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Waste Items -->
            <div class="detail-card">
                <h3>Waste Items Submitted</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->details as $detail)
                        <tr>
                            <td>{{ $detail->jenis_sampah }}</td>
                            <td>{{ $detail->jenis_sampah }}</td>
                            <td>{{ $detail->ukuran_sampah }}</td>
                            <td>{{ $detail->quantity }} items</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Photo Proof -->
            @if($transaksi->foto_bukti)
            <div class="detail-card">
                <h3>Photo Proof</h3>
                <div class="photo-grid">
                    <div class="photo-item">
                        <img src="{{ asset($transaksi->foto_bukti) }}" alt="Waste proof">
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons (only for Menunggu status) -->
            @if($transaksi->status == 'Menunggu')
            <div class="action-buttons">
                <a href="{{ route('waste-exchange.edit', $transaksi->id_tSampah) }}" class="btn-action btn-edit-transaction">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <button type="button" class="btn-action btn-cancel-transaction" onclick="confirmCancel()">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div>
            <!-- Transaction Summary -->
            <div class="detail-card">
                <h3>Transaction Summary</h3>
                
                <div class="summary-item">
                    <div class="summary-label">Drop Point</div>
                    <div class="summary-value">{{ $transaksi->dropPoint->nama_lokasi }}</div>
                    <div style="color: var(--text-gray); font-size: 0.875rem; margin-top: 0.25rem;">
                        {{ $transaksi->dropPoint->alamat }}
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-label">Submission Date</div>
                    <div class="summary-value">{{ $transaksi->tgl_tSampah->format('F d, Y \a\t g:i A') }}</div>
                </div>

                <div class="summary-item">
                    <div class="summary-label">{{ $transaksi->status == 'Selesai' ? 'Points Earned' : 'Estimated Points' }}</div>
                    <div class="points-highlight">+{{ $transaksi->total_poin }} points</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal-overlay" id="cancelModal" style="display:none;">
    <div class="modal-content">
        <div style="width:80px;height:80px;background:#ffebee;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2.5rem;color:#dc3545;">
            <i class="bi bi-trash"></i>
        </div>
        <h3 style="color:var(--green-dark);margin-bottom:1rem;font-weight:700;">Cancel Transaction</h3>
        <p style="color:var(--text-gray);margin-bottom:2rem;">Are you sure you want to cancel this transaction? This action cannot be undone.</p>
        
        <div style="display:flex;gap:1rem;">
            <button type="button" onclick="closeCancelModal()" style="flex:1;background:#f5f5f5;color:var(--text-dark);padding:1rem;border:none;border-radius:10px;font-weight:600;cursor:pointer;">
                Cancel
            </button>
            <button type="button" id="confirmCancelBtn" style="flex:1;background:#dc3545;color:white;padding:1rem;border:none;border-radius:10px;font-weight:600;cursor:pointer;">
                Delete
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection