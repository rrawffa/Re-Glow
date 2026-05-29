@extends('layouts.app')

@section('title', 'Edit Point Transaction - Re-Glow')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/riwayat_poin/custom.css') }}">
@endsection

@section('content')
<div class="container py-5 mt-4">
    <h1 class="fw-bold mb-4">Edit Point Transaction</h1>

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
                    <form action="{{ route('admin.riwayat_poin.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- User -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label">User</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id_user }}" 
                                        @if($user->id_user == $transaction->user_id) selected @endif>
                                        {{ $user->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="earn" @if($transaction->type == 'earn') selected @endif>Earn</option>
                                <option value="redeem" @if($transaction->type == 'redeem') selected @endif>Redeem</option>
                                <option value="adjust" @if($transaction->type == 'adjust') selected @endif>Adjust</option>
                            </select>
                        </div>

                        <!-- Points -->
                        <div class="mb-3">
                            <label for="points" class="form-label">Points</label>
                            <input type="number" name="points" id="points" class="form-control" 
                                   value="{{ $transaction->points }}" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control" 
                                   value="{{ $transaction->description }}">
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.riwayat_poin.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
