@extends('layouts.app')

@section('title', 'Manage User Points - Re-Glow')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/riwayat poin/custom.css') }}">
@endsection

@section('content')
<div class="container py-5 mt-4">

    <div class="row mb-5">
        <div class="col-md-8 col-sm-12">
            <h1 class="fw-bold mb-1">Manage User Points</h1>
            <p class="text-muted">View and edit user point transactions here.</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addPointModal">
                + Add Point
            </button>
        </div>
    </div>

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
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tx->user->username }}</td>
                    <td class="text-capitalize">{{ $tx->type }}</td>
                    <td>{{ $tx->points }}</td>
                    <td>{{ $tx->description ?? '-' }}</td>
                    <td>{{ $tx->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editPointModal{{ $tx->id }}">Edit</button>
                        <form action="{{ route('admin.points.destroy', $tx->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editPointModal{{ $tx->id }}" tabindex="-1" aria-labelledby="editPointModalLabel{{ $tx->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.points.update', $tx->id) }}" method="POST" class="modal-content">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPointModalLabel{{ $tx->id }}">Edit Point</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="points{{ $tx->id }}" class="form-label">Points</label>
                                    <input type="number" name="points" class="form-control" id="points{{ $tx->id }}" value="{{ $tx->points }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description{{ $tx->id }}" class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control" id="description{{ $tx->id }}" value="{{ $tx->description }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Add Point Modal --}}
<div class="modal fade" id="addPointModal" tabindex="-1" aria-labelledby="addPointModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.points.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addPointModalLabel">Add Point Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="user_id" class="form-label">User</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id_user }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="earn">Earn</option>
                        <option value="redeem">Redeem</option>
                        <option value="adjust">Adjust</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="points" class="form-label">Points</label>
                    <input type="number" name="points" class="form-control" id="points" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" id="description">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
