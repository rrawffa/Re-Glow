@extends('layouts.app')

@section('title', 'Re-Glow - Education')

@section('styles')
    @vite(['resources/css/education/index.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
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
            <i class="bi bi-arrow-down"></i>
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
                <i class="bi bi-plus-lg"></i> Tambah Konten Baru
            </a>
        </div>
        @endcan

        @if($konten->count() > 0)
            <div class="catalog-grid">
                @foreach($konten as $item)
                <div class="catalog-card">
                    <!-- Gambar cover dihapus dari sini -->
                    
                    <h3>{{ $item->judul }}</h3>
                    <p>{{ Str::limit($item->ringkasan, 150) }}</p>
                    
                    <div class="card-meta">
                        <span>
                            <i class="bi bi-calendar3"></i> 
                            {{ \Carbon\Carbon::parse($item->tanggal_upload)->format('F d, Y') }}
                        </span>
                        @if($item->waktu_baca)
                        <span> 
                            • <i class="bi bi-clock"></i> {{ $item->waktu_baca }} min read
                        </span>
                        @endif
                    </div>
                    
                    <div class="card-footer">
                        <a href="{{ route('education.show', $item->id_konten) }}" class="read-more">
                            Read More <i class="bi bi-arrow-right"></i>
                        </a>
                        <div class="card-reactions">
                            @php
                                $counts = $item->getReactionCounts();
                            @endphp
                            <span class="reaction-count" title="Total Reactions">
                                <i class="bi bi-heart-fill" style="color: #dc3545;"></i> {{ $counts['total'] }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($konten->hasPages())
            <div class="pagination-wrapper">
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($konten->onFirstPage())
                        <li class="disabled">
                            <span>
                                <i class="bi bi-chevron-left"></i> Previous
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $konten->previousPageUrl() }}">
                                <i class="bi bi-chevron-left"></i> Previous
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($konten->getUrlRange(1, $konten->lastPage()) as $page => $url)
                        @if ($page == $konten->currentPage())
                            <li class="active">
                                <span>{{ $page }}</span>
                            </li>
                        @else
                            <li class="hidden-mobile">
                                <a href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($konten->hasMorePages())
                        <li>
                            <a href="{{ $konten->nextPageUrl() }}">
                                Next <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="disabled">
                            <span>
                                Next <i class="bi bi-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
            @endif
        @else
            <div style="text-align: center; padding: 3rem; background: var(--pink-light); border-radius: 12px;">
                <h3 style="color: var(--green-dark); margin-bottom: 1rem;">
                    <i class="bi bi-info-circle"></i> No Content Available
                </h3>
                <p style="color: var(--text-gray);">Educational content will be available soon.</p>
            </div>
        @endif
    </section>
@endsection