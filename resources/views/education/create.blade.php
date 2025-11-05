@extends('layouts.app')

@section('title', 'Tambah Konten Edukasi - Re-Glow')

@section('styles')
    @vite(['resources/css/education/credit.css'])
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
@endsection

@section('scripts')
    @vite(['resources/js/education/create.js'])
@endsection