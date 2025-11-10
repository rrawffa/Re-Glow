@extends('layouts.app')

@section('title', 'Re-Glow - User Profile')

@section('styles')
@vite(['resources/css/pages/profile.css'])
<style>
    <!-- Profile Header (static placeholder) -->
    <div class="profile-header">
        <div class="profile-info">
            <h1>Guest User</h1>
            <p class="profile-bio">This is a placeholder profile page. Real user data will be shown here once backend is implemented.</p>
            <a href="#" class="edit-profile-btn">Edit Profile</a>
        </div>
        <img src="{{ asset('images/default-avatar.jpg') }}" alt="Guest User" class="profile-avatar">
    </div>

    <!-- Stats Grid (static placeholders) -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🌱</div>
            <div class="stat-value">0</div>
            <div class="stat-label">Available Points</div>
            <div class="stat-sublabel">For free deals</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💬</div>
            <div class="stat-value">0</div>
            <div class="stat-label">My Community Posts</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">❤️</div>
            <div class="stat-value">0</div>
            <div class="stat-label">Saved Vouchers</div>
        </div>

        <div class="stat-card highlight">
            <div class="stat-icon">🌍</div>
            <div class="stat-value">0 items recycled</div>
            <div class="stat-label">Environmental Impact</div>
            <div class="stat-sublabel">Making every day greener</div>
        </div>
    </div>

    <!-- Tabs (static links) -->
    <div class="tabs">
        <a href="#" class="tab active">My Posts</a>
        <a href="#" class="tab">Recycling History</a>
        <a href="#" class="tab">Redeemed Vouchers</a>
    </div>

    <!-- Posts Container (static placeholders) -->
    <div class="posts-container">
        <div class="post-card">
            <div class="post-header">
                <span class="post-icon">💡</span>
                <h3 class="post-title">Welcome to Re-Glow</h3>
                <span class="post-time">Just now</span>
            </div>
            <p class="post-description">This is a static demo post on the placeholder profile page.</p>
        </div>

        <div class="post-card">
            <div class="post-header">
                <span class="post-icon">♻️</span>
                <h3 class="post-title">Start recycling today</h3>
                <span class="post-time">1 day ago</span>
            </div>
            <p class="post-description">Share tips and tricks about reducing waste and living sustainably.</p>
        </div>
    </div>
        background: #f0f9f0;
    }

    .stat-icon {
        font-size: 28px;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .stat-label {
        font-size: 13px;
        color: #666;
        font-weight: 500;
    }

    .stat-sublabel {
        font-size: 11px;
        color: #999;
    }

    .tabs {
        display: flex;
        gap: 32px;
        border-bottom: 2px solid #e0e0e0;
        margin-bottom: 32px;
    }

    .tab {
        padding: 12px 0;
        font-size: 15px;
        font-weight: 500;
        color: #666;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        transition: color 0.2s, border-color 0.2s;
        text-decoration: none;
    }

    .tab:hover {
        color: #333;
    }

    .tab.active {
        color: #1a1a1a;
        border-bottom-color: #4a90e2;
    }

    .posts-container {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .post-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: box-shadow 0.2s;
    }

    .post-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .post-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .post-icon {
        font-size: 18px;
    }

    .post-title {
        flex: 1;
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .post-time {
        font-size: 13px;
        color: #999;
    }

    .post-description {
        font-size: 14px;
        line-height: 1.6;
        color: #666;
        margin-bottom: 16px;
    }

    .post-image {
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 16px;
        object-fit: cover;
        max-height: 400px;
    }

    .post-actions {
        display: flex;
        gap: 24px;
        align-items: center;
    }

    .post-action {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #999;
        cursor: pointer;
        transition: color 0.2s;
    }

    .post-action:hover {
        color: #666;
    }

    .settings-btn {
        position: fixed;
        bottom: 32px;
        left: 32px;
        padding: 12px 24px;
        background: #2c5f4f;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .settings-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .logout-btn {
        position: fixed;
        bottom: 32px;
        right: 32px;
        padding: 12px 24px;
        background: transparent;
        color: #999;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .logout-btn:hover {
        color: #666;
        border-color: #999;
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column-reverse;
            align-items: center;
            text-align: center;
        }

        .profile-bio {
            max-width: 100%;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .tabs {
            overflow-x: auto;
            gap: 20px;
        }

        .settings-btn,
        .logout-btn {
            bottom: 16px;
            left: 16px;
            right: auto;
        }

        .logout-btn {
            left: auto;
            right: 16px;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <!-- Profile Header -->
    @if($user)
    <div class="profile-header">
        <div class="profile-info">
            <h1>{{ $user->name ?? 'User' }}</h1>
            <p class="profile-bio">{{ $user->bio ?? 'No bio available.' }}</p>
            <a class="edit-profile-btn">Edit Profile</a>
        </div>
        <img src="{{ $user->avatar_url ?? asset('images/default-avatar.jpg') }}" alt="{{ $user->name ?? 'User' }}" class="profile-avatar">
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🌱</div>
            <div class="stat-value">{{ number_format($user->available_points ?? 0) }}</div>
            <div class="stat-label">Available Points</div>
            <div class="stat-sublabel">For free deals</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💬</div>
            <div class="stat-value">{{ $user->community_posts_count ?? 0 }}</div>
            <div class="stat-label">My Community Posts</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">❤️</div>
            <div class="stat-value">{{ $user->saved_vouchers_count ?? 0 }}</div>
            <div class="stat-label">Saved Vouchers</div>
        </div>

        <div class="stat-card highlight">
            <div class="stat-icon">🌍</div>
            <div class="stat-value">{{ ($user->items_recycled ?? 0) }} items recycled</div>
            <div class="stat-label">Environmental Impact</div>
            <div class="stat-sublabel">Making every day greener</div>
        </div>
    </div>
    @else
    <div class="alert alert-warning">
        <p>Please log in to view your profile.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
    </div>
    @endif

    <!-- Tabs -->
    <div class="tabs">
        <a href="{{ route('user.profile.show', ['tab' => 'posts']) }}" class="tab {{ $activeTab === 'posts' ? 'active' : '' }}">My Posts</a>
        <a href="{{ route('user.profile.show', ['tab' => 'history']) }}" class="tab {{ $activeTab === 'history' ? 'active' : '' }}">Recycling History</a>
        <a href="{{ route('vouchers.favorites', ['tab' => 'vouchers']) }}" class="tab {{ $activeTab === 'vouchers' ? 'active' : '' }}">Redeemed Vouchers</a>
    </div>

    <!-- Posts Container -->
    <div class="posts-container">
        @forelse($posts as $post)
        <div class="post-card">
            <div class="post-header">
                <span class="post-icon">
                    @if($post->type === 'tip')
                        ♻️
                    @elseif($post->type === 'haul')
                        📦
                    @elseif($post->type === 'event')
                        👥
                    @else
                        💡
                    @endif
                </span>
                <h3 class="post-title">{{ $post->title }}</h3>
                <span class="post-time">{{ $post->created_at->diffForHumans() }}</span>
            </div>

            <p class="post-description">{{ $post->description }}</p>

            @if($post->image_url)
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="post-image">
            @endif

            <div class="post-actions">
                <span class="post-action">
                    ❤️ {{ $post->likes_count }}
                </span>
                <span class="post-action">
                    💬 {{ $post->comments_count }}
                </span>
            </div>
        </div>
        @empty
        <div class="post-card">
            <p style="text-align: center; color: #999;">No posts yet. Start sharing your recycling journey!</p>
        </div>
        @endforelse
    </div>

</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection