@extends('layouts.app')

@section('title', 'Tambah Konten Edukasi - Re-Glow')

@section('styles')
<style>
    .form-container {
        max-width: 900px;
        margin: 3rem auto;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--pink-light);
    }

    .form-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--green-dark);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--green-dark);
        margin-bottom: 0.5rem;
    }

    .form-label.required::after {
        content: " *";
        color: #dc3545;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--pink-base);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .editor-container {
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        min-height: 400px;
    }

    .form-text {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.85rem;
        color: var(--text-gray);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid var(--pink-light);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-primary {
        background: var(--green-dark);
        color: white;
    }

    .btn-primary:hover {
        background: #16332d;
    }

    .btn-secondary {
        background: var(--green-light);
        color: var(--green-dark);
    }

    .btn-secondary:hover {
        background: #A8B399;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .file-preview {
        margin-top: 0.5rem;
    }

    .file-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 6px;
        border: 2px solid #e0e0e0;
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1>Tambah Konten Edukasi Baru</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('education.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="judul" class="form-label required">Judul Artikel</label>
            <input type="text" 
                   class="form-control @error('judul') is-invalid @enderror" 
                   id="judul" 
                   name="judul" 
                   value="{{ old('judul') }}"
                   required>
            @error('judul')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="penulis" class="form-label required">Penulis</label>
            <input type="text" 
                   class="form-control @error('penulis') is-invalid @enderror" 
                   id="penulis" 
                   name="penulis" 
                   value="{{ old('penulis', Auth::user()->username ?? '') }}"
                   required>
            @error('penulis')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="ringkasan" class="form-label">Ringkasan</label>
            <textarea class="form-control @error('ringkasan') is-invalid @enderror" 
                      id="ringkasan" 
                      name="ringkasan" 
                      rows="3">{{ old('ringkasan') }}</textarea>
            <small class="form-text">Deskripsi singkat yang akan ditampilkan di katalog (maks. 1000 karakter)</small>
            @error('ringkasan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="isi" class="form-label required">Isi Artikel</label>
            <textarea class="form-control @error('isi') is-invalid @enderror" 
                      id="isi" 
                      name="isi" 
                      rows="15"
                      required>{{ old('isi') }}</textarea>
            <small class="form-text">Anda bisa menggunakan HTML untuk formatting</small>
            @error('isi')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="gambar_cover" class="form-label">Gambar Cover</label>
            <input type="file" 
                   class="form-control @error('gambar_cover') is-invalid @enderror" 
                   id="gambar_cover" 
                   name="gambar_cover" 
                   accept="image/*"
                   onchange="previewImage(this)">
            <small class="form-text">Format: JPG, PNG (Maks. 2MB)</small>
            @error('gambar_cover')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            <div id="imagePreview" class="file-preview" style="display:none;">
                <img id="preview" src="" alt="Preview">
            </div>
        </div>

        <div class="form-group">
            <label for="waktu_baca" class="form-label">Estimasi Waktu Baca (menit)</label>
            <input type="number" 
                   class="form-control @error('waktu_baca') is-invalid @enderror" 
                   id="waktu_baca" 
                   name="waktu_baca" 
                   min="1" 
                   value="{{ old('waktu_baca', 5) }}">
            @error('waktu_baca')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status" class="form-label required">Status</label>
            <select class="form-control @error('status') is-invalid @enderror" 
                    id="status" 
                    name="status" 
                    required>
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
            </select>
            @error('status')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Konten</button>
            <a href="{{ route('education.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection