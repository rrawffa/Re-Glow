@extends('layouts.app')

@section('title', 'Re-Glow - Dashboard Pengguna')

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    :root {
        --green-dark: #20413A;
        --green-light: #C1D0B5;
        --pink-base: #F9B6C7;
        --pink-light: #FFF0F3;
        --text-gray: #666;
        --text-dark: #333;
    }

    /* Hero Section */
    .hero {
        background: linear-gradient(135deg, rgba(193, 208, 181, 0.3) 0%, rgba(255, 255, 255, 0.8) 100%),
                    url('/images/dashboard-hero.jpg') center/cover no-repeat;
        padding: 4rem 5% 3rem;
        min-height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.4);
        z-index: 0;
    }

    .hero-content {
        max-width: 800px;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .hero-content h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .hero-content p {
        font-size: 1.125rem;
        color: var(--text-gray);
        margin-bottom: 2rem;
        line-height: 1.7;
    }

    .btn-start {
        background: var(--pink-base);
        color: white;
        padding: 1rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .btn-start:hover {
        background: #F7A3B8;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(249, 182, 199, 0.3);
    }
<<<<<<< HEAD
=======

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

    /* Stats Section Styling */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        padding: 2rem 5%;
        background: white;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.75rem;
    }

    .stat-icon.pink { background: var(--pink-light); }
    .stat-icon.green { background: #E8F5E9; }
    .stat-icon.yellow { background: #FFF8E1; }

    .stat-info h3 {
        color: var(--green-dark);
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .stat-info .number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--pink-base);
        margin-bottom: 0.25rem;
    }

    .stat-info .subtext {
        color: var(--text-gray);
        font-size: 0.9rem;
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

    /* Learning Section */
    .learning-section {
        padding: 4rem 5%;
        background: white;
    }

    .learning-section h2 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--green-dark);
        text-align: center;
        margin-bottom: 2rem;
    }

    .learning-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .learning-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
    }

    .learning-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .learning-card h3 {
        color: var(--green-dark);
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .learning-card p {
        color: var(--text-gray);
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .learning-card .meta {
        color: var(--text-gray);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-read {
        background: var(--pink-base);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .card-actions {
        display: flex;
        gap: 0.5rem;
        font-size: 1.125rem;
    }

    /* Challenge Banner */
    .challenge-banner {
        background: linear-gradient(135deg, #F9B6C7 0%, #ffc9d4 100%);
        color: var(--green-dark);
        padding: 3rem 5%;
        text-align: center;
        margin: 2rem 5%;
        border-radius: 16px;
    }

    .challenge-banner h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: var(--green-dark);
    }

    .challenge-banner p {
        max-width: 600px;
        margin: 0 auto 1.5rem;
        line-height: 1.6;
        opacity: 0.85;
        color: #20413A;
    }

    @media (max-width: 768px) {
        .welcome-card {
            padding: 2rem;
        }
>>>>>>> 3b61eda371393da1f4365e527486502abe295f5e

    /* Stats Section */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        padding: 3rem 5%;
        background: white;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .stat-icon.pink {
        background: var(--pink-light);
    }

    .stat-icon.green {
        background: #E8F5E9;
    }

    .stat-icon.yellow {
        background: #FFF9C4;
    }

    .stat-info {
        flex: 1;
    }

    .stat-info h3 {
        font-size: 0.95rem;
        color: var(--text-gray);
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .stat-info .number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 0.25rem;
    }

    .stat-info .subtext {
        font-size: 0.85rem;
        color: var(--pink-base);
        cursor: pointer;
        font-weight: 500;
    }

    .stat-info .subtext:hover {
        text-decoration: underline;
    }

    /* Learning Section */
    .learning-section {
        padding: 4rem 5%;
        background: var(--pink-light);
    }

    .learning-section h2 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: 2rem;
        text-align: center;
    }

    .learning-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .learning-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .learning-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .learning-card h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--green-dark);
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .learning-card p {
        color: var(--text-gray);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .learning-card .meta {
        font-size: 0.85rem;
        color: var(--text-gray);
        margin-bottom: 1.5rem;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-read {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--pink-base);
        color: white;
        padding: 0.65rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: background 0.3s;
    }

    .btn-read:hover {
        background: #F7A3B8;
    }

    .card-actions {
        display: flex;
        gap: 0.5rem;
        font-size: 1.125rem;
    }

    .view-all-link {
        text-align: center;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--green-dark);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-view-all:hover {
        background: #16332d;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(32, 65, 58, 0.3);
    }

    /* Challenge Banner */
    .challenge-banner {
        background: linear-gradient(135deg, var(--green-dark) 0%, #2d5f54 100%);
        color: white;
        padding: 3rem 5%;
        text-align: center;
        margin: 4rem 5%;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .challenge-banner h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .challenge-banner p {
        font-size: 1.125rem;
        margin-bottom: 2rem;
        opacity: 0.95;
        line-height: 1.6;
    }

    .btn-primary {
        background: var(--pink-base);
        color: white;
        padding: 1rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background: #F7A3B8;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(249, 182, 199, 0.4);
    }

    /* Toast Notification */
    .toast {
        position: fixed;
        top: 100px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-left: 4px solid var(--pink-base);
        z-index: 9999;
        display: none;
        animation: slideIn 0.3s ease-out;
        font-weight: 500;
        color: var(--text-dark);
    }

    .toast.show {
        display: block;
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

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 1.75rem;
        }

<<<<<<< HEAD
        .hero-content p {
            font-size: 1rem;
        }

=======
>>>>>>> 3b61eda371393da1f4365e527486502abe295f5e
        .stats-container {
            grid-template-columns: 1fr;
            padding: 2rem 1rem;
        }

        .learning-grid {
            grid-template-columns: 1fr;
        }

        .challenge-banner {
            margin: 2rem 1rem;
            padding: 2rem 1rem;
        }

        .challenge-banner h2 {
            font-size: 1.5rem;
        }

        .challenge-banner p {
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
<<<<<<< HEAD
    <section class="hero">
=======
    <section class="hero" aria-labelledby="dashboard-heading">
>>>>>>> 3b61eda371393da1f4365e527486502abe295f5e
        <div class="hero-content">
            <h1>Welcome Back to Your<br>Sustainable Journey!</h1>
            <p>You're making a real difference in reducing cosmetic waste pollution. Every container you recycle helps create a cleaner, more sustainable future.</p>
            <button class="btn-start" onclick="showToast('Fitur sedang dalam pengembangan!')">Start Recycling</button>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-container">
        <div class="stat-card">
            <div class="stat-icon pink">♻</div>
            <div class="stat-info">
                <h3>Waste Collected</h3>
                <div class="number">247</div>
                <div class="subtext">containers recycled</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">⭐</div>
            <div class="stat-info">
                <h3>Points Earned</h3>
                <div class="number">1,235</div>
                <div class="subtext">Redeem Points</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow">🏆</div>
            <div class="stat-info">
                <h3>Achievements</h3>
                <div class="number">8</div>
                <div class="subtext">badges unlocked</div>
            </div>
        </div>
    </section>

    <!-- Learning Section -->
    <section class="learning-section">
        <h2>Recommended Learning</h2>
        <div class="learning-grid">
<<<<<<< HEAD
            @if($topArticles && $topArticles->count() > 0)
                @foreach($topArticles as $article)
                <div class="learning-card" onclick="window.location.href='{{ route('education.show', $article->id_konten) }}'">
                    <h3>{{ $article->judul }}</h3>
                    <p>{{ Str::limit($article->ringkasan, 120) }}</p>
                    <div class="meta">
                        📅 Posted {{ \Carbon\Carbon::parse($article->tanggal_upload)->diffForHumans() }}
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('education.show', $article->id_konten) }}" class="btn-read" onclick="event.stopPropagation()">
                            Read More →
                        </a>
                        <div class="card-actions">
                            @php
                                $counts = $article->getReactionCounts();
                            @endphp
                            <span title="Total Reactions">❤️ {{ $counts['total'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback jika tidak ada data -->
                <div class="learning-card" onclick="window.location.href='{{ route('education.index') }}'">
                    <h3>The Impact of Microplastics</h3>
                    <p>Learn how cosmetic packaging contributes to microplastic pollution and what we can do about it.</p>
                    <div class="meta">📅 Posted recently</div>
                    <div class="card-footer">
                        <a href="{{ route('education.index') }}" class="btn-read" onclick="event.stopPropagation()">
                            Read More →
                        </a>
                        <div class="card-actions">
                            <span title="Love">❤️</span>
                            <span title="Hot">🔥</span>
                        </div>
                    </div>
                </div>
                <div class="learning-card" onclick="window.location.href='{{ route('education.index') }}'">
                    <h3>Sustainable Beauty Brands</h3>
                    <p>Discover beauty brands leading the way in sustainable packaging and eco-friendly practices.</p>
                    <div class="meta">Posted recently</div>
                    <div class="card-footer">
                        <a href="{{ route('education.index') }}" class="btn-read" onclick="event.stopPropagation()">
                            Read More →
                        </a>
                        <div class="card-actions">
                            <span title="Love">❤️</span>
                            <span title="Hot">🔥</span>
                        </div>
=======
            <div class="learning-card">
                <h3>The Impact of Microplastics</h3>
                <p>Learn how cosmetic packaging contributes to microplastic pollution and what we can do about it.</p>
                <div class="meta">📅 Posted 2 days ago</div>
                <div class="card-footer">
                    <a href="{{ route('education.index') }}" class="btn-read">Read More →</a>
                    <div class="card-actions">
                        <span title="Love">❤</span>
                        <span title="Hot">🔥</span>
                    </div>
                </div>
            </div>
            <div class="learning-card">
                <h3>Sustainable Beauty Brands</h3>
                <p>Discover beauty brands leading the way in sustainable packaging and eco-friendly practices.</p>
                <div class="meta">Posted 1 week ago</div>
                <div class="card-footer">
                    <a href="{{ route('education.index') }}" class="btn-read">Read More →</a>
                    <div class="card-actions">
                        <span title="Love">❤</span>
                        <span title="Hot">🔥</span>
                    </div>
                </div>
            </div>
            <div class="learning-card">
                <h3>DIY Upcycling Ideas</h3>
                <p>Creative ways to repurpose your empty cosmetic containers before recycling them.</p>
                <div class="meta">Posted 3 days ago</div>
                <div class="card-footer">
                    <a href="{{ route('education.index') }}" class="btn-read">Read More →</a>
                    <div class="card-actions">
                        <span title="Love">❤</span>
                        <span title="Hot">🔥</span>
>>>>>>> 3b61eda371393da1f4365e527486502abe295f5e
                    </div>
                </div>
                <div class="learning-card" onclick="window.location.href='{{ route('education.index') }}'">
                    <h3>DIY Upcycling Ideas</h3>
                    <p>Creative ways to repurpose your empty cosmetic containers before recycling them.</p>
                    <div class="meta">Posted recently</div>
                    <div class="card-footer">
                        <a href="{{ route('education.index') }}" class="btn-read" onclick="event.stopPropagation()">
                            Read More →
                        </a>
                        <div class="card-actions">
                            <span title="Love">❤️</span>
                            <span title="Hot">🔥</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- View All Link -->
        <div class="view-all-link">
            <a href="{{ route('education.index') }}" class="btn-view-all">
                View All Articles →
            </a>
        </div>
    </section>

    <!-- Challenge Banner -->
    <section class="challenge-banner">
        <h2>Join Our Recycling Challenge!</h2>
        <p>This month, we're challenging our community to recycle 10,000 cosmetic containers. Join hundreds of eco-warriors making a difference!</p>
        <button class="btn-primary" onclick="showToast('Berhasil bergabung dengan challenge!')">Join Challenge</button>
    </section>
<<<<<<< HEAD

=======
    
>>>>>>> 3b61eda371393da1f4365e527486502abe295f5e
    <!-- Toast container -->
    <div id="dashboardToast" class="toast" role="status" aria-live="polite"></div>
@endsection

@section('scripts')
<script>
function showToast(message, ms = 3500) {
    const t = document.getElementById('dashboardToast');
    if (!t) return;
    t.textContent = message;
    t.classList.add('show');
    clearTimeout(t._hideTimer);
    t._hideTimer = setTimeout(() => t.classList.remove('show'), ms);
}

<<<<<<< HEAD
document.addEventListener('DOMContentLoaded', function() {
    // Greeting
    const username = "{{ addslashes(Session::get('username') ?? 'User') }}";
    setTimeout(() => {
        showToast('Selamat datang, ' + username + '!');
    }, 500);
});
=======
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
>>>>>>> 3b61eda371393da1f4365e527486502abe295f5e
</script>
@endsection