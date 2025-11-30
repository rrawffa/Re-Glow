@extends('layouts.app')

@section('title', 'Tim Logistik Management - Re-Glow Admin')

@section('styles')
    @vite(['resources/css/admin/waste-exchange/logistik.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <div class="logistik-container">
        <h1 class="logistik-page-title">
            <i class="bi bi-recycle"></i> Waste Exchange Management
        </h1>

        <div class="logistik-tabs">
            <button class="logistik-tab" onclick="window.location.href='{{ route('admin.waste.index') }}'">
                <i class="bi bi-grid-3x3-gap"></i> All
            </button>
            <button class="logistik-tab" onclick="window.location.href='{{ route('admin.waste.droppoint.index') }}'">
                <i class="bi bi-geo-alt"></i> Drop Point
            </button>
            <button class="logistik-tab" onclick="window.location.href='{{ route('admin.waste.transaksi.index') }}'">
                <i class="bi bi-arrow-left-right"></i> Transaksi Sampah
            </button>
            <button class="logistik-tab active">
                <i class="bi bi-truck"></i> Tim Logistik
            </button>
        </div>

        <div class="logistik-info-box">
            <i class="bi bi-info-circle" style="color: #28a745; font-size: 1.25rem;"></i>
            <p><strong>Jadwal Pengambilan Otomatis:</strong> Tim logistik dijadwalkan mengambil sampah dari drop point setiap 2 hari sekali secara bergantian.</p>
        </div>

        <div class="logistik-filters">
            <div class="logistik-filter-group">
                <label class="logistik-filter-label">
                    <i class="bi bi-funnel"></i> Status
                </label>
                <select class="logistik-filter-select" id="filterStatus" onchange="filterTable()">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="dikonfirmasi">Dikonfirmasi</option>
                    <option value="selesai">Selesai</option>
                    <option value="bermasalah">Bermasalah</option>
                </select>
            </div>
            <div class="logistik-filter-group">
                <label class="logistik-filter-label">
                    <i class="bi bi-calendar"></i> Periode
                </label>
                <select class="logistik-filter-select" id="filterPeriod" onchange="filterTable()">
                    <option value="">Semua Waktu</option>
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                </select>
            </div>
        </div>

        <div class="logistik-table-container">
            <div class="logistik-table-header">
                <h2 class="logistik-table-title">
                    <i class="bi bi-list-ul"></i> Jadwal Pengambilan Sampah
                </h2>
            </div>
            <div class="table-responsive">
                <table id="logistikTable">
                    <thead>
                        <tr>
                            <th>
                                <i class="bi bi-person"></i> Nama Logistik
                            </th>
                            <th>
                                <i class="bi bi-clock"></i> Waktu Pengambilan
                            </th>
                            <th>
                                <i class="bi bi-geo-alt"></i> Drop Point
                            </th>
                            <th>
                                <i class="bi bi-trash"></i> Jenis Sampah
                            </th>
                            <th>
                                <i class="bi bi-info-circle"></i> Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal as $item)
                        <tr data-status="{{ strtolower($item->status) }}" data-date="{{ $item->waktu_pengambilan }}">
                            <td>
                                <strong>Tim Logistik {{ $loop->iteration }}</strong>
                                @if($item->user)
                                    <div class="logistik-schedule-info">
                                        <i class="bi bi-person-check"></i> PIC: {{ $item->user->username }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($item->waktu_pengambilan)->format('d M Y') }}</strong>
                                <div class="logistik-schedule-info">
                                    <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($item->waktu_pengambilan)->format('H:i') }} WIB
                                </div>
                            </td>
                            <td>
                                <strong>{{ $item->lokasi_droppoint }}</strong>
                                @if($item->koordinat_lokasi)
                                    <div class="logistik-schedule-info">
                                        <i class="bi bi-geo"></i> {{ $item->koordinat_lokasi }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{ Str::limit($item->jenis_sampah, 50) }}
                                @if($item->transaksi)
                                    <div class="logistik-schedule-info">
                                        <i class="bi bi-star"></i> Total: {{ $item->transaksi->total_poin }} poin
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($item->status) {
                                        'Pending' => 'logistik-badge-pending',
                                        'Dikonfirmasi' => 'logistik-badge-confirmed',
                                        'Selesai' => 'logistik-badge-completed',
                                        'Bermasalah' => 'logistik-badge-problem',
                                        default => 'logistik-badge-pending'
                                    };
                                    $statusIcon = match($item->status) {
                                        'Pending' => 'bi-clock',
                                        'Dikonfirmasi' => 'bi-check-circle',
                                        'Selesai' => 'bi-check-circle-fill',
                                        'Bermasalah' => 'bi-exclamation-triangle',
                                        default => 'bi-clock'
                                    };
                                @endphp
                                <span class="logistik-badge {{ $statusClass }}">
                                    <i class="bi {{ $statusIcon }}"></i> {{ $item->status }}
                                </span>
                                @if($item->catatan_pengguna)
                                    <div class="logistik-schedule-info">
                                        <i class="bi bi-chat"></i> {{ $item->catatan_pengguna }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="logistik-empty-state">
                                    <i class="bi bi-truck"></i>
                                    <p><strong>Belum ada jadwal pengambilan</strong></p>
                                    <p style="font-size: 0.875rem; margin-top: 0.5rem;">Jadwal akan muncul otomatis saat ada transaksi sampah baru</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($jadwal->count() > 0)
        <div class="logistik-stats-container">
            <h3 class="logistik-stats-title">
                <i class="bi bi-graph-up"></i> Statistik Tim Logistik
            </h3>
            <div class="logistik-stats-grid">
                <div class="logistik-stat-card">
                    <div class="logistik-stat-label">
                        <i class="bi bi-list-check"></i> Total Jadwal
                    </div>
                    <div class="logistik-stat-value">{{ $jadwal->count() }}</div>
                </div>
                <div class="logistik-stat-card logistik-stat-card-pending">
                    <div class="logistik-stat-label logistik-stat-label-pending">
                        <i class="bi bi-clock"></i> Pending
                    </div>
                    <div class="logistik-stat-value logistik-stat-value-pending">{{ $jadwal->where('status', 'Pending')->count() }}</div>
                </div>
                <div class="logistik-stat-card logistik-stat-card-confirmed">
                    <div class="logistik-stat-label logistik-stat-label-confirmed">
                        <i class="bi bi-check-circle"></i> Dikonfirmasi
                    </div>
                    <div class="logistik-stat-value logistik-stat-value-confirmed">{{ $jadwal->where('status', 'Dikonfirmasi')->count() }}</div>
                </div>
                <div class="logistik-stat-card logistik-stat-card-completed">
                    <div class="logistik-stat-label logistik-stat-label-completed">
                        <i class="bi bi-check-circle-fill"></i> Selesai
                    </div>
                    <div class="logistik-stat-value logistik-stat-value-completed">{{ $jadwal->where('status', 'Selesai')->count() }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        function filterTable() {
            const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
            const periodFilter = document.getElementById('filterPeriod').value;
            const rows = document.querySelectorAll('#logistikTable tbody tr');

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            rows.forEach(row => {
                let showRow = true;

                // Status filter
                if (statusFilter) {
                    const rowStatus = row.dataset.status.toLowerCase();
                    if (rowStatus !== statusFilter) {
                        showRow = false;
                    }
                }

                // Period filter
                if (periodFilter && row.dataset.date) {
                    const rowDate = new Date(row.dataset.date);
                    rowDate.setHours(0, 0, 0, 0);

                    if (periodFilter === 'today') {
                        if (rowDate.getTime() !== today.getTime()) {
                            showRow = false;
                        }
                    } else if (periodFilter === 'week') {
                        const weekAgo = new Date(today);
                        weekAgo.setDate(weekAgo.getDate() - 7);
                        if (rowDate < weekAgo || rowDate > today) {
                            showRow = false;
                        }
                    } else if (periodFilter === 'month') {
                        const monthAgo = new Date(today);
                        monthAgo.setMonth(monthAgo.getMonth() - 1);
                        if (rowDate < monthAgo || rowDate > today) {
                            showRow = false;
                        }
                    }
                }

                row.style.display = showRow ? '' : 'none';
            });
        }

        // Initialize table on load
        document.addEventListener('DOMContentLoaded', function() {
            filterTable();
        });
    </script>
@endsection