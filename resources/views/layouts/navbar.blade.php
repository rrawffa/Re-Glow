@vite(['resources/css/layouts/navbar.css'])
<nav class="navbar">
    <div class="logo" style="display:flex;align-items:center;gap:8px;">
        <img src="{{ asset('assets/re-glow.svg') }}" width="40" height="40" alt="Re-Glow logo">
        <span>Re-Glow</span>
    </div>

    <ul class="nav-menu">
        <li>
            @if(Session::get('user_role') === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            @else
            <a href="{{ route('user.dashboard') }}" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
            @endif
        </li>
        <li><a href="{{ route('waste-exchange.index') }}">Exchange Waste</a></li>
        <li><a href="#points">Points</a></li>
        <li><a href="#vouchers">Vouchers</a></li>
        <li><a href="{{ route('community.index') }}" class="{{ request()->is('community') ? 'active' : '' }}">Community</a></li>
        <li>
            @if(Session::get('user_role') === 'admin')
                <a href="{{ route('admin.waste.index') }}" class="{{ request()->is('admin/waste-exchange*') ? 'active' : '' }}">
                    Exchange Waste
                </a>
            @else
                <a href="{{ route('waste-exchange.index') }}" class="{{ request()->is('waste-exchange') ? 'active' : '' }}">
                    Exchange Waste
                </a>
            @endif
        </li>
        <li><a href="{{ url('/riwayat-poin') }}" class="{{ request()->is('riwayat-poin') ? 'active' : '' }}">Points</a></li>
        <li><a href="{{ route('vouchers.index') }}" class="{{ request()->is('vouchers') ? 'active' : '' }}">Vouchers</a></li>
        <li><a href="{{ route('community.index') }}" class="{{ request()->is('community') ? 'active' : '' }}">Community</a></li>
        <li><a href="{{ url('/education') }}" class="{{ request()->is('education') ? 'active' : '' }}">Education</a></li>
        <li><a href="{{ url('/faq') }}" class="{{ request()->is('faq') ? 'active' : '' }}">FAQ</a></li>
    </ul>

    <div class="nav-icons">
        <button class="icon-btn">🔔</button>
        <a href="{{ route('user.profile.show') }}">
            <img src="https://i.pravatar.cc/150?img=47" alt="Profile" class="profile-pic">
        </a>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</nav>