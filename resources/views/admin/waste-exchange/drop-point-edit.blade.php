@extends('layouts.app')

@section('title', 'Edit Drop Point - Re-Glow Admin')

@section('styles')
    @vite(['resources/css/admin/waste-exchange/drop-point-edit.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <div class="drop-point-edit-header">
        <div class="container" style="margin: 0; max-width: none;">
            <a href="{{ route('admin.waste.droppoint.index') }}" class="drop-point-edit-back-button">
                <i class="bi bi-arrow-left"></i> Back to Drop Point List
            </a>
        </div>
    </div>

    <div class="drop-point-edit-container">
        <h1 class="drop-point-edit-page-title">
            <i class="bi bi-pencil"></i> Edit Drop Point
        </h1>

        @if($errors->any())
            <div class="drop-point-edit-alert drop-point-edit-alert-danger">
                <p style="font-weight: 600; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-exclamation-triangle"></i> Ups! Ada beberapa kesalahan validasi:
                </p>
                <ul style="margin-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.waste.droppoint.update', $dropPoint->id_drop_point) }}" method="POST" id="dropPointForm" class="drop-point-edit-form-container">
            @csrf
            @method('PUT')

            <div class="drop-point-edit-form-row">
                <div class="drop-point-edit-form-group">
                    <label class="drop-point-edit-form-label">
                        <i class="bi bi-geo-alt"></i> Nama Lokasi
                    </label>
                    <input type="text" name="nama_lokasi" class="drop-point-edit-form-input" value="{{ old('nama_lokasi', $dropPoint->nama_lokasi) }}" required>
                    <span class="drop-point-edit-form-error" id="error-nama_lokasi">Nama lokasi tidak boleh kosong</span>
                </div>

                <div class="drop-point-edit-form-group">
                    <label class="drop-point-edit-form-label">
                        <i class="bi bi-geo"></i> Koordinat
                    </label>
                    <input type="text" name="koordinat" class="drop-point-edit-form-input" placeholder="contoh: -6.2088, 106.8456" value="{{ old('koordinat', $dropPoint->koordinat) }}" required>
                    <span class="drop-point-edit-form-error" id="error-koordinat">Koordinat tidak boleh kosong</span>
                </div>
            </div>

            <div class="drop-point-edit-form-group">
                <label class="drop-point-edit-form-label">
                    <i class="bi bi-speedometer2"></i> Kapasitas Sampah (kg)
                </label>
                    <input type="number" name="kapasitas_sampah" class="drop-point-edit-form-input" step="0.1" min="0" value="{{ old('kapasitas_sampah', $dropPoint->kapasitas_sampah) }}" required>
                <span class="drop-point-edit-form-error" id="error-kapasitas_sampah">Kapasitas sampah tidak boleh kosong</span>
            </div>

            <div class="drop-point-edit-form-group">
                <label class="drop-point-edit-form-label">
                    <i class="bi bi-house"></i> Alamat
                </label>
                <textarea name="alamat" class="drop-point-edit-form-textarea" required>{{ old('alamat', $dropPoint->alamat) }}</textarea>
                <span class="drop-point-edit-form-error" id="error-alamat">Alamat tidak boleh kosong</span>
            </div>

            <button type="submit" class="drop-point-edit-btn-submit">
                <i class="bi bi-check-lg"></i> Update
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('dropPointForm').addEventListener('submit', function(e) {
            let isValid = true;

            // Clear previous errors
            document.querySelectorAll('.drop-point-edit-form-error').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.drop-point-edit-form-input, .drop-point-edit-form-textarea').forEach(el => el.classList.remove('error'));

            // Validate nama_lokasi
            const namaLokasi = document.querySelector('[name="nama_lokasi"]');
            if (!namaLokasi.value.trim()) {
                namaLokasi.classList.add('error');
                document.getElementById('error-nama_lokasi').classList.add('show');
                isValid = false;
            }

            // Validate koordinat
            const koordinat = document.querySelector('[name="koordinat"]');
            if (!koordinat.value.trim()) {
                koordinat.classList.add('error');
                document.getElementById('error-koordinat').classList.add('show');
                isValid = false;
            }

            // Validate kapasitas_sampah
            const kapasitas = document.querySelector('[name="kapasitas_sampah"]');
            if (!kapasitas.value || kapasitas.value <= 0) {
                kapasitas.classList.add('error');
                document.getElementById('error-kapasitas_sampah').classList.add('show');
                isValid = false;
            }

            // Validate alamat
            const alamat = document.querySelector('[name="alamat"]');
            if (!alamat.value.trim()) {
                alamat.classList.add('error');
                document.getElementById('error-alamat').classList.add('show');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    </script>
@endsection