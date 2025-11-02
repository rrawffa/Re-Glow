@extends('layouts.app')

@section('title', 'Close the Loop - Track Your Cosmetic Waste Journey')

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --pink-base: #F9B6C7;
        --green-dark: #20413A;
        --green-light: #BAC2AB;
        --pink-light: #FFF5F7;
        --text-dark: #2D2D2D;
        --text-gray: #666666;
    }

    body {
        font-family: 'DM Sans', sans-serif;
        color: var(--text-dark);
        line-height: 1.6;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Bricolage Grotesque', sans-serif;
    }

    .exchange-hero {
        background: linear-gradient(135deg, #F9B6C7 0%, #BAC2AB 100%);
        padding: 4rem 5%;
        text-align: center;
        border-radius: 0 0 50px 50px;
    }

    .exchange-hero h1 {
        font-size: 2.5rem;
        color: var(--green-dark);
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .exchange-hero h1 .highlight {
        color: var(--pink-base);
    }

    .exchange-hero p {
        font-size: 1.125rem;
        color: var(--text-dark);
        max-width: 700px;
        margin: 0 auto 2rem;
    }

    .btn-primary {
        background: var(--green-dark);
        color: white;
        padding: 1rem 2.5rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.125rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background: #163026;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .process-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        padding: 4rem 5%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .process-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        text-align: center;
        transition: transform 0.3s;
    }

    .process-card:hover {
        transform: translateY(-10px);
    }

    .process-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .process-card h3 {
        color: var(--green-dark);
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .journey-section {
        background: var(--pink-light);
        padding: 4rem 5%;
    }

    .journey-title {
        text-align: center;
        margin-bottom: 3rem;
    }

    .journey-title h2 {
        font-size: 2rem;
        color: var(--green-dark);
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .journey-timeline {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
    }

    .timeline-item {
        display: flex;
        gap: 2rem;
        margin-bottom: 3rem;
        align-items: center;
    }

    .timeline-icon {
        width: 70px;
        height: 70px;
        background: var(--green-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
    }

    .timeline-content {
        flex: 1;
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .timeline-content h4 {
        color: var(--green-dark);
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .location-section {
        padding: 4rem 5%;
    }

    .location-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        max-width: 1200px;
        margin: 2rem auto 0;
    }

    .map-container {
        background: #f0f0f0;
        border-radius: 20px;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
    }

    .nearby-locations {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .nearby-locations h3 {
        color: var(--green-dark);
        font-weight: 600;
    }

    .location-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .location-icon {
        width: 50px;
        height: 50px;
        background: var(--pink-base);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .location-info h4 {
        color: var(--green-dark);
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .location-info p {
        font-size: 0.9rem;
        color: var(--text-gray);
        margin-bottom: 0.25rem;
    }

    .distance {
        color: var(--pink-base);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .btn-view-all {
        background: var(--pink-base);
        color: white;
        padding: 0.875rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        margin-top: 1rem;
    }

    .cta-section {
        background: var(--green-dark);
        color: white;
        padding: 4rem 5%;
        text-align: center;
    }

    .cta-section h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .cta-stats {
        display: flex;
        justify-content: center;
        gap: 4rem;
        margin-top: 3rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        display: block;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: rgba(255,255,255,0.8);
    }

    .btn-cta {
        background: var(--pink-base);
        color: white;
        padding: 1rem 2.5rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.125rem;
        cursor: pointer;
        margin-top: 2rem;
        text-decoration: none;
        display: inline-block;
    }

    @media (max-width: 768px) {
        .process-cards {
            grid-template-columns: 1fr;
        }
        
        .location-grid {
            grid-template-columns: 1fr;
        }

        .cta-stats {
            flex-direction: column;
            gap: 2rem;
        }
    }
</style>
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