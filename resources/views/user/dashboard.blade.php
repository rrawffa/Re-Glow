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
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 450ms var(--ease, cubic-bezier(.2,.9,.2,1)), transform 450ms var(--ease);
    }

    .welcome-card.visible {
        opacity: 1;
        transform: translateY(0);
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

    /* Small utilities */
    .meta-row {
        margin-top: .75rem;
        display: flex;
        gap: 1rem;
        justify-content: center;
        align-items: center;
        color: var(--text-gray, #888);
        font-size: 0.95rem;
    }

    .toast {
        position: fixed;
        top: 16px;
        right: 16px;
        background: rgba(32,65,58,0.95);
        color: #fff;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        transform: translateY(-8px);
        opacity: 0;
        transition: opacity 300ms, transform 300ms;
        z-index: 60;
        pointer-events: none;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
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
        <div class="welcome-card" id="welcomeCard" role="region" aria-live="polite" aria-labelledby="dashboard-heading">
            <div class="user-icon" aria-hidden="true">👤</div>
            <p class="welcome-text">Halo,</p>
            <h2 id="dashboard-heading" class="username-text">{{ Session::get('username') ?? 'Pengguna' }}!</h2>
            <div class="role-badge" aria-hidden="true">PENGGUNA</div>

            <div class="meta-row" aria-hidden="false">
                <div id="localTime" title="Waktu Lokal">⏰ --:--</div>
                <div title="Last login">🔔 Terakhir login: {{ Session::get('last_login') ?? '—' }}</div>
            </div>

            <p class="description">
                Selamat datang di Re-Glow! Mulai perjalanan Anda untuk memberikan dampak positif pada planet dengan mendaur ulang limbah kosmetik.
                Jelajahi artikel edukasi, pantau aktivitas daur ulang, dan ikuti program kami untuk efek yang nyata.
            </p>

            <div class="dashboard-actions" role="group" aria-label="Tindakan dashboard">
                <a href="{{ route('education.index') }}" class="btn-primary" id="btnEducation">Jelajahi Edukasi</a>
            </div>
        </div>
    </section>

    <!-- Toast container -->
    <div id="dashboardToast" class="toast" role="status" aria-live="polite"></div>
@endsection

@section('scripts')
<script>
    (function () {
        // Short helper
        const $ = sel => document.querySelector(sel);

        // Animate welcome card entrance
        document.addEventListener('DOMContentLoaded', function () {
            const card = $('#welcomeCard');
            if (card) {
                // slight stagger for nicer feel
                setTimeout(() => card.classList.add('visible'), 80);
            }

            // Show toast greeting once
            const username = "{{ addslashes(Session::get('username') ?? 'Pengguna') }}";
            showToast('Selamat datang, ' + username + '!');
            // Start local time updater
            updateLocalTime();
            setInterval(updateLocalTime, 60 * 1000);
        });

        function showToast(message, ms = 3500) {
            const t = $('#dashboardToast');
            if (!t) return;
            t.textContent = message;
            t.classList.add('show');
            clearTimeout(t._hideTimer);
            t._hideTimer = setTimeout(() => t.classList.remove('show'), ms);
        }

        function updateLocalTime() {
            const el = $('#localTime');
            if (!el) return;
            const now = new Date();
            const opts = { hour: '2-digit', minute: '2-digit' };
            el.textContent = '⏰ ' + now.toLocaleTimeString([], opts);
        }

        // Smooth navigation to education when on same page (fallback to link behavior)
        const btnEdu = $('#btnEducation');
        if (btnEdu) {
            btnEdu.addEventListener('click', function (e) {
                // if page contains an anchor target, try smooth scroll, else let default navigate
                const href = this.getAttribute('href') || '';
                if (href.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                // else default navigation to route
            });
        }

        // Profile button small visual feedback
        const btnProfile = $('#btnProfile');
        if (btnProfile) {
            btnProfile.addEventListener('click', function () {
                // brief feedback
                this.style.transform = 'translateY(-2px)';
                setTimeout(() => this.style.transform = '', 180);
            });
        }
    })();
</script>
@endsection