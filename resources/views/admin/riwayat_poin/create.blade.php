@extends('layouts.app')

@section('title', 'Add Point Transaction - Re-Glow')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/riwayat_poin/custom.css') }}">
@endsection

@section('content')
<div class="container py-5 mt-4">
    <h1 class="fw-bold mb-2">Add Point Transaction</h1>

    @if(auth()->check())
        <p class="text-end text-muted small mb-4">
            Logged in as: <strong>{{ auth()->user()->username }}</strong>
        </p>
    @endif
    
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12">

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <form action="{{ route('admin.riwayat_poin.store') }}" method="POST">
                        @csrf

                        <!-- Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="earn">Earn</option>
                                <option value="redeem">Redeem</option>
                                <option value="adjust">Adjust</option>
                            </select>
                        </div>

                        <!-- Points -->
                        <div class="mb-3">
                            <label for="points" class="form-label">Points</label>
                            <input type="number" name="points" id="points" class="form-control" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control">
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-3">
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
