@extends('layouts.app')

@section('title', 'FAQ Management')

@push('styles')
<style>
    :root {
        --reglow-pink: #d85f8c;
        --reglow-green-dark: #20413A;
    }

    .admin-faq-title {
        color: var(--reglow-green-dark);
        font-family: 'Bricolage Grotesque', sans-serif;
        font-weight: 700;
    }

    .btn-primary {
        background-color: var(--reglow-pink);
        border-color: var(--reglow-pink);
    }

    .btn-primary:hover {
        background-color: #bf4f7a;
        border-color: #bf4f7a;
    }

    table thead {
        background-color: var(--reglow-green-dark);
        color: white;
    }

    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<main class="container py-5">

    <section class="text-center mb-4">
        <h2 class="admin-faq-title">FAQ Management</h2>
        <p class="text-muted">Manage and update frequently asked questions for Re-Glow users.</p>
    </section>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> Add FAQ
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="35%">Question</th>
                        <th width="45%">Answer</th>
                        <th width="15%" class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($faqs as $faq)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $faq->question }}</td>
                        <td>{{ Str::limit($faq->answer, 80) }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.faq.edit', $faq->id) }}"
                                class="btn btn-warning btn-sm me-1">
                                Edit
                            </a>

                            <form action="{{ route('admin.faq.destroy', $faq->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this FAQ?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-3 text-muted">
                            No FAQ added yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</main>
@endsection
