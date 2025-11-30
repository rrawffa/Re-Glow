@extends('layouts.app')

@section('title', 'Add New Drop Point - Re-Glow Admin')

@section('styles')
    @vite(['resources/css/admin/waste-exchange/drop-point-create.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <div class="drop-point-create-header">
        <div class="container" style="margin: 0; max-width: none;">
            <a href="{{ route('admin.waste.droppoint.index') }}" class="drop-point-create-back-button">
                <i class="bi bi-arrow-left"></i> Back to Drop Point List
            </a>
        </div>
    </div>

    <div class="drop-point-create-container">
        <h1 class="drop-point-create-page-title">
            <i class="bi bi-plus-circle"></i> Add New Drop Point
        </h1>

        @if($errors->any())
            <div class="drop-point-create-alert drop-point-create-alert-danger">
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

        <form action="{{ route('admin.waste.droppoint.store') }}" method="POST" id="dropPointForm" class="drop-point-create-form-container">
            @csrf

            <div class="drop-point-create-form-row">
                <div class="drop-point-create-form-group">
                    <label class="drop-point-create-form-label">
                        <i class="bi bi-geo-alt"></i> Nama Lokasi
                    </label>
                    <input type="text" name="nama_lokasi" class="drop-point-create-form-input" value="{{ old('nama_lokasi') }}" required placeholder="Masukkan nama lokasi">
                    <span class="drop-point-create-form-error" id="error-nama_lokasi">Nama lokasi tidak boleh kosong</span>
                </div>

                <div class="drop-point-create-form-group">
                    <label class="drop-point-create-form-label">
                        <i class="bi bi-geo"></i> Koordinat
                    </label>
                    <input type="text" name="koordinat" class="drop-point-create-form-input" placeholder="contoh: -6.2088, 106.8456" value="{{ old('koordinat') }}" required>
                    <span class="drop-point-create-form-error" id="error-koordinat">Koordinat tidak boleh kosong</span>
                </div>
            </div>

            <div class="drop-point-create-form-group">
                <label class="drop-point-create-form-label">
                    <i class="bi bi-speedometer2"></i> Kapasitas Sampah (kg)
                </label>
                <input type="number" name="kapasitas_sampah" class="drop-point-create-form-input" step="0.1" min="0" value="{{ old('kapasitas_sampah') }}" required placeholder="Masukkan kapasitas dalam kg">
                <span class="drop-point-create-form-error" id="error-kapasitas_sampah">Kapasitas sampah tidak boleh kosong</span>
            </div>

            <div class="drop-point-create-form-group">
                <label class="drop-point-create-form-label">
                    <i class="bi bi-house"></i> Alamat
                </label>
                <textarea name="alamat" class="drop-point-create-form-textarea" required placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                <span class="drop-point-create-form-error" id="error-alamat">Alamat tidak boleh kosong</span>
            </div>

            <button type="submit" class="drop-point-create-btn-submit">
                <i class="bi bi-check-lg"></i> Kirim
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('dropPointForm').addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = [
                { name: 'nama_lokasi', message: 'Nama lokasi tidak boleh kosong' },
                { name: 'koordinat', message: 'Koordinat tidak boleh kosong' },
                { name: 'kapasitas_sampah', message: 'Kapasitas sampah harus diisi dan lebih dari 0' },
                { name: 'alamat', message: 'Alamat tidak boleh kosong' }
            ];

            // Clear previous errors
            document.querySelectorAll('.drop-point-create-form-error').forEach(el => {
                el.classList.remove('show');
            });
            document.querySelectorAll('.drop-point-create-form-input, .drop-point-create-form-textarea').forEach(el => el.classList.remove('error'));

            requiredFields.forEach(fieldInfo => {
                const field = document.querySelector(`[name="${fieldInfo.name}"]`);
                const errorElement = document.getElementById(`error-${fieldInfo.name}`);
                
                let isFieldValid = false;

                if (fieldInfo.name === 'kapasitas_sampah') {
                    // Validasi khusus untuk kapasitas: harus ada nilai DAN > 0
                    if (field.value.trim() !== '' && parseFloat(field.value) > 0) {
                        isFieldValid = true;
                    }
                } else {
                    // Validasi umum: tidak boleh kosong
                    if (field.value.trim() !== '') {
                        isFieldValid = true;
                    }
                }

                if (!isFieldValid) {
                    field.classList.add('error');
                    errorElement.textContent = fieldInfo.message;
                    errorElement.classList.add('show');
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
@endsection