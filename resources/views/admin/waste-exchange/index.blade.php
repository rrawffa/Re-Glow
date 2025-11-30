@extends('layouts.app')

@section('title', 'Manajemen Tukar Sampah - Re-Glow')

@section('styles')
    @vite(['resources/css/admin/waste-exchange/index.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <h1 class="page-title">
            <i class="bi bi-recycle"></i> Waste Exchange Management
        </h1>

        <div class="tabs">
            <button class="tab active" data-tab="all">
                <i class="bi bi-grid-3x3-gap"></i> All
            </button>
            <button class="tab" data-tab="droppoint" onclick="window.location.href='{{ route('admin.waste.droppoint.index') }}'">
                <i class="bi bi-geo-alt"></i> Drop Point
            </button>
            <button class="tab" data-tab="transaksi" onclick="window.location.href='{{ route('admin.waste.transaksi.index') }}'">
                <i class="bi bi-arrow-left-right"></i> Transaksi Sampah
            </button>
            <button class="tab" data-tab="logistik" onclick="window.location.href='{{ route('admin.waste.logistik.index') }}'">
                <i class="bi bi-truck"></i> Tim Logistik
            </button>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">
                    <i class="bi bi-geo-alt"></i> Total Drop Points
                </div>
                <div class="stat-value">{{ $stats['total_droppoint'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">
                    <i class="bi bi-arrow-left-right"></i> Total Transaksi
                </div>
                <div class="stat-value">{{ $stats['total_transaksi'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">
                    <i class="bi bi-clock"></i> Transaksi Pending
                </div>
                <div class="stat-value">{{ $stats['transaksi_pending'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">
                    <i class="bi bi-gear"></i> Transaksi Diproses
                </div>
                <div class="stat-value">{{ $stats['transaksi_diproses'] }}</div>
            </div>
        </div>

        <!-- Recent Drop Points Table -->
        <div class="table-container">
            <div class="table-header">
                <div>
                    <h2 class="table-title">
                        <i class="bi bi-list-ul"></i> Recent Drop Points
                    </h2>
                    <div class="table-subtitle">Daftar drop point dengan status kapasitas terbaru</div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>
                            <i class="bi bi-geo-alt"></i> Drop Point
                        </th>
                        <th>
                            <i class="bi bi-speedometer2"></i> Kapasitas
                        </th>
                        <th>
                            <i class="bi bi-info-circle"></i> Status
                        </th>
                        <th>
                            <i class="bi bi-activity"></i> Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dropPoints as $dp)
                    <tr>
                        <td>
                            <strong>{{ $dp->nama_lokasi }}</strong><br>
                            <small style="color: #6c757d;">
                                <i class="bi bi-geo"></i> {{ $dp->alamat }}
                            </small>
                        </td>
                        <td>
                            <div>
                                <strong>{{ number_format($dp->current_capacity, 1) }}kg / {{ number_format($dp->kapasitas_sampah, 1) }}kg</strong>
                            </div>
                            <div class="capacity-bar">
                                @php
                                    $percentage = ($dp->current_capacity / $dp->kapasitas_sampah) * 100;
                                    $colorClass = $percentage >= 90 ? 'danger' : ($percentage >= 70 ? 'warning' : '');
                                @endphp
                                <div class="capacity-fill {{ $colorClass }}" style="width: {{ min($percentage, 100) }}%"></div>
                            </div>
                        </td>
                        <td>
                            @if($dp->is_full)
                                <span class="badge badge-danger">
                                    <i class="bi bi-exclamation-triangle"></i> Penuh
                                </span>
                            @elseif($percentage >= 70)
                                <span class="badge badge-warning">
                                    <i class="bi bi-exclamation-circle"></i> Hampir Penuh
                                </span>
                            @else
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle"></i> Tersedia
                                </span>
                            @endif
                        </td>
                        <td>
                            <button class="btn-action btn-detail" onclick="showDropPointDetail({{ $dp->id_drop_point }})">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>Belum ada drop point</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="bi bi-info-circle"></i> Detail Drop Point
                </h2>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showDropPointDetail(id) {
            fetch(`/admin/waste-exchange/droppoint/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const dp = data.data.dropPoint;
                        const transactions = data.data.transactions;

                        let html = `
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-geo-alt"></i> Nama Lokasi
                                    </div>
                                    <div class="detail-value">${dp.nama_lokasi}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-geo"></i> Koordinat
                                    </div>
                                    <div class="detail-value">${dp.koordinat}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-speedometer2"></i> Kapasitas
                                    </div>
                                    <div class="detail-value">${dp.kapasitas_sampah}kg</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-arrow-left-right"></i> Total Transaksi
                                    </div>
                                    <div class="detail-value">${transactions.length}</div>
                                </div>
                                <div class="detail-item" style="grid-column: 1 / -1;">
                                    <div class="detail-label">
                                        <i class="bi bi-house"></i> Alamat
                                    </div>
                                    <div class="detail-value">${dp.alamat}</div>
                                </div>
                            </div>

                            <h3 style="font-family: 'Bricolage Grotesque', sans-serif; margin-bottom: 1rem; color: #20413A; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi bi-clock-history"></i> Transaksi di Drop Point Ini
                            </h3>
                            <div class="transaction-list">
                        `;

                        if (transactions.length > 0) {
                            transactions.forEach(tr => {
                                const totalItems = tr.details.reduce((sum, d) => sum + d.quantity, 0);
                                const transactionDate = new Date(tr.tgl_tSampah).toLocaleDateString('id-ID', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                
                                html += `
                                    <div class="transaction-item">
                                        <div>
                                            <strong>
                                                <i class="bi bi-person"></i> ${tr.user ? tr.user.username : 'Unknown'}
                                            </strong><br>
                                            <small style="color: #6c757d;">
                                                <i class="bi bi-calendar"></i> ${transactionDate}
                                            </small>
                                        </div>
                                        <div style="text-align: right;">
                                            <strong>
                                                <i class="bi bi-box"></i> ${totalItems} item
                                            </strong><br>
                                            <small style="color: #6c757d;">
                                                <i class="bi bi-star"></i> ${tr.total_poin} poin
                                            </small>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html += `
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada transaksi di drop point ini</p>
                                </div>
                            `;
                        }

                        html += '</div>';

                        document.getElementById('modalBody').innerHTML = html;
                        document.getElementById('detailModal').classList.add('active');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data drop point');
                });
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal();
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
@endsection