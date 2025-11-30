@extends('layouts.app')

@section('title', 'Add FAQ')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root {
        --reglow-pink: #d85f8c;
        --reglow-green-dark: #20413A;
    }

    .page-title {
        color: var(--reglow-green-dark);
        font-weight: 700;
        font-family: 'Bricolage Grotesque', sans-serif;
    }

    .btn-reglow {
        background-color: var(--reglow-pink);
        border-color: var(--reglow-pink);
    }
    .btn-reglow:hover {
        background-color: #bf4f7a;
        border-color: #bf4f7a;
    }
</style>
@endsection

@section('content')
<div class="container mt-5" style="max-width: 780px;">

    <h2 class="page-title mb-2">Add New FAQ</h2>
    <p class="text-muted mb-4">Provide questions & answers to help users 🌟</p>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-4">

            <form action="{{ route('admin.faq.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Question</label>
                    <input type="text" name="question" class="form-control form-control-lg" required placeholder="Enter FAQ question">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Answer</label>
                    <textarea name="answer" class="form-control" rows="5" required placeholder="Write the answer here..."></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary px-4">
                        <i class="fa fa-arrow-left me-1"></i> Back
                    </a>

                    <button type="submit" class="btn btn-reglow text-white px-4 fw-bold">
                        <i class="fa fa-check me-1"></i> Save FAQ
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
