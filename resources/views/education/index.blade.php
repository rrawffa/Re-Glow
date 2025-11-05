@extends('layouts.app')

@section('title', 'Re-Glow - Education')

@section('styles')
    @vite(['resources/css/education/index.css'])
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <h1>
            Explore the World of Cosmetic<br>
            <span class="highlight">Recycling: Sustainable Beauty Starts Here!</span>
        </h1>
        <p>
            Discover the impact of cosmetic waste on our environment and learn practical 
            ways to make your beauty routine more sustainable. Join thousands of users 
            making a difference, one product at a time.
        </p>
        <a href="#catalog" class="cta-btn">
            Start Learning
            <span>↓</span>
        </a>
    </section>

    <!-- Education Catalog -->
    <section class="catalog-section" id="catalog">
        <div class="section-header">
            <h2>Education Catalog</h2>
            <p>Expand your knowledge with our curated collection of sustainability content</p>
        </div>

        @can('create', App\Models\Education::class)
        <div class="admin-actions">
            <a href="{{ route('education.create') }}" class="btn-create">
                <span>+</span> Tambah Konten Baru
            </a>
        </div>
        @endcan

        @if($konten->count() > 0)
        <div class="catalog-grid">
            @foreach($konten as $item)
            <div class="catalog-card">
                @if($item->gambar_cover)
                <img src="{{ asset('storage/' . $item->gambar_cover) }}" 
                     alt="{{ $item->judul }}" 
                     style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                @endif

                <h3>{{ $item->judul }}</h3>
                <p>{{ Str::limit($item->ringkasan, 150) }}</p>
                
                <div class="card-meta">
                    <span>📅 {{ \Carbon\Carbon::parse($item->tanggal_upload)->format('F d, Y') }}</span>
                    @if($item->waktu_baca)
                    <span> • ⏱️ {{ $item->waktu_baca }} min read</span>
                    @endif
                    @if($item->penulis)
                    <span> • ✏️ {{ $item->penulis }}</span>
                    @endif
                </div>
                
                <div class="card-footer">
                    <a href="{{ route('education.show', $item->id_konten) }}" class="read-more">
                        Read More →
                    </a>
                    <div class="card-reactions">
                        @php
                            $counts = $item->getReactionCounts();
                        @endphp
                        <span class="reaction-count" title="Total Reactions">
                            ❤️ {{ $counts['total'] }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $konten->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 3rem; background: var(--pink-light); border-radius: 12px;">
            <h3 style="color: var(--green-dark); margin-bottom: 1rem;">No Content Available</h3>
            <p style="color: var(--text-gray);">Educational content will be available soon.</p>
        </div>
        @endif
    </section>
@endsection