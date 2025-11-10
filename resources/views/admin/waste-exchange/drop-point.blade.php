@extends('layouts.app')

@section('title', 'Drop Point Management - Re-Glow')

@section('styles')
    @vite(['resources/css/admin/waste-exchange/drop-point.css'])
@endsection

@section('content')
    <div class="drop-point-container">
        <h1 class="drop-point-page-title">Waste Exchange Management</h1>

        <div class="drop-point-tabs">
            <button class="drop-point-tab" onclick="window.location.href='{{ route('admin.waste.index') }}'">All</button>
            <button class="drop-point-tab active">Drop Point</button>
            <button class="drop-point-tab" onclick="window.location.href='{{ route('admin.waste.transaksi.index') }}'">Transaksi Sampah</button>
            <button class="drop-point-tab" onclick="window.location.href='{{ route('admin.waste.logistik.index') }}'">Tim Logistik</button>
        </div>

        @if(session('success'))
            <div class="drop-point-alert drop-point-alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="drop-point-alert drop-point-alert-danger">{{ session('error') }}</div>
        @endif

        <div class="drop-point-table-container">
            <div class="drop-point-table-header">
                <h2 class="drop-point-table-title">Daftar Drop Point</h2>
                <a href="{{ route('admin.waste.droppoint.create') }}" class="drop-point-btn-add">+</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Drop Point Name</th>
                        <th>Coordinates</th>
                        <th>Address</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dropPoints as $dp)
                    <tr>
                        <td>{{ $dp->nama_lokasi }}</td>
                        <td>{{ $dp->koordinat }}</td>
                        <td>{{ $dp->alamat }}</td>
                        <td>{{ $dp->kapasitas_sampah }}kg</td>
                        <td>
                            <div class="drop-point-actions">
                                <a href="{{ route('admin.waste.droppoint.edit', $dp->id_drop_point) }}" class="drop-point-btn-action drop-point-btn-edit">Edit</a>
                                <button class="drop-point-btn-action drop-point-btn-delete" onclick="confirmDelete({{ $dp->id_drop_point }})">Delete</button>
                                <button class="drop-point-btn-action drop-point-btn-detail" onclick="showDetail({{ $dp->id_drop_point }})">Detail</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">Belum ada drop point</td>
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
                <h2 class="modal-title">Detail Drop Point</h2>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal drop-point-confirm-modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            <div class="modal-header">
                <h2 class="modal-title">Konfirmasi Penghapusan</h2>
            </div>
            <div class="modal-body">
                <p>Apakah kamu yakin menghapus drop point ini?</p>
                <div class="drop-point-confirm-buttons">
                    <button class="drop-point-btn-confirm drop-point-btn-yes" onclick="deleteDropPoint()">Iya</button>
                    <button class="drop-point-btn-confirm drop-point-btn-no" onclick="closeModal('deleteModal')">Tidak</button>
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
                                    <div class="drop-point-detail-label">Nama Lokasi</div>
                                    <div class="drop-point-detail-value">${dp.nama_lokasi}</div>
                                </div>
                                <div class="drop-point-detail-item">
                                    <div class="drop-point-detail-label">Koordinat</div>
                                    <div class="drop-point-detail-value">${dp.koordinat}</div>
                                </div>
                                <div class="drop-point-detail-item">
                                    <div class="drop-point-detail-label">Kapasitas</div>
                                    <div class="drop-point-detail-value">${dp.kapasitas_sampah}kg</div>
                                </div>
                                <div class="drop-point-detail-item">
                                    <div class="drop-point-detail-label">Total Transaksi</div>
                                    <div class="drop-point-detail-value">${transactions.length}</div>
                                </div>
                                <div class="drop-point-detail-item" style="grid-column: 1 / -1;">
                                    <div class="drop-point-detail-label">Alamat</div>
                                    <div class="drop-point-detail-value">${dp.alamat}</div>
                                </div>
                            </div>

                            <h3 style="font-family: 'Bricolage Grotesque', sans-serif; margin-bottom: 1rem; color: #20413A;">Transaksi di Drop Point Ini</h3>
                            <div class="drop-point-transaction-list">
                        `;

                        if (transactions.length > 0) {
                            transactions.forEach(tr => {
                                const totalItems = tr.details.reduce((sum, d) => sum + d.quantity, 0);
                                html += `
                                    <div class="drop-point-transaction-item">
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
    </script>
@endsection