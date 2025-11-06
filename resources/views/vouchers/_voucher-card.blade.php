<div class="bg-white rounded-2xl shadow-md p-5 flex flex-col justify-between transition hover:shadow-lg">
    <div class="h-40 bg-gray-100 rounded-xl mb-4 overflow-hidden relative">
        <img src="{{ asset('images/vouchers/' . ($voucher->image ?? 'placeholder.jpg')) }}" 
             alt="{{ $voucher->name }}" class="w-full h-full object-cover">
        <button class="absolute top-3 right-3 bg-white/80 p-2 rounded-full shadow hover:bg-white transition">
            @if($voucher->is_favorite ?? false)
                <svg class="w-6 h-6 text-red-500 fill-red-500" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                             2 5.42 4.42 3 7.5 3
                             c1.74 0 3.41.81 4.5 2.09
                             C13.09 3.81 14.76 3 16.5 3
                             C19.58 3 22 5.42 22 8.5
                             c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            @else
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364
                          l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636
                          l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            @endif
        </button>
    </div>

    <div class="flex justify-between items-start">
        <div class="flex-1">
            <h4 class="text-lg font-semibold text-[--color-dark-text] mb-1">{{ $voucher->name }}</h4>
            <p class="text-gray-500 text-sm mb-2">{{ $voucher->brand }}</p>
            <div class="inline-block bg-[--color-pink-soft] rounded-lg px-3 py-1 mb-2">
                <span class="text-[--color-dark-text] font-semibold text-sm">{{ $voucher->required_points }} pts</span>
            </div>
            <p class="text-sm text-gray-400">Expires: {{ \Carbon\Carbon::parse($voucher->expiration_date)->format('d M Y') }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500 font-medium">Remaining:</p>
            <p class="text-base text-[--color-dark-text] font-bold">{{ $voucher->stock }}</p>
        </div>
    </div>

    <div class="mt-4">
        @if($voucher->stock <= 0)
            <button class="w-full py-2.5 rounded-full font-semibold bg-gray-300 text-white cursor-not-allowed">
                Out of Stock
            </button>
        @elseif($userPoints < $voucher->required_points)
            <button class="w-full py-2.5 rounded-full font-semibold bg-gray-300 text-white cursor-not-allowed">
                Insufficient Points
            </button>
        @else
            <form action="{{ route('vouchers.redeem', $voucher->id) }}" method="POST">
                @csrf
                <button class="w-full py-2.5 rounded-full font-semibold bg-[--color-pink-soft] text-[--color-dark-text] hover:opacity-90 transition">
                    Redeem Now
                </button>
            </form>
        @endif
    </div>
</div>
