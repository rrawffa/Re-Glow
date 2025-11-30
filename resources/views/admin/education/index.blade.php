@extends('layouts.app')

@section('title', 'Admin - Dash Edu - Re-Glow')

@section('styles')
    @vite(['resources/css/admin/education/index.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <!-- Main Content -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-book"></i> Education Content Management
            </h1>
            <div class="header-actions">
                <a href="{{ route('admin.education.create') }}" class="btn-create">
                    <i class="bi bi-plus-lg"></i> Create New Content
                </a>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table id="educationTable">
                    <thead>
                        <tr>
                            <th>
                                <i class="bi bi-type"></i> Title
                            </th>
                            <th>
                                <i class="bi bi-person"></i> Author
                            </th>
                            <th class="uplod">
                                <i class="bi bi-calendar"></i> Upload Date
                            </th>
                            <th>
                                <i class="bi bi-heart"></i> Reactions
                            </th>
                            <th>
                                <i class="bi bi-activity"></i> Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($konten as $item)
                        <tr data-id="{{ $item->id_konten }}">
                            <td>
                                <strong>{{ Str::limit($item->judul, 50) }}</strong>
                            </td>
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
                                    <button class="btn-action btn-detail" onclick="showDetail({{ $item->id_konten }})" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.education.edit', $item->id_konten) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn-action btn-delete" onclick="confirmDelete({{ $item->id_konten }})" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada konten edukasi</p>
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
                <h2 class="modal-title" id="modalTitle">
                    <i class="bi bi-info-circle"></i> Detail Konten
                </h2>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal confirm-modal">
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
                <p id="deleteMessage">Apakah kamu yakin menghapus konten ini?</p>
                <div class="confirm-buttons">
                    <button class="btn-confirm btn-yes" id="confirmDeleteBtn">
                        <i class="bi bi-check-lg"></i> Iya
                    </button>
                    <button class="btn-confirm btn-no" onclick="closeModal('deleteModal')">
                        <i class="bi bi-x-lg"></i> Tidak
                    </button>
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
                        modalTitle.innerHTML = `<i class="bi bi-info-circle"></i> ${data.data.judul}`;
                        
                        let htmlContent = `
                            <div class="detail-header" style="margin-bottom: 2rem;">
                                <div class="detail-meta" style="display: flex; gap: 2rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="bi bi-person" style="color: #6c757d;"></i>
                                        <span style="color: #20413A; font-weight: 500;">${data.data.penulis}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="bi bi-calendar" style="color: #6c757d;"></i>
                                        <span style="color: #20413A; font-weight: 500;">${new Date(data.data.tanggal_upload).toLocaleDateString('id-ID', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        })}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="bi bi-clock" style="color: #6c757d;"></i>
                                        <span style="color: #20413A; font-weight: 500;">${data.data.waktu_baca} menit</span>
                                    </div>
                                </div>
                        `;

                        // Tampilkan ringkasan
                        if (data.data.ringkasan) {
                            htmlContent += `
                                <div class="ringkasan-section" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 0.5 rem; border-left: 4px solid #F9B6C7;">
                                    <h3 style="font-family: 'Bricolage Grotesque', sans-serif; color: #20413A; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                        Ringkasan
                                    </h3>
                                    <p style="color: #495057; line-height: 1.6; margin: 0;">${data.data.ringkasan}</p>
                                </div>
                            `;
                        }

                        htmlContent += `</div>`; // Tutup detail-header

                        // Tampilkan isi konten
                        htmlContent += `
                            <div class="content-section">
                                <h3 style="font-family: 'Bricolage Grotesque', sans-serif; color: #20413A; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="bi bi-file-text"></i> Isi Konten
                                </h3>
                                <div class="content-body" style="line-height: 1.8; color: #495057;">
                                    ${data.data.isi}
                                </div>
                            </div>
                        `;

                        modalBody.innerHTML = htmlContent;
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
        notification.className = `custom-notification`;
        notification.innerHTML = `
            <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'}"></i>
            ${message}
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