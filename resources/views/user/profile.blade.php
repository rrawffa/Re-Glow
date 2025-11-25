@extends('layouts.app')

@section('title', 'Re-Glow - User Profile')

@section('styles')
@vite(['resources/css/pages/profile.css'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .profile-container { padding: 20px; max-width: 1200px; margin: 0 auto; }
    .profile-header { display: flex; gap: 30px; align-items: center; margin-bottom: 40px; }
    .profile-info { flex: 1; }
    .profile-name { font-size: 32px; font-weight: 700; margin: 0 0 12px; color: #1a1a1a; }
    .profile-bio { color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 16px; }
    .btn-edit-profile { display: inline-block; padding: 8px 16px; background: #2c5f4f; color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600; }
    .btn-edit-profile:hover { background: #1f4438; }
    .avatar-container { 
        width: 120px; 
        height: 120px; 
        border-radius: 12px; 
        background: linear-gradient(135deg, #2c5f4f 0%, #1f4438 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }
    .avatar-icon {
        font-size: 48px;
        color: white;
    }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 18px; margin-bottom: 32px; }
    .stat-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 6px 16px rgba(0,0,0,0.04); display: flex; gap: 16px; align-items: center; }
    .stat-card-highlight { background: #f7fff8; }
    .stat-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: #f0f0f0; border-radius: 8px; flex-shrink: 0; font-size: 24px; color: #2c5f4f; }
    .stat-content { flex: 1; }
    .stat-value { font-size: 24px; font-weight: 700; color: #1a1a1a; }
    .stat-label { font-size: 13px; color: #666; margin-top: 4px; }
    .profile-tabs { display: flex; gap: 0; border-bottom: 2px solid #eee; margin-bottom: 24px; }
    .tab-btn { background: none; border: none; padding: 12px 20px; cursor: pointer; font-size: 15px; font-weight: 600; color: #666; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: all 0.2s; }
    .tab-btn:hover { color: #333; }
    .tab-btn.active { color: #1a1a1a; border-bottom-color: #4a90e2; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .post-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 6px 16px rgba(0,0,0,0.04); margin-bottom: 20px; }
    .post-header { display: flex; gap: 12px; align-items: center; margin-bottom: 12px; }
    .post-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #f0f0f0; border-radius: 6px; font-size: 18px; color: #2c5f4f; }
    .post-title { flex: 1; font-size: 16px; font-weight: 700; color: #1a1a1a; margin: 0; }
    .post-time { font-size: 13px; color: #999; }
    .post-description { font-size: 14px; line-height: 1.6; color: #666; margin-bottom: 12px; }
    .post-image { margin-bottom: 12px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 200px; }
    .post-image img { width: 100%; height: auto; display: block; }
    .post-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid #eee; }
    .post-stats { display: flex; gap: 16px; }
    .stat-item { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #666; }
    .placeholder-content { text-align: center; padding: 60px 20px; }
    .placeholder-content h3 { font-size: 18px; font-weight: 700; color: #1a1a1a; margin: 0 0 8px; }
    .placeholder-content p { color: #999; font-size: 14px; }
    .profile-actions { display: flex; gap: 12px; margin-top: 40px; justify-content: center; padding-top: 20px; border-top: 1px solid #eee; }
    .btn-logout { padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; background: #fff4f4; color: #d33; }
    .btn-logout:hover { background: #ffe6e6; }
    .btn-transaction-history { padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; background: #e8f4ff; color: #2c5f4f; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-transaction-history:hover { background: #d9ecff; }
</style>
@endsection

@section('content')
<div class="profile-container">
    <!-- Profile Header Section -->
    <div class="profile-header">
        <div class="profile-info">
            <h1 class="profile-name">{{ $user->username ?? 'User' }}</h1>
            <p class="profile-bio">{{ $user->bio ?? 'Welcome to Re-Glow community!' }}</p>
            <div class="d-flex gap-2">
                <a href="{{ route('user.profile.edit') }}" class="btn-edit-profile">Edit Profile</a>
                <a href="{{ route('waste-exchange.history') }}" class="btn-transaction-history">
                    <i class="bi bi-clock-history"></i> Riwayat Transaksi
                </a>
            </div>
        </div>
        <div class="avatar-container">
            <i class="bi bi-person-fill avatar-icon"></i>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-tree-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $user->poin ?? 0 }}</div>
                <div class="stat-label">Available Points</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $user->email }}</div>
                <div class="stat-label">Email</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-phone-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $user->no_hp ?? 'Not set' }}</div>
                <div class="stat-label">Phone Number</div>
            </div>
        </div>

        <div class="stat-card stat-card-highlight">
            <div class="stat-icon">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $user->role }}</div>
                <div class="stat-label">Role</div>
            </div>
        </div>
    </div>

    <!-- Profile Actions -->
    <div class="profile-actions">
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i> Log Out
            </button>
        </form>
    </div>
</div>
@endsection