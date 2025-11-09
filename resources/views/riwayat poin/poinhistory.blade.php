@extends('layouts.app')

@section('title', 'Your Point History - Re-Glow')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/riwayat poin/custom.css') }}">
@endsection

@section('content')
<div class="container py-5 mt-4">

    <div class="row align-items-center mb-5">
        <div class="col-md-8 col-sm-12">
            <h1 class="fw-bold mb-1">Your Point History</h1>
            <p class="text-muted">Check your transaction progress here!</p>
        </div>
        <div class="col-md-4 text-end d-none d-md-block">
            <img src="{{ asset('images/riwayat poin/money-icon.png') }}" alt="Recycle Money Icon" width="120" class="img-fluid header-money-icon">
        </div>
    </div>

    <div class="row g-4 mb-5">

        {{-- Total Points Earned --}}
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-point shadow-sm border-0">
                <div class="card-body">
                    <span class="card-point-icon-wrapper icon-earned-bg">
                        <img src="{{ asset('images/riwayat poin/coin.png') }}" alt="Earned Icon" width="20">
                    </span>
                    <h6 class="text-secondary text-uppercase mb-2">Total Points Earned</h6>
                    <h2 class="fw-bold text-dark">0</h2>
                </div>
            </div>
        </div>

        {{-- Points Redeemed --}}
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-point shadow-sm border-0">
                <div class="card-body">
                    <span class="card-point-icon-wrapper icon-redeemed-bg">
                        <img src="{{ asset('images/riwayat poin/gift.png') }}" alt="Redeemed Icon" width="20">
                    </span>
                    <h6 class="text-secondary text-uppercase mb-2">Points Redeemed</h6>
                    <h2 class="fw-bold text-dark">0</h2>
                </div>
            </div>
        </div>

        {{-- Current Balance --}}
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-point shadow-sm border-0">
                <div class="card-body">
                    <span class="card-point-icon-wrapper icon-balance-bg">
                        <img src="{{ asset('images/riwayat poin/wallet.png') }}" alt="Balance Icon" width="20">
                    </span>
                    <h6 class="text-secondary text-uppercase mb-2">Current Balance</h6>
                    <h2 class="fw-bold text-dark">0</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 pt-3">
        <h5 class="fw-semibold mb-0">Transaction History</h5>
        <select class="form-select w-auto">
            <option selected>All Transactions</option>
            <option>Earned</option>
            <option>Redeemed</option>
        </select>
    </div>

    <div class="text-center py-5 my-5">
        <img src="{{ asset('images/riwayat poin/empty-wallet.png') }}" alt="No Points" width="200" class="mb-4">
        <h2 class="fw-bold text-dark mt-4">No Points Yet</h2>
        <p class="text-muted mb-4">You don't have any point history yet.<br>Start recycling and earning your first point!</p>
        <a href="/dashboard" class="btn btn-pink px-5 py-3 fw-bold rounded-pill">Go to Dashboard</a>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
