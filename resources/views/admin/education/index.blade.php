@extends('layouts.app')

@section('title', 'Admin - Dash Edu - Re-Glow')
<!-- resources\admin\education\index.blade.php -->
@section('styles')
    @vite(['resources/css/admin/education/index.css'])
@endsection

@section('content')
    <!-- Main Content -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="page-header">
            <h1 class="page-title">Education Content Management</h1>
            <a href="{{ route('admin.education.create') }}" class="btn-create">
                + Create New Content
            </a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Upload Date</th>
                        <th>Reactions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konten as $item)
                    <tr>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->penulis }}</td>
                        <td>{{ $item->tanggal_upload->format('Y-m-d') }}</td>
                        <td>
                            <div class="reactions">
                                <span class="reaction-item">❤️ {{ $item->statistik->total_suka ?? 0 }}</span>
                                <span class="reaction-item">👍 {{ $item->statistik->total_membantu ?? 0 }}</span>
                                <span class="reaction-item">🔥 {{ $item->statistik->total_menarik ?? 0 }}</span>
                                <span class="reaction-item">✨ {{ $item->statistik->total_inspiratif ?? 0 }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="actions">
                                <button class="btn-action btn-detail" onclick="showDetail({{ $item->id_konten }})">Detail</button>
                                <a href="{{ route('admin.education.edit', $item->id_konten) }}" class="btn-action btn-edit">Edit</a>
                                <button class="btn-action btn-delete" onclick="confirmDelete({{ $item->id_konten }})">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">Belum ada konten edukasi</td>
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
                <h2 class="modal-title" id="modalTitle"></h2>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal confirm-modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            <div class="modal-header">
                <h2 class="modal-title">Konfirmasi Penghapusan</h2>
            </div>
            <div class="modal-body">
                <p>Apakah kamu yakin menghapus konten ini?</p>
                <div class="confirm-buttons">
                    <button class="btn-confirm btn-yes" onclick="deleteContent()">Iya</button>
                    <button class="btn-confirm btn-no" onclick="closeModal('deleteModal')">Tidak</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let deleteId = null;

        function showDetail(id) {
            fetch(`/admin/education/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('modalTitle').textContent = data.data.judul;
                        document.getElementById('modalBody').innerHTML = data.data.isi;
                        document.getElementById('detailModal').classList.add('active');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('deleteModal').classList.add('active');
        }

        function deleteContent() {
            if (!deleteId) return;

            fetch(`/admin/education/${deleteId}`, {
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
                alert('Terjadi kesalahan saat menghapus konten');
            });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            if (modalId === 'deleteModal') {
                deleteId = null;
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
                deleteId = null;
            }
        }
    </script>
@endsection