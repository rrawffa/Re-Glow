@extends('layouts.app')

@section('title', 'Drop Point Management - Re-Glow')

@section('styles')
    @vite(['resources/css/admin/waste-exchange/drop-point.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <div class="drop-point-container">
        <h1 class="drop-point-page-title">
            <i class="bi bi-recycle"></i> Waste Exchange Management
        </h1>

        <div class="drop-point-tabs">
            <button class="drop-point-tab" onclick="window.location.href='{{ route('admin.waste.index') }}'">
                <i class="bi bi-grid-3x3-gap"></i> All
            </button>
            <button class="drop-point-tab active">
                <i class="bi bi-geo-alt"></i> Drop Point
            </button>
            <button class="drop-point-tab" onclick="window.location.href='{{ route('admin.waste.transaksi.index') }}'">
                <i class="bi bi-arrow-left-right"></i> Transaksi Sampah
            </button>
            <button class="drop-point-tab" onclick="window.location.href='{{ route('admin.waste.logistik.index') }}'">
                <i class="bi bi-truck"></i> Tim Logistik
            </button>
        </div>

        @if(session('success'))
            <div class="drop-point-alert drop-point-alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="drop-point-alert drop-point-alert-danger">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="drop-point-table-container">
            <div class="drop-point-table-header">
                <h2 class="drop-point-table-title">
                    <i class="bi bi-list-ul"></i> Daftar Drop Point
                </h2>
                <a href="{{ route('admin.waste.droppoint.create') }}" class="drop-point-btn-add" title="Add New Drop Point">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>
                            <i class="bi bi-geo-alt"></i> Drop Point Name
                        </th>
                        <th>
                            <i class="bi bi-geo"></i> Coordinates
                        </th>
                        <th>
                            <i class="bi bi-house"></i> Address
                        </th>
                        <th>
                            <i class="bi bi-speedometer2"></i> Capacity
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
                            <strong>{{ $dp->nama_lokasi }}</strong>
                        </td>
                        <td>{{ $dp->koordinat }}</td>
                        <td>{{ $dp->alamat }}</td>
                        <td>{{ $dp->kapasitas_sampah }}kg</td>
                        <td>
                            <div class="drop-point-actions">
                                <a href="{{ route('admin.waste.droppoint.edit', $dp->id_drop_point) }}" class="drop-point-btn-action drop-point-btn-edit">
                                    <i class="bi bi-pencil"></i> 
                                </a>
                                <button class="drop-point-btn-action drop-point-btn-delete" onclick="confirmDelete({{ $dp->id_drop_point }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="drop-point-btn-action drop-point-btn-detail" onclick="showDetail({{ $dp->id_drop_point }})">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="drop-point-empty-state">
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
            <button class="modal-close" onclick="closeModal('detailModal')">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="bi bi-info-circle"></i> Detail Drop Point
                </h2>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal drop-point-confirm-modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('deleteModal')">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="bi bi-exclamation-triangle"></i> Konfirmasi Penghapusan
                </h2>
            </div>
            <div class="modal-body">
                <p>Apakah kamu yakin menghapus drop point ini?</p>
                <div class="drop-point-confirm-buttons">
                    <button class="drop-point-btn-confirm drop-point-btn-yes" onclick="deleteDropPoint()">
                        <i class="bi bi-check-lg"></i> Iya
                    </button>
                    <button class="drop-point-btn-confirm drop-point-btn-no" onclick="closeModal('deleteModal')">
                        <i class="bi bi-x-lg"></i> Tidak
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let deleteId = null;

        function showDetail(id) {
            fetch(`/admin/waste-exchange/droppoint/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const dp = data.data.dropPoint;
                        const transactions = data.data.transactions;

                        let html = `
                            <div class="drop-point-detail-grid">
                                <div class="drop-point-detail-item">
                                    <div class="drop-point-detail-label">
                                        <i class="bi bi-geo-alt"></i> Nama Lokasi
                                    </div>
                                    <div class="drop-point-detail-value">${dp.nama_lokasi}</div>
                                </div>
                                <div class="drop-point-detail-item">
                                    <div class="drop-point-detail-label">
                                        <i class="bi bi-geo"></i> Koordinat
                                    </div>
                                    <div class="drop-point-detail-value">${dp.koordinat}</div>
                                </div>
                                <div class="drop-point-detail-item">
                                    <div class="drop-point-detail-label">
                                        <i class="bi bi-speedometer2"></i> Kapasitas
                                    </div>
                                    <div class="drop-point-detail-value">${dp.kapasitas_sampah}kg</div>
                                </div>
                                <div class="drop-point-detail-item">
                                    <div class="drop-point-detail-label">
                                        <i class="bi bi-arrow-left-right"></i> Total Transaksi
                                    </div>
                                    <div class="drop-point-detail-value">${transactions.length}</div>
                                </div>
                                <div class="drop-point-detail-item" style="grid-column: 1 / -1;">
                                    <div class="drop-point-detail-label">
                                        <i class="bi bi-house"></i> Alamat
                                    </div>
                                    <div class="drop-point-detail-value">${dp.alamat}</div>
                                </div>
                            </div>

                            <h3 style="font-family: 'Bricolage Grotesque', sans-serif; margin-bottom: 1rem; color: #20413A; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi bi-clock-history"></i> Transaksi di Drop Point Ini
                            </h3>
                            <div class="drop-point-transaction-list">
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
                                    <div class="drop-point-transaction-item">
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
                                <div class="drop-point-empty-state">
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

        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.add('active');
        }

        function deleteDropPoint() {
            if (!deleteId) return;

            fetch(`/admin/waste-exchange/droppoint/${deleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                    closeModal('deleteModal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus drop point');
            });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            if (modalId === 'deleteModal') {
                deleteId = null;
            }
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
                deleteId = null;
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('detailModal');
                closeModal('deleteModal');
            }
        });
    </script>
@endsection