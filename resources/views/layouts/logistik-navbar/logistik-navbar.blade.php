@vite(['resources/css/layouts/navbar.css'])
<nav class="navbar">
    <div class="logo" style="display:flex;align-items:center;gap:8px;">
        <img src="{{ asset('assets/re-glow.svg') }}" width="40" height="40" alt="Re-Glow logo">
        <span>Re-Glow</span>
    </div>
    <ul class="nav-menu">
        <li>
            <a href="{{ url('logistik/dashboard') }}" 
               class="{{ request()->is('logistik/dashboard') ? 'active' : '' }}">
               Dashboard
            </a>
        </li>
        <li>Schedule</li>
        <li>Routes</li>
        <li>History</li>
    </ul>
    <div class="nav-icons">
        <button class="icon-btn">🔔</button>
        <img src="https://i.pravatar.cc/150?img=47" alt="Profile" class="profile-pic">
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</nav>