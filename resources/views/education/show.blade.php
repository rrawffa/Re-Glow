@extends('layouts.app')

@section('title', $education->judul . ' - Re-Glow')

@section('styles')
<style>
    /* Article Container */
    .article-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    /* Article Header */
    .article-header {
        margin-bottom: 3rem;
        border-bottom: 2px solid var(--border-light);
        padding-bottom: 2rem;
    }

    .article-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .article-meta {
        display: flex;
        gap: 1.5rem;
        color: var(--text-gray);
        font-size: 0.9rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .article-summary {
        color: var(--text-gray);
        font-size: 1.125rem;
        line-height: 1.7;
        background: var(--pink-light);
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid var(--pink-base);
    }

    /* Article Content */
    .article-content {
        line-height: 1.8;
        font-size: 1.05rem;
    }

    .article-content h2 {
        font-size: 1.75rem;
        color: var(--green-dark);
        margin: 2.5rem 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--pink-light);
    }

    .article-content h3 {
        font-size: 1.375rem;
        color: var(--green-dark);
        margin: 2rem 0 1rem 0;
    }

    .article-content p {
        margin-bottom: 1.5rem;
        color: var(--text-dark);
    }

    .article-content ul, .article-content ol {
        margin: 1.5rem 0;
        padding-left: 2rem;
    }

    .article-content li {
        margin-bottom: 0.75rem;
        color: var(--text-dark);
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--green-dark);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.3s;
        margin-top: 2rem;
    }

    .back-btn:hover {
        background: #16332d;
    }

    :root {
        --border-light: #E5E5E5;
    }
</style>
@endsection

@section('content')
<div class="article-container">
    <!-- Article Header -->
    <div class="article-header">
        <h1 class="article-title">{{ $education->judul }}</h1>
        
        <div class="article-meta">
            <div class="meta-item">
                <span>📅</span>
                <span>{{ \Carbon\Carbon::parse($education->tanggal_upload)->format('F d, Y') }}</span>
            </div>
            <div class="meta-item">
                <span>✍️</span>
                <span>{{ $education->penulis ?? 'Re-Glow Team' }}</span>
            </div>
            @if($education->waktu_baca)
            <div class="meta-item">
                <span>⏱️</span>
                <span>{{ $education->waktu_baca }} min read</span>
            </div>
            @endif
        </div>

        @if($education->ringkasan)
        <div class="article-summary">
            {{ $education->ringkasan }}
        </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="article-content">
        @if($education->konten)
            {!! $education->konten !!}
        @else
            <p>Content will be available soon. Please check back later.</p>
        @endif
        
        <a href="{{ route('education.index') }}" class="back-btn">
            ← Back to Education Catalog
        </a>
    </div>
</div>
@endsection