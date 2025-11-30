@extends('layouts.app')

@section('title', 'Manage FAQs - Re-Glow')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/riwayat_poin/custom.css') }}">
@endsection

@section('content')
<div class="container py-5 mt-4">

    <div class="row align-items-center mb-5">
        <div class="col-md-8 col-sm-12">
            <h1 class="fw-bold mb-1">Manage FAQs</h1>
            <p class="text-muted">Manage frequently asked questions for Re-Glow.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.faq.create') }}" class="btn btn-primary fw-bold">+ Add FAQ</a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($faqs->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Answer Preview</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faqs as $index => $faq)
                <tr>
                    <td>{{ $index + 1 + ($faqs->currentPage() - 1) * $faqs->perPage() }}</td>
                    <td class="fw-semibold">{{ $faq->question }}</td>
                    <td>{{ Str::limit($faq->answer, 70) }}</td>
                    <td>
                        <a href="{{ route('admin.faq.edit', $faq->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>

                        <form action="{{ route('admin.faq.destroy', $faq->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $faqs->links() }}
    </div>
    @else
        <div class="text-center py-5">
            <h4 class="text-muted">No FAQs added yet.</h4>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
