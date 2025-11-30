@extends('layouts.app')

@section('title', 'Edit Konten Edukasi - Re-Glow')

@section('styles')
    @vite(['resources/css/admin/education/create.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <div class="header">
        <div class="container" style="margin: 0;">
            <a href="{{ route('admin.education.index') }}" class="back-button">
                <i class="bi bi-arrow-left"></i> Back to Management
            </a>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">
            <i class="bi bi-pencil"></i> Edit Content
        </h1>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.education.update', $konten->id_konten) }}" method="POST" enctype="multipart/form-data" id="educationForm" class="form-container">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-type"></i> Judul
                    </label>
                    <input type="text" name="judul" class="form-input" value="{{ old('judul', $konten->judul) }}" required placeholder="Masukkan judul konten">
                    <span class="form-error" id="error-judul">Judul tidak boleh kosong</span>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-person"></i> Penulis
                    </label>
                    <input type="text" name="penulis" class="form-input" value="{{ old('penulis', $konten->penulis) }}" required placeholder="Nama penulis">
                    <span class="form-error" id="error-penulis">Nama penulis tidak boleh kosong</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-text-paragraph"></i> Ringkasan
                </label>
                <textarea name="ringkasan" class="form-textarea" required placeholder="Tulis ringkasan singkat tentang konten ini">{{ old('ringkasan', $konten->ringkasan) }}</textarea>
                <span class="form-error" id="error-ringkasan">Ringkasan tidak boleh kosong</span>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-image"></i> Foto header (Cover Image)
                </label>
                
                @if($konten->gambar_cover)
                <div class="current-image-section" style="margin-bottom: 1rem;">
                    <label class="form-label" style="color: #6c757d; font-size: 0.875rem;">
                        <i class="bi bi-image-fill"></i> Gambar Saat Ini
                    </label>
                    <div class="file-preview show">
                        <img src="{{ asset('storage/' . $konten->gambar_cover) }}" alt="Current cover">
                    </div>
                    <div style="font-size: 0.875rem; color: #6c757d; margin-top: 0.5rem;">
                        Kosongkan jika ingin mengganti gambar
                    </div>
                </div>
                @endif

                <div class="file-input-wrapper">
                    <label for="gambar_cover" class="file-input-label" id="fileLabel">
                        <div>
                            <i class="bi bi-cloud-arrow-up" style="font-size: 2rem; margin-bottom: 0.5rem; color: #6c757d;"></i>
                            <div>Click to upload new image</div>
                            <div style="font-size: 0.875rem; color: #6c757d; margin-top: 0.25rem;">JPEG, PNG, JPG (Max 5MB)</div>
                        </div>
                    </label>
                    <input type="file" id="gambar_cover" name="gambar_cover" class="file-input" accept="image/*">
                </div>
                <span class="form-error" id="error-gambar_cover">Format file tidak valid atau ukuran terlalu besar</span>
                <div class="file-preview" id="imagePreview">
                    <button type="button" class="delete-image-btn" id="deleteImageBtn" title="Batalkan unggahan">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-file-text"></i> Isi Konten
                </label>
                <div class="editor-wrapper">
                    <!-- Toolbar -->
                    <div class="editor-toolbar">
                        <button type="button" class="editor-btn" data-command="bold" title="Bold (Ctrl+B)">
                            <i class="bi bi-type-bold"></i>
                        </button>
                        <button type="button" class="editor-btn" data-command="italic" title="Italic (Ctrl+I)">
                            <i class="bi bi-type-italic"></i>
                        </button>
                        <button type="button" class="editor-btn" data-command="underline" title="Underline (Ctrl+U)">
                            <i class="bi bi-type-underline"></i>
                        </button>

                        <div class="editor-separator"></div>

                        <button type="button" class="editor-btn" data-command="justifyLeft" title="Rata Kiri">
                            <i class="bi bi-text-left"></i>
                        </button>
                        <button type="button" class="editor-btn" data-command="justifyCenter" title="Rata Tengah">
                            <i class="bi bi-text-center"></i>
                        </button>
                        <button type="button" class="editor-btn" data-command="justifyRight" title="Rata Kanan">
                            <i class="bi bi-text-right"></i>
                        </button>
                        <button type="button" class="editor-btn" data-command="justifyFull" title="Rata Kiri-Kanan">
                            <i class="bi bi-justify"></i>
                        </button>

                        <div class="editor-separator"></div>

                        <button type="button" class="editor-btn" data-command="insertOrderedList" title="Daftar Bernomor">
                            <i class="bi bi-list-ol"></i>
                        </button>
                        <button type="button" class="editor-btn" data-command="insertUnorderedList" title="Daftar Bullet">
                            <i class="bi bi-list-ul"></i>
                        </button>

                        <div class="editor-separator"></div>

                        <button type="button" class="editor-btn" data-command="createLink" title="Sisipkan Link">
                            <i class="bi bi-link"></i>
                        </button>
                    </div>

                    <!-- Editor Content -->
                    <div 
                        id="editor" 
                        class="editor-content" 
                        contenteditable="true" 
                        data-placeholder="Mulai menulis postingan Anda di sini..."
                    >{!! old('isi', $konten->isi) !!}</div>

                    <!-- Hidden input untuk menyimpan HTML content -->
                    <input type="hidden" name="isi" id="isi" value="{{ old('isi', $konten->isi) }}" required>
                </div>
                <span class="form-error" id="error-isi">Isi konten tidak boleh kosong</span>
            </div>

            <!-- Waktu Baca Section -->
            <div class="waktu-baca-section">
                <div class="waktu-baca-content">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-clock" style="font-size: 1.25rem; color: #20413A;"></i>
                        <div>
                            <div class="form-label" style="margin: 0; font-size: 1rem;">Waktu Baca</div>
                        </div>
                    </div>
                    <div class="waktu-baca-input">
                        <input type="number" name="waktu_baca" class="form-input" min="1" max="120" value="{{ old('waktu_baca', $konten->waktu_baca) }}" required style="max-width: 80px;">
                        <span style="color: #20413A; font-weight: 500; white-space: nowrap;">menit</span>
                    </div>
                </div>
                <span class="form-error" id="error-waktu_baca">Waktu baca harus diisi (1-120 menit)</span>
            </div>

            <div class="form-actions" style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn-submit" style="flex: 2;">
                    <i class="bi bi-check-lg"></i> Update Content
                </button>
                <a href="{{ route('admin.education.index') }}" class="btn-submit" style="flex: 1; background: #6c757d; text-decoration: none; text-align: center;">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Custom Editor Implementation
    const editor = document.getElementById('editor');
    const hiddenInput = document.getElementById('isi');
    const toolbar = document.querySelector('.editor-toolbar');

    // Execute formatting command
    function executeCommand(command, value = null) {
        document.execCommand(command, false, value);
        editor.focus();
        updateHiddenInput();
    }

    // Update hidden input with editor content
    function updateHiddenInput() {
        hiddenInput.value = editor.innerHTML;
    }

    // Initialize editor with existing content
    document.addEventListener('DOMContentLoaded', function() {
        updateHiddenInput();
    });

    // Toolbar button handlers
    toolbar.addEventListener('click', (e) => {
        const button = e.target.closest('.editor-btn');
        if (!button) return;

        e.preventDefault();
        const command = button.dataset.command;

        if (command === 'createLink') {
            const url = prompt('Masukkan URL link:');
            if (url) executeCommand(command, url);
        } else {
            executeCommand(command);
        }

        // Toggle active state for formatting buttons
        if (['bold', 'italic', 'underline'].includes(command)) {
            button.classList.toggle('active');
        }
    });

    // Update hidden input on content change
    editor.addEventListener('input', updateHiddenInput);
    editor.addEventListener('paste', () => {
        setTimeout(updateHiddenInput, 10);
    });

    // Keyboard shortcuts
    editor.addEventListener('keydown', (e) => {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key.toLowerCase()) {
                case 'b':
                    e.preventDefault();
                    executeCommand('bold');
                    break;
                case 'i':
                    e.preventDefault();
                    executeCommand('italic');
                    break;
                case 'u':
                    e.preventDefault();
                    executeCommand('underline');
                    break;
            }
        }
    });

    // File upload preview
    const coverInput = document.getElementById('gambar_cover');
    const preview = document.getElementById('imagePreview');
    const label = document.getElementById('fileLabel');

    function resetCoverImage() {
        coverInput.value = '';
        preview.innerHTML = `<button type="button" class="delete-image-btn" id="deleteImageBtn" title="Batalkan unggahan">
            <i class="bi bi-x"></i>
        </button>`;
        preview.classList.remove('show');
        label.style.display = 'flex';
        
        // Re-bind listener
        document.getElementById('deleteImageBtn').addEventListener('click', resetCoverImage);
    }

    coverInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="delete-image-btn" id="deleteImageBtn" title="Batalkan unggahan">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                preview.classList.add('show');
                label.style.display = 'none';
                
                // Re-bind listener
                document.getElementById('deleteImageBtn').addEventListener('click', resetCoverImage);
            };
            reader.readAsDataURL(file);
        }
    });

    // Initialize delete button
    document.getElementById('deleteImageBtn').addEventListener('click', resetCoverImage);

    // Form validation
    document.getElementById('educationForm').addEventListener('submit', function(e) {
        let isValid = true;

        // Clear previous errors
        document.querySelectorAll('.form-error').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.form-input, .form-textarea').forEach(el => el.classList.remove('error'));
        document.getElementById('fileLabel').classList.remove('error');

        // Validate judul
        const judul = document.querySelector('[name="judul"]');
        if (!judul.value.trim()) {
            judul.classList.add('error');
            document.getElementById('error-judul').classList.add('show');
            isValid = false;
        }

        // Validate ringkasan
        const ringkasan = document.querySelector('[name="ringkasan"]');
        if (!ringkasan.value.trim()) {
            ringkasan.classList.add('error');
            document.getElementById('error-ringkasan').classList.add('show');
            isValid = false;
        }

        // Validate gambar_cover (optional for edit)
        const gambar = document.getElementById('gambar_cover');
        if (gambar.files.length > 0) {
            const file = gambar.files[0];
            if (file.size > 5 * 1024 * 1024) {
                document.getElementById('fileLabel').classList.add('error');
                document.getElementById('error-gambar_cover').classList.add('show');
                isValid = false;
            }
        }

        // Validate isi (editor content)
        updateHiddenInput();
        if (!hiddenInput.value.trim() || hiddenInput.value === '<br>') {
            document.getElementById('error-isi').classList.add('show');
            isValid = false;
        }

        // Validate penulis
        const penulis = document.querySelector('[name="penulis"]');
        if (!penulis.value.trim()) {
            penulis.classList.add('error');
            document.getElementById('error-penulis').classList.add('show');
            isValid = false;
        }

        // Validate waktu_baca
        const waktuBaca = document.querySelector('[name="waktu_baca"]');
        if (!waktuBaca.value || waktuBaca.value < 1 || waktuBaca.value > 120) {
            waktuBaca.classList.add('error');
            document.getElementById('error-waktu_baca').classList.add('show');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
</script>
@endsection