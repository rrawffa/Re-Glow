@extends('layouts.app')

@section('title', 'Transaksi Sampah Management - Re-Glow Admin')

@section('styles')
    @vite(['resources/css/admin/waste-exchange/transaksi.css'])
@endsection

@section('content')
    <div class="transaksi-container">
        <h1 class="transaksi-page-title">Waste Exchange Management</h1>

        <div class="transaksi-tabs">
            <button class="transaksi-tab" onclick="window.location.href='{{ route('admin.waste.index') }}'">All</button>
            <button class="transaksi-tab" onclick="window.location.href='{{ route('admin.waste.droppoint.index') }}'">Drop Point</button>
            <button class="transaksi-tab active">Transaksi Sampah</button>
            <button class="transaksi-tab" onclick="window.location.href='{{ route('admin.waste.logistik.index') }}'">Tim Logistik</button>
        </div>

        @if(session('success'))
            <div class="transaksi-alert transaksi-alert-success">{{ session('success') }}</div>
        @endif

        <div class="transaksi-table-container">
            <div class="transaksi-table-header">
                <h2 class="transaksi-table-title">Daftar Transaksi Sampah</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Nama</th>
                        <th>Drop Point</th>
                        <th>Perkiraan Poin</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $tr)
                    <tr>
                        <td><strong>#{{ str_pad($tr->id_tSampah, 6, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>{{ $tr->user ? $tr->user->username : 'Unknown' }}</td>
                        <td>{{ $tr->dropPoint ? $tr->dropPoint->nama_lokasi : 'Unknown' }}</td>
                        <td><strong>{{ $tr->total_poin }} poin</strong></td>
                        <td>
                            <select class="transaksi-status-select {{ strtolower($tr->status) }}" 
                                    data-id="{{ $tr->id_tSampah }}" 
                                    onchange="updateStatus(this)">
                                <option value="Menunggu" {{ $tr->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Diproses" {{ $tr->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Selesai" {{ $tr->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </td>
                        <td>
                            <div class="transaksi-actions">
                                <button class="transaksi-btn-action transaksi-btn-detail" onclick="showDetail({{ $tr->id_tSampah }})">Detail</button>
                                <button class="transaksi-btn-action transaksi-btn-delete" onclick="confirmDelete({{ $tr->id_tSampah }})">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('detailModal')">&times;</button>
            <div class="modal-header">
                <h2 class="modal-title">Detail Transaksi</h2>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal transaksi-confirm-modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            <div class="modal-header">
                <h2 class="modal-title">Konfirmasi Penghapusan</h2>
            </div>
            <div class="modal-body">
                <p>Apakah kamu yakin menghapus transaksi ini?</p>
                <div class="transaksi-confirm-buttons">
                    <button class="transaksi-btn-confirm transaksi-btn-yes" onclick="deleteTransaction()">Iya</button>
                    <button class="transaksi-btn-confirm transaksi-btn-no" onclick="closeModal('deleteModal')">Tidak</button>
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
                    alert.textContent = 'Status berhasil diperbarui';
                    alert.style.position = 'fixed';
                    alert.style.top = '20px';
                    alert.style.right = '20px';
                    alert.style.zIndex = '9999';
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
                                    <div class="transaksi-detail-label">Kode Transaksi</div>
                                    <div class="transaksi-detail-value">#${String(tr.id_tSampah).padStart(6, '0')}</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">Nama Pengguna</div>
                                    <div class="transaksi-detail-value">${tr.user ? tr.user.username : 'Unknown'}</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">Drop Point</div>
                                    <div class="transaksi-detail-value">${tr.drop_point ? tr.drop_point.nama_lokasi : 'Unknown'}</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">Tanggal</div>
                                    <div class="transaksi-detail-value">${new Date(tr.tgl_tSampah).toLocaleDateString('id-ID')}</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">Total Poin</div>
                                    <div class="transaksi-detail-value">${tr.total_poin} poin</div>
                                </div>
                                <div class="transaksi-detail-item">
                                    <div class="transaksi-detail-label">Status</div>
                                    <div class="transaksi-detail-value">${tr.status}</div>
                                </div>
                            </div>

                            <h3 style="font-family: 'Bricolage Grotesque', sans-serif; margin-bottom: 1rem; color: #20413A;">Detail Sampah</h3>
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
                                            <small style="color: #6c757d;">Ukuran</small><br>
                                            <strong>${detail.ukuran_sampah}</strong>
                                        </div>
                                        <div>
                                            <small style="color: #6c757d;">Quantity</small><br>
                                            <strong>${detail.quantity}x</strong>
                                        </div>
                                        <div>
                                            <small style="color: #6c757d;">Poin</small><br>
                                            <strong>${detail.poin_per_sampah}</strong>
                                        </div>
                                    </div>
                                `;
                            });
                        }

                        html += '</div>';

                        if (tr.foto_bukti) {
                            html += `
                                <h3 style="font-family: 'Bricolage Grotesque', sans-serif; margin-top: 1.5rem; margin-bottom: 1rem; color: #20413A;">Foto Bukti</h3>
                                <img src="/${tr.foto_bukti}" alt="Foto Bukti" class="transaksi-proof-image">
                            `;
                        }

                        document.getElementById('modalBody').innerHTML = html;
                        document.getElementById('detailModal').classList.add('active');
                    }
                })
                .catch(error => console.error('Error:', error));
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
    </script>
@endsection