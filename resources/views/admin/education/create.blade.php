@extends('layouts.app')
<!-- resources\admin\education\create.blade.php -->
@section('title', 'Admin - Tambah Konten Edukasi - Re-Glow')

@section('styles')
    @vite(['resources/css/admin/education/create.css'])
@endsection

@section('content')
    <div class="header">
        <div class="container" style="margin: 0;">
            <a href="{{ route('admin.education.index') }}" class="back-button">
                ← Back to Management
            </a>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">Create new Content</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.education.store') }}" method="POST" enctype="multipart/form-data" id="educationForm" class="form-container">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-input" value="{{ old('judul') }}" required>
                    <span class="form-error" id="error-judul">Judul tidak boleh kosong</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Penulis</label>
                    <input type="text" name="penulis" class="form-input" value="{{ old('penulis') }}" required>
                    <span class="form-error" id="error-penulis">Nama penulis tidak boleh kosong</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Ringkasan</label>
                <textarea name="ringkasan" class="form-textarea" required>{{ old('ringkasan') }}</textarea>
                <span class="form-error" id="error-ringkasan">Ringkasan tidak boleh kosong</span>
            </div>

            <div class="form-group">
                <label class="form-label">Foto header (Cover Image)</label>
                <div class="file-input-wrapper">
                    <label for="gambar_cover" class="file-input-label" id="fileLabel">
                        <div>
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📷</div>
                            <div>Click to upload image</div>
                            <div style="font-size: 0.875rem; color: #6c757d; margin-top: 0.25rem;">JPEG, PNG, JPG (Max 5MB)</div>
                        </div>
                    </label>
                    <input type="file" id="gambar_cover" name="gambar_cover" class="file-input" accept="image/*" required>
                </div>
                <span class="form-error" id="error-gambar_cover">Foto header harus diupload</span>
                <div class="file-preview" id="imagePreview">
                    <button type="button" class="delete-image-btn" id="deleteImageBtn" title="Batalkan unggahan">×</button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Isi Konten</label>
                <div class="editor-wrapper">
                    <!-- Toolbar -->
                    <div class="editor-toolbar">
                        <button type="button" class="editor-btn" data-command="bold" title="Bold (Ctrl+B)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path>
                                <path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"></path>
                            </svg>
                        </button>
                        <button type="button" class="editor-btn" data-command="italic" title="Italic (Ctrl+I)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="4" x2="10" y2="4"></line>
                                <line x1="14" y1="20" x2="5" y2="20"></line>
                                <line x1="15" y1="4" x2="9" y2="20"></line>
                            </svg>
                        </button>
                        <button type="button" class="editor-btn" data-command="underline" title="Underline (Ctrl+U)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"></path>
                                <line x1="4" y1="21" x2="20" y2="21"></line>
                            </svg>
                        </button>

                        <div class="editor-separator"></div>

                        <button type="button" class="editor-btn" data-command="justifyLeft" title="Rata Kiri">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="17" y1="10" x2="3" y2="10"></line>
                                <line x1="21" y1="6" x2="3" y2="6"></line>
                                <line x1="21" y1="14" x2="3" y2="14"></line>
                                <line x1="17" y1="18" x2="3" y2="18"></line>
                            </svg>
                        </button>
                        <button type="button" class="editor-btn" data-command="justifyCenter" title="Rata Tengah">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="10" x2="6" y2="10"></line>
                                <line x1="21" y1="6" x2="3" y2="6"></line>
                                <line x1="21" y1="14" x2="3" y2="14"></line>
                                <line x1="18" y1="18" x2="6" y2="18"></line>
                            </svg>
                        </button>
                        <button type="button" class="editor-btn" data-command="justifyRight" title="Rata Kanan">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="21" y1="10" x2="7" y2="10"></line>
                                <line x1="21" y1="6" x2="3" y2="6"></line>
                                <line x1="21" y1="14" x2="3" y2="14"></line>
                                <line x1="21" y1="18" x2="7" y2="18"></line>
                            </svg>
                        </button>
                        <button type="button" class="editor-btn" data-command="justifyFull" title="Rata Kiri-Kanan">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="21" y1="10" x2="3" y2="10"></line>
                                <line x1="21" y1="6" x2="3" y2="6"></line>
                                <line x1="21" y1="14" x2="3" y2="14"></line>
                                <line x1="21" y1="18" x2="3" y2="18"></line>
                            </svg>
                        </button>

                        <div class="editor-separator"></div>

                        <button type="button" class="editor-btn" data-command="insertOrderedList" title="Daftar Bernomor">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="10" y1="6" x2="21" y2="6"></line>
                                <line x1="10" y1="12" x2="21" y2="12"></line>
                                <line x1="10" y1="18" x2="21" y2="18"></line>
                                <path d="M4 6h1v4"></path>
                                <path d="M4 10h2"></path>
                                <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path>
                            </svg>
                        </button>
                        <button type="button" class="editor-btn" data-command="insertUnorderedList" title="Daftar Bullet">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                <line x1="3" y1="18" x2="3.01" y2="18"></line>
                            </svg>
                        </button>

                        <div class="editor-separator"></div>

                        <button type="button" class="editor-btn" data-command="createLink" title="Sisipkan Link">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                        </button>
                        <button type="button" class="editor-btn" data-command="insertImage" title="Sisipkan Gambar">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </button>
                    </div>

                    <!-- Editor Content -->
                    <div 
                        id="editor" 
                        class="editor-content" 
                        contenteditable="true" 
                        data-placeholder="Mulai menulis postingan Anda di sini..."
                    ></div>

                    <!-- Hidden input untuk menyimpan HTML content -->
                    <input type="hidden" name="isi" id="isi" required>
                </div>
                <span class="form-error" id="error-isi">Isi konten tidak boleh kosong</span>
            </div>

            <div class="form-group" style="max-width: 300px; margin-left: auto;">
                <label class="form-label">Waktu Baca (menit)</label>
                <input type="number" name="waktu_baca" class="form-input" min="1" value="{{ old('waktu_baca', 5) }}" required>
                <span class="form-error" id="error-waktu_baca">Waktu baca harus diisi</span>
            </div>

            <button type="submit" class="btn-submit">Publish</button>
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

        // Toolbar button handlers
        toolbar.addEventListener('click', (e) => {
            const button = e.target.closest('.editor-btn');
            if (!button) return;

            e.preventDefault();
            const command = button.dataset.command;

            if (command === 'createLink') {
                const url = prompt('Masukkan URL link:');
                if (url) executeCommand(command, url);
            } else if (command === 'insertImage') {
                const url = prompt('Masukkan URL gambar:');
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
        document.getElementById('gambar_cover').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            const label = document.getElementById('fileLabel');

            function resetCoverImage() {
            coverInput.value = ''; // Kosongkan input file
            preview.innerHTML = `<button type="button" class="delete-image-btn" id="deleteImageBtn">×</button>`; // Kosongkan konten gambar
            preview.classList.remove('show');
            label.style.display = 'flex'; // Tampilkan kembali label upload
            
            // Re-bind listener karena innerHTML telah diubah
            document.getElementById('deleteImageBtn').addEventListener('click', resetCoverImage);
        }
        
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    preview.classList.add('show');
                    label.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

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

            // Validate gambar_cover
            const gambar = document.getElementById('gambar_cover');
            if (!gambar.files.length) {
                document.getElementById('fileLabel').classList.add('error');
                document.getElementById('error-gambar_cover').classList.add('show');
                isValid = false;
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
            if (!waktuBaca.value || waktuBaca.value < 1) {
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