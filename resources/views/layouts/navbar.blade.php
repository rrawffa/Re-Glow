<nav class="navbar">
    <div class="logo" style="display:flex;align-items:center;gap:8px;">
        <img src="{{ asset('assets/re-glow.svg') }}" width="40" height="40" alt="Re-Glow logo">
        <span>Re-Glow</span>
    </div>
    <ul class="nav-menu">
    <li>
        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" 
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            @elseif(Auth::user()->role === 'tim_logistik')
                <a href="{{ route('logistik.dashboard') }}" 
                class="{{ request()->routeIs('logistik.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            @else
                <a href="{{ route('user.dashboard') }}" 
                class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            @endif
        @else
            <a href="{{ route('welcome') }}">Dashboard</a>
        @endauth
    </li>
        <li><a href="#exchange">Exchange Waste</a></li>
        <li><a href="#points">Points</a></li>
        <li><a href="#vouchers">Vouchers</a></li>
        <li><a href="#community">Community</a></li>
        <li><a href="{{ url('/education') }}" class="{{ request()->is('education') ? 'active' : '' }}">Education</a></li>
        <li><a href="#faq">FAQ</a></li>
    </ul>
    <div class="nav-icons">
        <button class="icon-btn">🔔</button>
        <img src="https://i.pravatar.cc/150?img=47" alt="Profile" class="profile-pic">
    </div>
</nav>

<style>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 5%;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'Bricolage Grotesque', sans-serif;
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--green-dark);
    }

    .nav-menu {
        display: flex;
        gap: 2rem;
        list-style: none;
        align-items: center;
    }

    .nav-menu a {
        text-decoration: none;
        color: var(--text-gray);
        font-weight: 500;
        transition: color 0.3s;
    }

    .nav-menu a:hover,
    .nav-menu a.active {
        color: var(--green-dark);
    }

    .nav-menu a.active {
        border-bottom: 2px solid var(--green-dark);
        padding-bottom: 0.25rem;
    }

    .nav-icons {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .icon-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.25rem;
        color: var(--text-gray);
    }

    .profile-pic {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .nav-menu {
            display: none;
        }
    }
</style>