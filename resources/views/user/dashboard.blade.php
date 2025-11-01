@extends('layouts.app')

@section('title', 'Re-Glow - Dashboard Pengguna')

@section('styles')
<style>
    /* Adapted to match visual scheme from views/education */
    .hero {
        background: linear-gradient(135deg, var(--pink-light, #fef5f8) 0%, #FFF 100%);
        padding: 4.5rem 5% 3rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(70vh - 80px);
    }

    .welcome-card {
        background: white;
        padding: 3.5rem 2.5rem;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
        text-align: center;
        max-width: 720px;
        width: 100%;
    }

    .user-icon {
        width: 96px;
        height: 96px;
        background: linear-gradient(135deg, var(--pink-base, #F9B6C7) 0%, #ffc9d4 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 2.25rem;
        box-shadow: 0 6px 20px rgba(249,182,199,0.18);
    }

    .welcome-text {
        font-size: 1.125rem;
        color: var(--text-gray, #666);
        margin-bottom: 0.25rem;
    }

    .username-text {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--pink-base, #F9B6C7);
        margin-bottom: 0.75rem;
    }

    .role-badge {
        display: inline-block;
        padding: 8px 20px;
        background: var(--pink-base, #F9B6C7);
        color: white;
        border-radius: 999px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .description {
        margin-top: 1.25rem;
        color: var(--text-gray, #888);
        font-size: 1rem;
        line-height: 1.7;
    }

    .dashboard-actions {
        margin-top: 1.75rem;
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: var(--green-dark, #20413A);
        color: white;
        padding: 0.9rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary {
        background: #fff;
        color: var(--green-dark, #20413A);
        border: 1px solid rgba(32,65,58,0.08);
        padding: 0.8rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .welcome-card {
            padding: 2rem;
        }

        .username-text {
            font-size: 1.6rem;
        }

        .user-icon {
            width: 72px;
            height: 72px;
            font-size: 1.6rem;
        }
    }
</style>
@endsection

@section('content')
    <section class="hero" aria-labelledby="dashboard-heading">
        <div class="welcome-card">
            <div class="user-icon">👤</div>
            <p class="welcome-text">Halo,</p>
            <h2 id="dashboard-heading" class="username-text">{{ Session::get('username') ?? 'Pengguna' }}!</h2>
            <div class="role-badge">PENGGUNA</div>

            <p class="description">
                Selamat datang di Re-Glow! Mulai perjalanan Anda untuk memberikan dampak positif pada planet dengan mendaur ulang limbah kosmetik.
                Jelajahi artikel edukasi, pantau aktivitas daur ulang, dan ikuti program kami untuk efek yang nyata.
            </p>

            <div class="dashboard-actions">
                <a href="{{ route('education.index') }}" class="btn-primary">Jelajahi Edukasi</a>
                <a href="{{ route('user.profile') }}" class="btn-secondary">Lihat Profil</a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // ...existing code...
</script>
@endsection