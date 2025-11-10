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

            // Clear previous errors
            document.querySelectorAll('.drop-point-create-form-error').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.drop-point-create-form-input, .drop-point-create-form-textarea').forEach(el => el.classList.remove('error'));

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