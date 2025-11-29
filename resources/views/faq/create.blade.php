@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h2 class="fw-bold text-center mb-4">Tambah Pertanyaan Baru</h2>

    <div class="card shadow-sm border-0 p-4 mx-auto" style="max-width: 700px; border-radius: 18px;">

        <form action="{{ route('faq.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Pertanyaan</label>
                <input type="text" name="question" class="form-control form-control-lg" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Jawaban</label>
                <textarea name="answer" class="form-control" rows="6" required></textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('faq.index') }}" class="btn btn-outline-secondary px-4">Kembali</a>
                <button class="btn btn-dark px-4">Simpan</button>
            </div>

        </form>
    </div>

</div>
@endsection
