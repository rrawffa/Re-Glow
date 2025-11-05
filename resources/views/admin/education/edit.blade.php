@extends('layouts.app')

@section('title', 'Edit Konten - Re-Glow')
<!-- resources\admin\education\edit.blade.php -->
@section('styles')
    @vite(['resources/css/education/credit.css'])
@endsection

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1>Edit Konten Edukasi</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.education.update', $konten->id_konten) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Same form fields as create, but with values from $konten --}}
        <div class="form-group">
            <label for="judul" class="form-label required">Judul Artikel</label>
            <input type="text" 
                   class="form-control" 
                   id="judul" 
                   name="judul" 
                   value="{{ old('judul', $konten->judul) }}"
                   required>
        </div>

        {{-- Continue with other fields... --}}

        @if($konten->gambar_cover)
        <div class="form-group">
            <label class="form-label">Gambar Cover Saat Ini</label>
            <div class="file-preview">
                <img src="{{ asset('storage/' . $konten->gambar_cover) }}" alt="Current cover">
            </div>
        </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Konten</button>
            <a href="{{ route('education.show', $konten->id_konten) }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
