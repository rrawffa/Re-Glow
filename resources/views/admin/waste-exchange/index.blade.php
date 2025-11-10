@extends('layouts.app')

@section('title', 'manajemen tukar sampah - Re-Glow')
<!-- resources\admin\education\edit.blade.php -->
@section('styles')
    @vite(['resources/css/admin/waste-exchange/index.css'])
@endsection

@section('content')
    <div class="container">
        <h1 class="page-title">Waste Exchange Management</h1>

        <div class="tabs">
            <button class="tab active" data-tab="all">All</button>
            <button class="tab" data-tab="droppoint" onclick="window.location.href='{{ route('admin.waste.droppoint.index') }}'">Drop Point</button>
            <button class="tab" data-tab="transaksi" onclick="window.location.href='{{ route('admin.waste.transaksi.index') }}'">Transaksi Sampah</button>
            <button class="tab" data-tab="logistik" onclick="window.location.href='{{ route('admin.waste.logistik.index') }}'">Tim Logistik</button>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Drop Points</div>
                <div class="stat-value">{{ $stats['total_droppoint'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $stats['total_transaksi'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Transaksi Pending</div>
                <div class="stat-value">{{ $stats['transaksi_pending'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Transaksi Diproses</div>
                <div class="stat-value">{{ $stats['transaksi_diproses'] }}</div>
            </div>
        </div>

        <!-- Drop Points Table with Capacity -->
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Daftar Drop Point</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Drop Point</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dropPoints as $dp)
                    <tr>
                        <td>
                            <strong>{{ $dp->nama_lokasi }}</strong><br>
                            <small style="color: #6c757d;">{{ $dp->alamat }}</small>
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
                                <span class="badge badge-danger">Penuh</span>
                            @elseif($percentage >= 70)
                                <span class="badge badge-warning">Hampir Penuh</span>
                            @else
                                <span class="badge badge-success">Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn-action btn-detail" onclick="showDropPointDetail({{ $dp->id_drop_point }})">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">Belum ada drop point</td>
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
                <h2 class="modal-title" id="modalTitle">Detail Drop Point</h2>
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
                                    <div class="detail-label">Nama Lokasi</div>
                                    <div class="detail-value">${dp.nama_lokasi}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Koordinat</div>
                                    <div class="detail-value">${dp.koordinat}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Kapasitas</div>
                                    <div class="detail-value">${dp.kapasitas_sampah}kg</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Total Transaksi</div>
                                    <div class="detail-value">${transactions.length}</div>
                                </div>
                                <div class="detail-item" style="grid-column: 1 / -1;">
                                    <div class="detail-label">Alamat</div>
                                    <div class="detail-value">${dp.alamat}</div>
                                </div>
                            </div>

                            <h3 style="font-family: 'Bricolage Grotesque', sans-serif; margin-bottom: 1rem; color: #20413A;">Transaksi di Drop Point Ini</h3>
                            <div class="transaction-list">
                        `;

                        if (transactions.length > 0) {
                            transactions.forEach(tr => {
                                const totalItems = tr.details.reduce((sum, d) => sum + d.quantity, 0);
                                html += `
                                    <div class="transaction-item">
                                        <div>
                                            <strong>${tr.user ? tr.user.username : 'Unknown'}</strong><br>
                                            <small style="color: #6c757d;">${new Date(tr.tgl_tSampah).toLocaleDateString('id-ID')}</small>
                                        </div>
                                        <div style="text-align: right;">
                                            <strong>${totalItems} item</strong><br>
                                            <small style="color: #6c757d;">${tr.total_poin} poin</small>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html += '<p style="text-align: center; color: #6c757d; padding: 2rem;">Belum ada transaksi</p>';
                        }

                        html += '</div>';

                        document.getElementById('modalBody').innerHTML = html;
                        document.getElementById('detailModal').classList.add('active');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal();
            }
        }
    </script>
@endsection