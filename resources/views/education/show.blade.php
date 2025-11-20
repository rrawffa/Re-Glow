@extends('layouts.app')

@section('title', $konten->judul . ' - Re-Glow')

@section('styles')
    @vite(['resources/css/education/show.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<div class="article-container">
    <!-- Back Navigation - NOW AT TOP -->
    <div class="back-nav">
        <a href="{{ route('education.index') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Katalog Edukasi
        </a>
    </div>

    <!-- Article Header -->
    <div class="article-header">
        <h1 class="article-title">{{ $konten->judul }}</h1>
        
        <div class="article-meta">
            <div class="meta-item">
                <i class="bi bi-calendar3"></i>
                <span>{{ \Carbon\Carbon::parse($konten->tanggal_upload)->format('F d, Y') }}</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-pencil"></i>
                <span>{{ $konten->penulis ?? 'Re-Glow Team' }}</span>
            </div>
            @if($konten->waktu_baca)
            <div class="meta-item">
                <i class="bi bi-clock"></i>
                <span>{{ $konten->waktu_baca }} min read</span>
            </div>
            @endif
            @if($konten->statistik)
            <div class="meta-item">
                <i class="bi bi-eye"></i>
                <span>{{ number_format($konten->statistik->total_view) }} views</span>
            </div>
            @endif
        </div>

        @if($konten->gambar_cover)
        <img src="{{ asset('storage/' . $konten->gambar_cover) }}" 
             alt="{{ $konten->judul }}" 
             class="article-cover">
        @endif

        @if($konten->ringkasan)
        <div class="article-summary">
            {{ $konten->ringkasan }}
        </div>
        @endif
    </div>

    <!-- Admin Actions -->
    @if(Session::has('user_id') && Session::get('user_role') === 'admin')
    <div class="admin-actions">
        <a href="{{ route('education.edit', $konten->id_konten) }}" class="btn-admin btn-edit">
            <i class="bi bi-pencil-square"></i> Edit Konten
        </a>
        <form action="{{ route('education.destroy', $konten->id_konten) }}" 
              method="POST" 
              style="display: inline;"
              onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-admin btn-delete">
                <i class="bi bi-trash"></i> Hapus Konten
            </button>
        </form>
    </div>
    @endif

    <!-- Main Content -->
    <div class="article-content">
        @if($konten->isi)
            {!! $konten->isi !!}
        @else
            <p>Content will be available soon. Please check back later.</p>
        @endif
    </div>

    <!-- Reactions Section -->
    <div class="reactions-section">
        <h3 class="reactions-title">Bagaimana pendapat Anda tentang artikel ini?</h3>
        
        @php
            $counts = $konten->getReactionCounts();
            $userReactionType = $userReaction ? $userReaction->tipe_reaksi : null;
        @endphp

        <div class="stats-display">
            <div class="stat-item">
                <div class="stat-value" id="totalReactions">{{ $counts['total'] }}</div>
                <div class="stat-label">Total Reaksi</div>
            </div>
        </div>

        <div class="reactions-buttons">
            <button class="reaction-btn {{ $userReactionType === 'suka' ? 'active' : '' }}" 
                    data-reaction="suka"
                    data-konten="{{ $konten->id_konten }}">
                <span class="reaction-emoji">❤️</span>
                <span class="reaction-label">Suka</span>
                <span class="reaction-count-display" data-type="suka">{{ $counts['suka'] }}</span>
            </button>

            <button class="reaction-btn {{ $userReactionType === 'membantu' ? 'active' : '' }}" 
                    data-reaction="membantu"
                    data-konten="{{ $konten->id_konten }}">
                <span class="reaction-emoji">👍</span>
                <span class="reaction-label">Membantu</span>
                <span class="reaction-count-display" data-type="membantu">{{ $counts['membantu'] }}</span>
            </button>

            <button class="reaction-btn {{ $userReactionType === 'menarik' ? 'active' : '' }}" 
                    data-reaction="menarik"
                    data-konten="{{ $konten->id_konten }}">
                <span class="reaction-emoji">🔥</span>
                <span class="reaction-label">Menarik</span>
                <span class="reaction-count-display" data-type="menarik">{{ $counts['menarik'] }}</span>
            </button>

            <button class="reaction-btn {{ $userReactionType === 'inspiratif' ? 'active' : '' }}" 
                    data-reaction="inspiratif"
                    data-konten="{{ $konten->id_konten }}">
                <span class="reaction-emoji">✨</span>
                <span class="reaction-label">Inspiratif</span>
                <span class="reaction-count-display" data-type="inspiratif">{{ $counts['inspiratif'] }}</span>
            </button>
        </div>
    </div>

    <!-- Related Articles -->
    @if($related->count() > 0)
    <div class="related-section">
        <h3 class="related-title">
            <i class="bi bi-link-45deg"></i> Artikel Terkait
        </h3>
        <div class="related-grid">
            @foreach($related as $item)
            <a href="{{ route('education.show', $item->id_konten) }}" class="related-card">
                <h4>{{ $item->judul }}</h4>
                <p>{{ Str::limit($item->ringkasan, 100) }}</p>
                <div class="related-meta">
                    <i class="bi bi-clock"></i> {{ $item->waktu_baca }} min read
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Notification Toast -->
<div id="notificationToast" class="notification-toast">
    <span id="toastEmoji"></span>
    <span id="toastMessage"></span>
</div>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('scripts')
    @vite(['resources/js/education/show.js'])
@endsection