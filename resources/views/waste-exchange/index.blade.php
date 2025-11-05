@extends('layouts.app')

@section('title', 'Close the Loop - Track Your Cosmetic Waste Journey')

@section('styles')
    @vite(['resources/css/waste-exchange/index.css'])
@endsection

@section('content')
<div class="exchange-hero">
    <h1>Close the Loop: <span class="highlight">Track Your Cosmetic Waste Journey!</span></h1>
    <p>Transform your empty cosmetic containers into something beautiful. Join our transparent recycling process and watch your waste become part of the circular economy.</p>
    <a href="{{ route('waste-exchange.create') }}" class="btn-primary">
        Explore the Process ➜
    </a>
</div>

<div class="process-cards">
    <div class="process-card">
        <div class="process-icon">📦</div>
        <h3>Drop Off</h3>
        <p>Bring your empty cosmetics to any Re-Glow point and earn reward points for each item.</p>
    </div>
    <div class="process-card">
        <div class="process-icon">♻️</div>
        <h3>Transform</h3>
        <p>We process and recycle into new materials with our verified recycling partners.</p>
    </div>
    <div class="process-card">
        <div class="process-icon">🌱</div>
        <h3>Rebirth</h3>
        <p>New sustainable products are created, completing the circular journey.</p>
    </div>
</div>

<div class="journey-section">
    <div class="journey-title">
        <h2>From Empty to Evergreen: Your Waste's Journey</h2>
        <p>Follow the transparent path of your cosmetic waste transformation</p>
    </div>
    
    <div class="journey-timeline">
        <div class="timeline-item">
            <div class="timeline-icon">💄</div>
            <div class="timeline-content">
                <h4>User Drop-off</h4>
                <p>You bring your empty cosmetic containers to our designated drop points and earn reward points for each item.</p>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-icon">🚚</div>
            <div class="timeline-content">
                <h4>Logistics Pick-up</h4>
                <p>Our logistics team collects all waste from drop points using eco-friendly vehicles on scheduled routes.</p>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-icon">🏭</div>
            <div class="timeline-content">
                <h4>Partner Processing</h4>
                <p>Waste is sent to our verified recycling partners where it's carefully sorted, cleaned, and processed.</p>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-icon">🌿</div>
            <div class="timeline-content">
                <h4>New Sustainable Product</h4>
                <p>The recycled materials are transformed into new eco-friendly cosmetic products, completing the circular journey.</p>
            </div>
        </div>
    </div>
</div>

<div class="location-section">
    <div class="journey-title">
        <h2>Find Your Nearest Drop Point & Get Started</h2>
        <p>Locate convenient drop-off locations near you</p>
    </div>

    <div class="location-grid">
        <div class="map-container">
            <div style="text-align:center">
                <span style="font-size:3rem">📍</span>
                <p><strong>Interactive Map</strong><br>Drop points marked with pins</p>
            </div>
        </div>

        <div class="nearby-locations">
            <h3>Nearby Locations</h3>
            
            @forelse($dropPoints->take(3) as $point)
            <div class="location-card">
                <div class="location-icon">📍</div>
                <div class="location-info">
                    <h4>{{ $point->nama_lokasi }}</h4>
                    <p>{{ $point->alamat }}</p>
                    <span class="distance">Drop Point Available</span>
                </div>
            </div>
            @empty
            <p>Belum ada drop point tersedia.</p>
            @endforelse

            <a href="{{ route('waste-exchange.create') }}" class="btn-view-all">View All Locations</a>
        </div>
    </div>
</div>

<div class="cta-section">
    <h2>Ready to Make an Impact?</h2>
    <p>Join thousands of eco-conscious individuals who are transforming waste into wonder. Start your exchange journey today.</p>
    
    <a href="{{ route('waste-exchange.create') }}" class="btn-cta">🔄 Start My Exchange Now!</a>

    <div class="cta-stats">
        <div class="stat-item">
            <span class="stat-number">{{ number_format($stats['total_transaksi'] ?? 5000) }}+</span>
            <span class="stat-label">Items Recycled</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ number_format(($stats['total_poin'] ?? 250000) / 100) }}K</span>
            <span class="stat-label">Points Distributed</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['drop_locations'] ?? 15 }}</span>
            <span class="stat-label">Drop Locations</span>
        </div>
    </div>
</div>
@endsection