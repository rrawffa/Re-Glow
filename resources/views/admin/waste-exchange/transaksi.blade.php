@extends('layouts.app')

@section('title', 'Transaksi Sampah Management - Re-Glow Admin')

@section('styles')
    @vite(['resources/css/admin/waste-exchange/transaksi.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <div class="transaksi-container">
        <h1 class="transaksi-page-title">
            <i class="bi bi-recycle"></i> Waste Exchange Management
        </h1>

        <div class="transaksi-tabs">
            <button class="transaksi-tab" onclick="window.location.href='{{ route('admin.waste.index') }}'">
                <i class="bi bi-grid-3x3-gap"></i> All
            </button>
            <button class="transaksi-tab" onclick="window.location.href='{{ route('admin.waste.droppoint.index') }}'">
                <i class="bi bi-geo-alt"></i> Drop Point
            </button>
            <button class="transaksi-tab active">
                <i class="bi bi-arrow-left-right"></i> Transaksi Sampah
            </button>
            <button class="transaksi-tab" onclick="window.location.href='{{ route('admin.waste.logistik.index') }}'">
                <i class="bi bi-truck"></i> Tim Logistik
            </button>
        </div>

        @if(session('success'))
            <div class="transaksi-alert transaksi-alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="transaksi-table-container">
            <div class="transaksi-table-header">
                <h2 class="transaksi-table-title">
                    <i class="bi bi-list-ul"></i> Daftar Transaksi Sampah
                </h2>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <i class="bi bi-hash"></i> Kode Transaksi
                            </th>
                            <th>
                                <i class="bi bi-person"></i> Nama
                            </th>
                            <th>
                                <i class="bi bi-geo-alt"></i> Drop Point
                            </th>
                            <th>
                                <i class="bi bi-star"></i> Perkiraan Poin
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
                        @forelse($transaksi as $tr)
                        <tr>
                            <td>
                                <span class="transaksi-code">#{{ str_pad($tr->id_tSampah, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>{{ $tr->user ? $tr->user->username : 'Unknown' }}</td>
                            <td>{{ $tr->dropPoint ? $tr->dropPoint->nama_lokasi : 'Unknown' }}</td>
                            <td>
                                <span class="transaksi-points">{{ $tr->total_poin }} poin</span>
                            </td>
                            <td>
                                <select class="transaksi-status-select {{ strtolower($tr->status) }}" 
                                        data-id="{{ $tr->id_tSampah }}" 
                                        onchange="updateStatus(this)">
                                    <option value="Menunggu" {{ $tr->status == 'Menunggu' ? 'selected' : '' }}>
                                        <i class="bi bi-clock"></i> Menunggu
                                    </option>
                                    <option value="Diproses" {{ $tr->status == 'Diproses' ? 'selected' : '' }}>
                                        <i class="bi bi-gear"></i> Diproses
                                    </option>
                                    <option value="Selesai" {{ $tr->status == 'Selesai' ? 'selected' : '' }}>
                                        <i class="bi bi-check-circle"></i> Selesai
                                    </option>
                                </select>
                            </td>
                            <td>
                                <div class="transaksi-actions">
                                    <button class="transaksi-btn-action transaksi-btn-detail" onclick="showDetail({{ $tr->id_tSampah }})" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="transaksi-btn-action transaksi-btn-delete" onclick="confirmDelete({{ $tr->id_tSampah }})" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="transaksi-empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada transaksi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                    <i class="bi bi-info-circle"></i> Detail Transaksi
                </h2>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal transaksi-confirm-modal">
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
                <p>Apakah kamu yakin menghapus transaksi ini?</p>
                <div class="transaksi-confirm-buttons">
                    <button class="transaksi-btn-confirm transaksi-btn-yes" onclick="deleteTransaction()">
                        <i class="bi bi-check-lg"></i> Iya
                    </button>
                    <button class="transaksi-btn-confirm transaksi-btn-no" onclick="closeModal('deleteModal')">
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

        function updateStatus(select) {
            const id = select.dataset.id;
            const status = select.value;

            fetch(`/admin/waste-exchange/transaksi/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update class for styling
                    select.className = 'transaksi-status-select ' + status.toLowerCase();
                    
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'transaksi-alert transaksi-alert-success';
                    alert.innerHTML = '<i class="bi bi-check-circle"></i> Status berhasil diperbarui';
                    alert.style.position = 'fixed';
                    alert.style.top = '20px';
                    alert.style.right = '20px';
                    alert.style.zIndex = '9999';
                    alert.style.maxWidth = '300px';
                    document.body.appendChild(alert);
                    
                    setTimeout(() => alert.remove(), 3000);
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui status');
            });
        }

        function showDetail(id) {
            fetch(`/admin/waste-exchange/transaksi/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tr = data.data;
                        
                        let html = `
                            <div class="transaksi-detail-grid">
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">
                                        <i class="bi bi-hash"></i> Kode Transaksi
                                    </div>
                                    <div class="transaksi-detail-value">#${String(tr.id_tSampah).padStart(6, '0')}</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">
                                        <i class="bi bi-person"></i> Nama Pengguna
                                    </div>
                                    <div class="transaksi-detail-value">${tr.user ? tr.user.username : 'Unknown'}</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">
                                        <i class="bi bi-geo-alt"></i> Drop Point
                                    </div>
                                    <div class="transaksi-detail-value">${tr.drop_point ? tr.drop_point.nama_lokasi : 'Unknown'}</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">
                                        <i class="bi bi-calendar"></i> Tanggal
                                    </div>
                                    <div class="transaksi-detail-value">${new Date(tr.tgl_tSampah).toLocaleDateString('id-ID', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">
                                        <i class="bi bi-star"></i> Total Poin
                                    </div>
                                    <div class="transaksi-detail-value">${tr.total_poin} poin</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">
                                        <i class="bi bi-info-circle"></i> Status
                                    </div>
                                    <div class="transaksi-detail-value">
                                        <span class="transaksi-status-select ${tr.status.toLowerCase()}">
                                            ${tr.status}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <h3 style="font-family: 'Bricolage Grotesque', sans-serif; margin-bottom: 1rem; color: #20413A; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="bi bi-trash"></i> Detail Sampah
                            </h3>
                            <div class="transaksi-items-list">
                        `;

                        if (tr.details && tr.details.length > 0) {
                            tr.details.forEach(detail => {
                                html += `
                                    <div class="transaksi-item-card">
                                        <div>
                                            <strong>${detail.jenis_sampah}</strong>
                                        </div>
                                        <div>
                                            <small style="color: #6c757d; display: flex; align-items: center; gap: 0.25rem;">
                                                <i class="bi bi-rulers"></i> Ukuran
                                            </small>
                                            <strong>${detail.ukuran_sampah}</strong>
                                        </div>
                                        <div>
                                            <small style="color: #6c757d; display: flex; align-items: center; gap: 0.25rem;">
                                                <i class="bi bi-box"></i> Quantity
                                            </small>
                                            <strong>${detail.quantity}x</strong>
                                        </div>
                                        <div>
                                            <small style="color: #6c757d; display: flex; align-items: center; gap: 0.25rem;">
                                                <i class="bi bi-star"></i> Poin
                                            </small>
                                            <strong>${detail.poin_per_sampah}</strong>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html += `
                                <div class="transaksi-empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>Tidak ada detail sampah</p>
                                </div>
                            `;
                        }

                        html += '</div>';

                        if (tr.foto_bukti) {
                            html += `
                                <h3 style="font-family: 'Bricolage Grotesque', sans-serif; margin-top: 1.5rem; margin-bottom: 1rem; color: #20413A; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="bi bi-image"></i> Foto Bukti
                                </h3>
                                <img src="/${tr.foto_bukti}" alt="Foto Bukti" class="transaksi-proof-image">
                            `;
                        }

                        document.getElementById('modalBody').innerHTML = html;
                        document.getElementById('detailModal').classList.add('active');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat detail transaksi');
                });
        }

        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.add('active');
        }

        function deleteTransaction() {
            if (!deleteId) return;

            fetch(`/admin/waste-exchange/transaksi/${deleteId}`, {
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
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus transaksi');
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