@extends('layouts.app')

@section('title', 'Add New Drop Point - Re-Glow Admin')

@section('styles')
    @vite(['resources/css/admin/waste-exchange/drop-point-create.css'])
@endsection

@section('content')
    <div class="drop-point-create-header">
        <div class="container" style="margin: 0; max-width: none;">
            <a href="{{ route('admin.waste.droppoint.index') }}" class="drop-point-create-back-button">
                ← Back to Drop Point List
            </a>
        </div>
    </div>

    <div class="drop-point-create-container">
        <h1 class="drop-point-create-page-title">Add New Drop Point</h1>

        @if($errors->any())
            <div class="drop-point-create-alert drop-point-create-alert-danger">
                <p style="font-weight: 600; margin-bottom: 0.5rem;">🚨 Ups! Ada beberapa kesalahan validasi:</p>
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
                    <label class="drop-point-create-form-label">Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" class="drop-point-create-form-input" value="{{ old('nama_lokasi') }}" required>
                    <span class="drop-point-create-form-error" id="error-nama_lokasi">Nama lokasi tidak boleh kosong</span>
                </div>

                <div class="drop-point-create-form-group">
                    <label class="drop-point-create-form-label">Koordinat</label>
                    <input type="text" name="koordinat" class="drop-point-create-form-input" placeholder="contoh: -6.2088, 106.8456" value="{{ old('koordinat') }}" required>
                    <span class="drop-point-create-form-error" id="error-koordinat">Koordinat tidak boleh kosong</span>
                </div>
            </div>

            <div class="drop-point-create-form-group">
                <label class="drop-point-create-form-label">Kapasitas Sampah (kg)</label>
                <input type="number" name="kapasitas_sampah" class="drop-point-create-form-input" step="0.1" min="0" value="{{ old('kapasitas_sampah') }}" required>
                <span class="drop-point-create-form-error" id="error-kapasitas_sampah">Kapasitas sampah tidak boleh kosong</span>
            </div>

            <div class="drop-point-create-form-group">
                <label class="drop-point-create-form-label">Alamat</label>
                <textarea name="alamat" class="drop-point-create-form-textarea" required>{{ old('alamat') }}</textarea>
                <span class="drop-point-create-form-error" id="error-alamat">Alamat tidak boleh kosong</span>
            </div>

            <button type="submit" class="drop-point-create-btn-submit">Kirim</button>
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
                // Atur ulang pesan error kustom (jika Anda ingin pesan yang berbeda untuk JS vs Backend)
                // el.textContent = el.getAttribute('data-default-message'); 
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
                    errorElement.textContent = fieldInfo.message; // Tampilkan pesan error kustom
                    errorElement.classList.add('show');
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                // Menghapus scroll ke atas agar user fokus pada field error
                // window.scrollTo({ top: 0, behavior: 'smooth' }); 
            }
        });
    </script>
@endsection