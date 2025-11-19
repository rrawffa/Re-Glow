@extends('layouts.app')

@section('title', 'Admin - Dash Edu - Re-Glow')

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
            <div class="header-actions">
                <a href="{{ route('admin.education.create') }}" class="btn-create">
                    + Create New Content
                </a>
            </div>
        </div>

        <div class="table-container">
            <table id="educationTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th class="uplod">Upload Date</th>
                        <th>Reactions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konten as $item)
                    <tr data-id="{{ $item->id_konten }}">
                        <td>{{ Str::limit($item->judul, 50) }}</td>
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
                <p id="deleteMessage">Apakah kamu yakin menghapus konten ini?</p>
                <div class="confirm-buttons">
                    <button class="btn-confirm btn-yes" id="confirmDeleteBtn">Iya</button>
                    <button class="btn-confirm btn-no" onclick="closeModal('deleteModal')">Tidak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="loading-spinner"></div>
        <p>Menghapus konten...</p>
    </div>
@endsection

@section('scripts')
<script>
    let deleteId = null;

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
    });

    function initializeEventListeners() {
        // Confirm delete button event listener
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', deleteContent);
        }
    }

    function showDetail(id) {
        showLoading();
        fetch(`/admin/education/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                if (data.success) {
                    const modalTitle = document.getElementById('modalTitle');
                    const modalBody = document.getElementById('modalBody');
                    if (modalTitle && modalBody) {
                        modalTitle.textContent = data.data.judul;
                        modalBody.innerHTML = data.data.isi;
                        showModal('detailModal');
                    }
                } else {
                    showNotification('Gagal mengambil data konten', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengambil data', 'error');
            });
    }

    function confirmDelete(id) {
        deleteId = id;
        const deleteMessage = document.getElementById('deleteMessage');
        if (deleteMessage) {
            deleteMessage.textContent = 'Apakah kamu yakin menghapus konten ini?';
        }
        showModal('deleteModal');
    }

    function deleteContent() {
        if (!deleteId) {
            showNotification('Tidak ada konten yang dipilih untuk dihapus', 'error');
            return;
        }

        showLoading();
        
        const url = `/admin/education/${deleteId}`;
        
        // Gunakan FormData untuk kompatibilitas Laravel
        const formData = new FormData();
        formData.append('_token', getCsrfToken());
        formData.append('_method', 'DELETE');

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            if (response.status === 419) {
                throw new Error('CSRF_TOKEN_MISMATCH');
            }
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            hideLoading();
            if (data.success) {
                handleSuccessDelete();
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message || 'Terjadi kesalahan saat menghapus', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error details:', error);
            
            if (error.message === 'CSRF_TOKEN_MISMATCH') {
                showNotification('Session expired. Silakan refresh halaman dan coba lagi.', 'error');
                setTimeout(() => {
                    location.reload();
                }, 3000);
            } else {
                showNotification('Terjadi kesalahan saat menghapus konten: ' + error.message, 'error');
            }
        });
    }

    function getCsrfToken() {
        // Coba beberapa cara untuk mendapatkan CSRF token
        let token = '';
        
        // Cara 1: Dari meta tag
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            token = metaTag.getAttribute('content');
        }
        
        // Cara 2: Dari input hidden (fallback)
        if (!token) {
            const csrfInput = document.querySelector('input[name="_token"]');
            if (csrfInput) {
                token = csrfInput.value;
            }
        }
        
        // Cara 3: Dari window.Laravel (jika menggunakan Laravel default)
        if (!token && window.Laravel && window.Laravel.csrfToken) {
            token = window.Laravel.csrfToken;
        }
        
        console.log('CSRF Token found:', token ? 'Yes' : 'No');
        return token;
    }

    function handleSuccessDelete() {
        // Remove single row
        const row = document.querySelector(`tr[data-id="${deleteId}"]`);
        if (row) row.remove();
        
        closeModal('deleteModal');
        
        // Reload if no content left
        const remainingRows = document.querySelectorAll('#educationTable tbody tr');
        const hasContent = Array.from(remainingRows).some(row => !row.querySelector('td[colspan]'));
        
        if (!hasContent) {
            setTimeout(() => location.reload(), 1500);
        }
    }

    function showLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex';
        }
    }

    function hideLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    }

    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
        }
        if (modalId === 'deleteModal') {
            deleteId = null;
        }
    }

    function showNotification(message, type) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(notification => notification.remove());

        const notification = document.createElement('div');
        notification.className = `custom-notification alert-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            max-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;

        if (type === 'success') {
            notification.style.background = '#28a745';
        } else {
            notification.style.background = '#dc3545';
        }

        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 4000);
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('active');
            if (event.target.id === 'deleteModal') {
                deleteId = null;
            }
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal('detailModal');
            closeModal('deleteModal');
        }
    });
</script>
@endsection