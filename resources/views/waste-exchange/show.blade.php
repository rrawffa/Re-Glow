@extends('layouts.app')

@section('title', 'Transaction Details - Re-Glow')

@section('styles')
<style>
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

    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 5%;
    }

    .back-link {
        color: var(--text-gray);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .back-link:hover {
        color: var(--green-dark);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        color: var(--green-dark);
        font-weight: 700;
    }

    .transaction-id-badge {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .id-text {
        color: var(--text-gray);
        font-weight: 600;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-badge.menunggu {
        background: var(--pink-light);
        color: var(--pink-base);
    }

    .status-badge.diproses {
        background: #fff8e1;
        color: #f57c00;
    }

    .status-badge.selesai {
        background: #e8f5e9;
        color: var(--green-dark);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    }

    .detail-card h3 {
        color: var(--green-dark);
        margin-bottom: 1.5rem;
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 3rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-icon {
        position: absolute;
        left: -2.8rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: white;
        border: 3px solid #e0e0e0;
    }

    .timeline-icon.active {
        background: var(--green-dark);
        border-color: var(--green-dark);
        color: white;
    }

    .timeline-icon.current {
        background: var(--pink-base);
        border-color: var(--pink-base);
        color: white;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .timeline-content h4 {
        color: var(--green-dark);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .timeline-date {
        color: var(--text-gray);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .timeline-desc {
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    /* Summary */
    .summary-item {
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: var(--text-gray);
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .summary-value {
        color: var(--text-dark);
        font-weight: 600;
        font-size: 1rem;
    }

    .points-highlight {
        color: var(--pink-base);
        font-size: 1.5rem;
        font-weight: 700;
    }

    /* Help Box */
    .help-box {
        background: var(--pink-light);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .help-box h4 {
        color: var(--green-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .help-box p {
        color: var(--text-dark);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .btn-support {
        background: var(--green-dark);
        color: white;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
    }

    /* Items Table */
    .items-table {
        width: 100%;
        margin-top: 1rem;
    }

    .items-table thead tr {
        background: #f9f9f9;
    }

    .items-table th {
        padding: 1rem;
        text-align: left;
        color: var(--text-gray);
        font-weight: 600;
        font-size: 0.875rem;
        border-bottom: 2px solid #e0e0e0;
    }

    .items-table td {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        color: var(--text-dark);
    }

    /* Photo Proof */
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .photo-item {
        border-radius: 15px;
        overflow: hidden;
        aspect-ratio: 1;
        background: #f5f5f5;
    }

    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-action {
        flex: 1;
        padding: 1rem;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .btn-edit-transaction {
        background: var(--green-dark);
        color: white;
    }

    .btn-edit-transaction:hover {
        background: #163026;
    }

    .btn-cancel-transaction {
        background: white;
        color: #dc3545;
        border: 2px solid #dc3545;
    }

    .btn-cancel-transaction:hover {
        background: #dc3545;
        color: white;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .photo-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="detail-container">
    <a href="{{ route('waste-exchange.history') }}" class="back-link">
        ← Back to History
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
                        $statuses = ['Menunggu' => 'Submitted', 'Diproses' => 'Dropped Off', 'Selesai' => 'Processing', 'Completed' => 'Completed'];
                        $statusIcons = ['Menunggu' => '📦', 'Diproses' => '🚚', 'Selesai' => '♻️', 'Completed' => '✅'];
                        $currentStatus = $transaksi->status;
                    @endphp

                    @foreach($transaksi->riwayat as $index => $riwayat)
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $riwayat->status == $currentStatus ? 'current' : 'active' }}">
                            {{ $statusIcons[$riwayat->status] ?? '📌' }}
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
                        <div class="timeline-icon">⏳</div>
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
            <div class="detail-card" style="margin-top: 2rem;">
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
            <div class="detail-card" style="margin-top: 2rem;">
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
                    Edit Transaction
                </a>
                <button type="button" class="btn-action btn-cancel-transaction" onclick="confirmCancel()">
                    Cancel Transaction
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

                <!-- Help Box -->
                <div class="help-box">
                    <h4>Need Help?</h4>
                    <p>If you have questions about your transaction or need to make changes, contact our support team.</p>
                    <button class="btn-support">Contact Support</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal (sama seperti delete modal di history) -->
<div class="modal-overlay" id="cancelModal" style="display:none;">
    <div class="modal-content" style="background:white;padding:2rem;border-radius:20px;max-width:500px;text-align:center;">
        <div style="width:80px;height:80px;background:#ffebee;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2.5rem;color:#dc3545;">
            🗑️
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