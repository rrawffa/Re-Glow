@extends('layouts.app')

@section('title', $konten->judul . ' - Re-Glow')

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

    /* Cover Image */
    .article-cover {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 12px;
        margin: 1.5rem 0;
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

    /* Reactions Section */
    .reactions-section {
        margin: 3rem 0;
        padding: 2rem;
        background: var(--pink-light);
        border-radius: 12px;
        text-align: center;
    }

    .reactions-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--green-dark);
        margin-bottom: 1.5rem;
    }

    .reactions-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .reaction-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        border: 2px solid transparent;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
        min-width: 100px;
    }

    .reaction-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .reaction-btn.active {
        border-color: var(--pink-base);
        background: #fff5f7;
    }

    .reaction-emoji {
        font-size: 2rem;
    }

    .reaction-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--green-dark);
    }

    .reaction-count-display {
        font-size: 0.75rem;
        color: var(--text-gray);
    }

    /* Admin Actions */
    .admin-actions {
        display: flex;
        gap: 1rem;
        margin: 2rem 0;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .btn-admin {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-edit {
        background: var(--green-light);
        color: var(--green-dark);
    }

    .btn-edit:hover {
        background: #A8B399;
    }

    .btn-delete {
        background: #ffc4c4;
        color: #c41e3a;
    }

    .btn-delete:hover {
        background: #ffaaaa;
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

    /* Statistics Display */
    .stats-display {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 1.5rem 0;
        padding: 1rem;
        background: white;
        border-radius: 8px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--green-dark);
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-gray);
    }

    /* Related Articles */
    .related-section {
        margin-top: 4rem;
        padding-top: 3rem;
        border-top: 2px solid var(--border-light);
    }

    .related-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 2rem;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .related-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.3s;
    }

    .related-card:hover {
        transform: translateY(-3px);
    }

    .related-card h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--green-dark);
        margin-bottom: 0.75rem;
    }

    .related-card p {
        font-size: 0.9rem;
        color: var(--text-gray);
        line-height: 1.5;
    }

    :root {
        --border-light: #E5E5E5;
    }

    /* Notification Toast */
    .notification-toast {
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        display: none;
        animation: slideIn 0.3s ease-out;
    }

    .notification-toast.show {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .notification-toast.success {
        border-left: 4px solid #28a745;
    }

    .notification-toast.error {
        border-left: 4px solid #dc3545;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="article-container">
    <!-- Article Header -->
    <div class="article-header">
        <h1 class="article-title">{{ $konten->judul }}</h1>
        
        <div class="article-meta">
            <div class="meta-item">
                <span>📅</span>
                <span>{{ \Carbon\Carbon::parse($konten->tanggal_upload)->format('F d, Y') }}</span>
            </div>
            <div class="meta-item">
                <span>✏️</span>
                <span>{{ $konten->penulis ?? 'Re-Glow Team' }}</span>
            </div>
            @if($konten->waktu_baca)
            <div class="meta-item">
                <span>⏱️</span>
                <span>{{ $konten->waktu_baca }} min read</span>
            </div>
            @endif
            @if($konten->statistik)
            <div class="meta-item">
                <span>👁️</span>
                <span>{{ number_format($konten->statistik->total_view) }} views</span>
            </div>
            @endif
        </div>

        @if($konten->ringkasan)
        <div class="article-summary">
            {{ $konten->ringkasan }}
        </div>
        @endif

        @if($konten->gambar_cover)
        <img src="{{ asset('storage/' . $konten->gambar_cover) }}" 
             alt="{{ $konten->judul }}" 
             class="article-cover">
        @endif
    </div>

    <!-- Admin Actions -->
    @can('update', $konten)
    <div class="admin-actions">
        <a href="{{ route('education.edit', $konten->id_konten) }}" class="btn-admin btn-edit">
            Edit Konten
        </a>
        <form action="{{ route('education.destroy', $konten->id_konten) }}" 
              method="POST" 
              onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-admin btn-delete">Hapus Konten</button>
        </form>
    </div>
    @endcan

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
                <div class="stat-value">{{ $counts['total'] }}</div>
                <div class="stat-label">Total Reaksi</div>
            </div>
        </div>

        <div class="reactions-buttons">
            <button class="reaction-btn {{ $userReactionType === 'suka' ? 'active' : '' }}" 
                    data-reaction="suka"
                    data-konten="{{ $konten->id_konten }}">
                <span class="reaction-emoji">❤️</span>
                <span class="reaction-label">Suka</span>
                <span class="reaction-count-display">{{ $counts['suka'] }}</span>
            </button>

            <button class="reaction-btn {{ $userReactionType === 'membantu' ? 'active' : '' }}" 
                    data-reaction="membantu"
                    data-konten="{{ $konten->id_konten }}">
                <span class="reaction-emoji">👍</span>
                <span class="reaction-label">Membantu</span>
                <span class="reaction-count-display">{{ $counts['membantu'] }}</span>
            </button>

            <button class="reaction-btn {{ $userReactionType === 'menarik' ? 'active' : '' }}" 
                    data-reaction="menarik"
                    data-konten="{{ $konten->id_konten }}">
                <span class="reaction-emoji">🔥</span>
                <span class="reaction-label">Menarik</span>
                <span class="reaction-count-display">{{ $counts['menarik'] }}</span>
            </button>

            <button class="reaction-btn {{ $userReactionType === 'inspiratif' ? 'active' : '' }}" 
                    data-reaction="inspiratif"
                    data-konten="{{ $konten->id_konten }}">
                <span class="reaction-emoji">✨</span>
                <span class="reaction-label">Inspiratif</span>
                <span class="reaction-count-display">{{ $counts['inspiratif'] }}</span>
            </button>
        </div>
    </div>

    <a href="{{ route('education.index') }}" class="back-btn">
        ← Kembali ke Katalog
    </a>

    <!-- Related Articles -->
    @if($related->count() > 0)
    <div class="related-section">
        <h3 class="related-title">Artikel Terkait</h3>
        <div class="related-grid">
            @foreach($related as $item)
            <a href="{{ route('education.show', $item->id_konten) }}" style="text-decoration: none;">
                <div class="related-card">
                    <h4>{{ $item->judul }}</h4>
                    <p>{{ Str::limit($item->ringkasan, 100) }}</p>
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reactionButtons = document.querySelectorAll('.reaction-btn');
    const toast = document.getElementById('notificationToast');
    const toastEmoji = document.getElementById('toastEmoji');
    const toastMessage = document.getElementById('toastMessage');

    // Emoji mapping
    const emojiMap = {
        'suka': '❤️',
        'membantu': '👍',
        'menarik': '🔥',
        'inspiratif': '✨'
    };

    function showToast(emoji, message, type = 'success') {
        toastEmoji.textContent = emoji;
        toastMessage.textContent = message;
        toast.className = `notification-toast ${type} show`;
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    reactionButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const reactionType = this.dataset.reaction;
            const kontenId = this.dataset.konten;
            const wasActive = this.classList.contains('active');

            try {
                const response = await fetch(`/education/${kontenId}/reaction`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        tipe_reaksi: reactionType
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update UI based on action
                    if (data.action === 'removed') {
                        this.classList.remove('active');
                        showToast(emojiMap[reactionType], 'Reaksi dihapus', 'success');
                    } else if (data.action === 'added') {
                        reactionButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        showToast(emojiMap[reactionType], 'Terima kasih atas reaksinya!', 'success');
                    } else if (data.action === 'updated') {
                        reactionButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        showToast(emojiMap[reactionType], 'Reaksi diperbarui', 'success');
                    }

                    // Update counts
                    if (data.counts) {
                        reactionButtons.forEach(btn => {
                            const type = btn.dataset.reaction;
                            const countDisplay = btn.querySelector('.reaction-count-display');
                            if (countDisplay) {
                                countDisplay.textContent = data.counts[type] || 0;
                            }
                        });

                        // Update total
                        const totalDisplay = document.querySelector('.stat-value');
                        if (totalDisplay) {
                            totalDisplay.textContent = data.counts.total;
                        }
                    }
                } else {
                    showToast('❌', data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('❌', 'Terjadi kesalahan saat mengirim reaksi', 'error');
            }
        });
    });
});
</script>
@endsection