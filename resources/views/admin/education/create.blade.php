@extends('layouts.app')
<!-- resources\admin\education\create.blade.php -->
@section('title', 'Admin - Tambah Konten Edukasi - Re-Glow')

@section('styles')
    @vite(['resources/css/admin/education/create.css'])
    <style>
        .tox {
            width: 100% !important; 
            border-radius: 8px !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            /* Memastikan editor terlihat seperti input form */
            border-width: 2px !important; 
            border-style: solid !important;
            border-color: #e9ecef !important;
        }
        /* Mengganti border fokus standar TinyMCE */
        .tox:focus-within {
             border-color: #F9B6C7 !important; 
        }
        .tox .tox-toolbar-overlord {
            border-radius: 8px 8px 0 0; /* Membuat sudut toolbar atas melengkung */
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <a href="{{ route('admin.education.index') }}" class="back-link"> 
            ← Back to Management
        </a>
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
                <div class="form-group col-70">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-input" value="{{ old('judul') }}" required>
                    <span class="form-error" id="error-judul">Judul tidak boleh kosong</span>
                </div>
                <div class="form-group col-30">
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
                <div class="file-preview" id="imagePreview"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Isi Konten</label>
                <textarea id="isi" name="isi" required>{{ old('isi') }}</textarea> 
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
<script src="https://cdn.tiny.mce.com/1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '#isi',
            height: 500,
            menubar: false, 
            
            // Plugins dan Toolbar lengkap (Bold, Italic, Underline, Rata Teks, Penomoran/Lists, Gambar)
            plugins: 'lists link image code table media wordcount charmap anchor autosave', 
            toolbar: 'undo redo | formatselect | styleselect | ' + 
                     'bold italic underline strikethrough | forecolor backcolor | ' + 
                     'alignleft aligncenter alignright alignjustify | ' + 
                     'bullist numlist outdent indent | link image media table | code',
                     
            content_style: 'body { font-family: DM Sans, sans-serif; font-size: 16px; }',
            font_formats: 'DM Sans=DM Sans, sans-serif; Arial=arial,helvetica,sans-serif; Tahoma=tahoma,arial,helvetica,sans-serif;',
            
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });

        // File upload preview
        document.getElementById('gambar_cover').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            const label = document.getElementById('fileLabel');
            
            if (file) {
                // Validate file size (5MB)
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

            // Validate isi (TinyMCE)
            tinymce.triggerSave();
            const isi = document.querySelector('[name="isi"]');
            if (!isi.value.trim() || isi.value === '<p><br></p>') {
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