@extends('layouts.app')

@section('title', 'Re-Glow - Education')

@section('styles')
<style>
    /* Hero Section */
    .hero {
        background: linear-gradient(135deg, var(--pink-light) 0%, #FFF 100%);
        padding: 5rem 5% 4rem;
        text-align: center;
    }

    .hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--green-dark);
    }

    .hero .highlight {
        color: var(--pink-base);
    }

    .hero p {
        font-size: 1.125rem;
        color: var(--text-gray);
        max-width: 800px;
        margin: 1.5rem auto;
        line-height: 1.8;
    }

    .cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--green-dark);
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 1.5rem;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .cta-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(32, 65, 58, 0.3);
    }

    /* Education Catalog */
    .catalog-section {
        padding: 4rem 5%;
        background: white;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 0.75rem;
    }

    .section-header p {
        color: var(--text-gray);
        font-size: 1.125rem;
    }

    .catalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .catalog-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .catalog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .catalog-card h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--green-dark);
        margin-bottom: 1rem;
    }

    .catalog-card p {
        color: var(--text-gray);
        font-size: 0.95rem;
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .card-meta {
        font-size: 0.85rem;
        color: var(--text-gray);
        margin-bottom: 1.5rem;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .read-more {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--pink-base);
        color: var(--green-dark);
        padding: 0.65rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: background 0.3s;
    }

    .read-more:hover {
        background: #F7A3B8;
    }

    .card-reactions {
        display: flex;
        gap: 0.5rem;
        font-size: 1.125rem;
    }

    .load-more {
        display: block;
        margin: 3rem auto 0;
        background: var(--green-light);
        color: var(--green-dark);
        padding: 1rem 3rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .load-more:hover {
        background: #A8B399;
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2rem;
        }

        .catalog-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
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

        @if($konten->count() > 0)
        <div class="catalog-grid">
            @foreach($konten as $item)
            <div class="catalog-card">
                {{-- Tampilkan gambar cover jika ada --}}
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
                    <span>⏱️ {{ $item->waktu_baca }} min read</span>
                    @endif
                    @if($item->penulis)
                    <span>✍️ {{ $item->penulis }}</span>
                    @endif
                </div>
                
                <div class="card-footer">
                <a href="{{ url('/education/' . $item->id_konten) }}" class="read-more">
                    Read More →
                </a>
                    <div class="card-reactions">
                        <span title="Reactions">❤️ {{ $item->jumlah_reaksi ?? 0 }}</span>
                        <span title="Thumbs Up">👍</span>
                        <span title="Fire">🔥</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 3rem; background: var(--pink-light); border-radius: 12px;">
            <h3 style="color: var(--green-dark); margin-bottom: 1rem;">No Content Available</h3>
            <p style="color: var(--text-gray);">Educational content will be available soon.</p>
        </div>
        @endif

        <button class="load-more">
            Load More Articles
        </button>
    </section>
@endsection

@section('scripts')
<script>
    // JavaScript untuk load more button (jika diperlukan)
    document.querySelector('.load-more')?.addEventListener('click', function() {
        alert('Load more functionality would be implemented here!');
    });
</script>
@endsection