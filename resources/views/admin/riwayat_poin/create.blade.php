@extends('layouts.app')

@section('title', 'Add Point Transaction - Re-Glow')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/riwayat_poin/custom.css') }}">
@endsection

@section('content')
<div class="container py-5 mt-4">
    <h1 class="fw-bold mb-4">Add Point Transaction</h1>

    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ route('admin.riwayat_poin.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="id_user" class="form-label">User</label>
                            <select name="id_user" id="id_user" class="form-select" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
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
                            <input type="number" name="points" id="points" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.riwayat_poin.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Transaction</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
