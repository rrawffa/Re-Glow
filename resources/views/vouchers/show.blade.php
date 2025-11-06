@extends('layouts.app')

@section('content')
@vite(['resources/css/vouchers.css'])
<div class="container" style="padding-top:20px">
  <a href="{{ route('vouchers.index') }}">&larr; Back</a>
  <div class="voucher-detail" style="display:flex;gap:20px;margin-top:12px;align-items:flex-start">
    <img src="{{ $voucher->image_url ?? '/assets/vouchers/default.jpg' }}" style="width:360px;height:240px;object-fit:cover;border-radius:6px" alt="">
    <div>
      <h2 style="font-family:var(--heading-font)">{{ $voucher->name }}</h2>
      <p style="color:#556b60">{{ $voucher->brand }}</p>
      <p>{{ $voucher->description }}</p>
      <p><strong>{{ $voucher->required_points }} Points</strong></p>
      <p>Remaining: {{ $voucher->stock }}</p>
      <form method="POST" action="{{ route('vouchers.redeem', $voucher) }}">
        @csrf
        @php $disabled = $voucher->stock <= 0 || (auth()->user()->points ?? 0) < $voucher->required_points; @endphp
        <button class="btn-redeem {{ $disabled ? 'disabled' : '' }}" {{ $disabled ? 'disabled' : '' }}>
          @if($voucher->stock <= 0) Out of Stock
          @elseif((auth()->user()->points ?? 0) < $voucher->required_points) Insufficient Points
          @else Confirm Redemption @endif
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
