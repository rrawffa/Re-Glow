@extends('layouts.app')

@section('title', 'Re-Glow - History Penjemputan')

@section('styles')
<style>
    :root {
        --pink-base: #F9B6C7;
        --green-dark: #20413A;
        --green-light: #BAC2AB;
        --text-gray: #666666;
        --text-dark: #2D2D2D;
        --border-light: #e8e8e8;
    }

    .history-container {
        padding: 3rem 5%;
        background: #f8f9fa;
        min-height: calc(100vh - 120px);
    }

    .history-header {
        max-width: 1200px;
        margin: 0 auto 3rem;
    }

    .history-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 0.5rem;
    }

    .history-header p {
        font-size: 1.1rem;
        color: var(--text-gray);
    }

    /* Stats Section */
    .history-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--pink-base);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-gray);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .stat-card.accent .stat-value {
        color: var(--green-dark);
    }

    /* Filters */
    .history-filters {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .filter-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .filter-controls {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    .filter-input,
    .filter-select {
        padding: 0.75rem;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        font-size: 0.95rem;
        color: var(--text-dark);
        background: white;
    }

    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: var(--pink-base);
        box-shadow: 0 0 0 3px rgba(249, 182, 199, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-filter {
        background: var(--pink-base);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: #F7A3B8;
        transform: translateY(-2px);
    }

    .btn-reset {
        background: #f0f0f0;
        color: var(--text-gray);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: #e0e0e0;
    }

    /* History Table */
    .history-content {
        max-width: 1200px;
        margin: 0 auto;
    }

    .history-table-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
    }

    .history-table thead {
        background: var(--green-dark);
        color: white;
    }

    .history-table th {
        padding: 1.25rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .history-table tbody tr {
        border-bottom: 1px solid var(--border-light);
        transition: background 0.2s ease;
    }

    .history-table tbody tr:hover {
        background: #f8f9fa;
    }

    .history-table td {
        padding: 1.25rem;
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    .location-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .location-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--pink-base) 0%, #ffc9d4 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .location-info h4 {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .location-info p {
        font-size: 0.85rem;
        color: var(--text-gray);
    }

    .time-cell {
        font-weight: 600;
        color: var(--green-dark);
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: capitalize;
    }

    .status-pending {
        background: #fff4cc;
        color: #ca8a04;
    }

    .status-confirmed {
        background: #dbeafe;
        color: #0284c7;
    }

    .status-completed {
        background: #d4f4dd;
        color: #16a34a;
    }

    .status-issue {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: #f0f0f0;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: var(--pink-base);
        color: white;
        transform: translateY(-2px);
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        padding: 2rem;
        background: white;
        border-top: 1px solid var(--border-light);
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .pagination a,
    .pagination span {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        border: 1px solid var(--border-light);
        text-decoration: none;
        color: var(--text-dark);
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .pagination a:hover {
        background: var(--pink-base);
        color: white;
        border-color: var(--pink-base);
    }

    .pagination .active {
        background: var(--pink-base);
        color: white;
        border-color: var(--pink-base);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: var(--text-gray);
        margin-bottom: 2rem;
    }

    /* Timeline view */
    .history-timeline {
        max-width: 1200px;
        margin: 0 auto;
    }

    .timeline-item {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--pink-base);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .timeline-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .timeline-location {
        flex: 1;
    }

    .timeline-location h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .timeline-location p {
        font-size: 0.9rem;
        color: var(--text-gray);
    }

    .timeline-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .meta-label {
        font-size: 0.8rem;
        color: var(--text-gray);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .history-container {
            padding: 1.5rem 5%;
        }

        .history-header h1 {
            font-size: 1.75rem;
        }

        .history-stats {
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .filter-controls {
            grid-template-columns: 1fr;
        }

        .history-table {
            font-size: 0.85rem;
        }

        .history-table th,
        .history-table td {
            padding: 0.75rem;
        }

        .location-cell {
            flex-direction: column;
            gap: 0.5rem;
        }

        .location-info h4 {
            font-size: 0.9rem;
        }

        .location-info p {
            font-size: 0.8rem;
        }

        .action-buttons {
            justify-content: center;
        }

        .timeline-meta {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="history-container">
    <!-- Header -->
    <div class="history-header">
        <h1>History Penjemputan</h1>
        <p>Kelola dan pantau riwayat pengambilan sampah secara real-time</p>
    </div>

    <!-- Stats -->
    <div class="history-stats">
        <div class="stat-card">
            <div class="stat-value" id="stat-total">{{ $totalPickups }}</div>
            <div class="stat-label">Total Penjemputan</div>
        </div>
        <div class="stat-card accent">
            <div class="stat-value">{{ $totalCompleted }}</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $completionRate }}%</div>
            <div class="stat-label">Tingkat Penyelesaian</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-pending">-</div>
            <div class="stat-label">Tertunda</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="history-filters">
        <div class="filter-title">Filter Riwayat</div>
        <form method="GET" action="{{ route('logistik.history') }}" class="filter-controls">
            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Tertunda</option>
                    <option value="Dikonfirmasi" {{ request('status') === 'Dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Bermasalah" {{ request('status') === 'Bermasalah' ? 'selected' : '' }}>Bermasalah</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Dari Tanggal</label>
                <input type="date" name="from_date" class="filter-input" value="{{ request('from_date') }}">
            </div>

            <div class="filter-group">
                <label class="filter-label">Sampai Tanggal</label>
                <input type="date" name="to_date" class="filter-input" value="{{ request('to_date') }}">
            </div>

            <div class="filter-group">
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">🔍 Filter</button>
                    <a href="{{ route('logistik.history') }}" class="btn-reset">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- History Content -->
    <div class="history-content">
        @if($pickupHistory->count() > 0)
            <div class="history-table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Waktu Penjemputan</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Kontak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pickupHistory as $pickup)
                        <tr>
                            <td class="time-cell">
                                {{ $pickup->waktu_pengambilan->format('d M Y, H:i') }}
                            </td>
                            <td>
                                <div class="location-cell">
                                    <div class="location-icon">📍</div>
                                    <div class="location-info">
                                        <h4>{{ $pickup->dropPoint->nama_droppoint ?? 'Lokasi Tidak Diketahui' }}</h4>
                                        <p>{{ $pickup->lokasi_droppoint ?? 'Alamat tidak tersedia' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace(['Dikonfirmasi', 'Bermasalah'], ['confirmed', 'issue'], $pickup->status)) }}">
                                    {{ $pickup->status }}
                                </span>
                            </td>
                            <td>{{ $pickup->user->name ?? 'N/A' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn" title="Lihat Detail">👁</button>
                                    <button class="action-btn" title="Download Laporan">📥</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($pickupHistory->hasPages())
                <div class="pagination-wrapper">
                    {{ $pickupHistory->render() }}
                </div>
                @endif
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3 class="empty-title">Tidak Ada Riwayat Penjemputan</h3>
                <p class="empty-text">Belum ada data penjemputan yang sesuai dengan filter Anda</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh stats every 30 seconds
    setInterval(() => {
        fetch('{{ route("logistik.api.stats") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('stat-total').textContent = data.total_pickups;
                document.getElementById('stat-pending').textContent = data.pending_today;
            });
    }, 30000);

    // View detail modal
    document.querySelectorAll('.action-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.textContent.includes('👁')) {
                alert('Fitur detail akan segera ditambahkan');
            } else if (this.textContent.includes('📥')) {
                alert('Download laporan akan segera ditambahkan');
            }
        });
    });
</script>
@endsection
