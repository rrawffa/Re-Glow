@extends('layouts.app')

@section('title', 'Favorite Vouchers - Re-Glow')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
<section class="py-16 text-center bg-[--color-hero-bg]" 
    style="background-image: url('{{ asset('images/voucher-bg.jpg') }}'); background-size: cover; background-position: center;">
    <h2 class="text-3xl md:text-5xl font-semibold text-[--color-dark-text] mb-4">
        Your Favorite Vouchers ❤️
    </h2>
    <p class="text-gray-600 max-w-2xl mx-auto mb-8 text-base md:text-lg">
        Here are all the vouchers you've marked as favorites. Redeem them whenever you're ready!
    </p>
</section>

<section id="voucher-catalog" class="py-14 px-6 max-w-7xl mx-auto">
    <h3 class="text-2xl font-semibold text-[--color-dark-text] mb-8">Saved Favorites</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($favoriteVouchers as $voucher)
            @include('vouchers._voucher-card', ['voucher' => $voucher, 'userPoints' => $userPoints])
        @empty
            <p class="text-gray-500 text-center col-span-full">You haven’t added any vouchers to favorites yet.</p>
        @endforelse
    </div>
</section>
@endsection
