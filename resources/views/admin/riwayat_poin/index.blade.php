@extends('layouts.app')

@section('title', 'Manage User Points - Re-Glow')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/riwayat_poin/custom.css') }}">
@endsection

@section('content')
<div class="container py-5 mt-4">
    <div class="row align-items-center mb-5">
        <div class="col-md-8 col-sm-12">
            <h1 class="fw-bold mb-1">Manage User Points</h1>
            <p class="text-muted">View and edit user point transactions here.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.riwayat_poin.create') }}" class="btn btn-primary fw-bold">+ Add Point</a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($transactions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Points</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $index => $tx)
                        <tr>
                            <td>{{ $index + 1 + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                            <td>{{ optional($tx->user)->username ?? 'User Deleted' }}</td>
                            <td class="text-capitalize">{{ $tx->type }}</td>
                            <td>{{ $tx->points }}</td>
                            <td>{{ $tx->description ?? '-' }}</td>
                            <td>{{ $tx->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.riwayat_poin.edit', $tx->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                                <form action="{{ route('admin.riwayat_poin.destroy', $tx->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $transactions->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <h4 class="text-muted">No transactions added yet.</h4>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
