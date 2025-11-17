@extends('layouts.app')

@section('title', 'Close the Loop - Track Your Cosmetic Waste Journey')

@section('styles')
    @vite(['resources/css/waste-exchange/index.css'])
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    
    <style>
        #map {
            width: 100%;
            height: 400px;
            border-radius: 20px;
            z-index: 1;
        }
        
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
        }
        
        .leaflet-popup-content {
            margin: 15px;
        }
        
        .map-popup h4 {
            margin: 0 0 8px 0;
            color: var(--green-dark);
            font-size: 1rem;
            font-weight: 600;
        }
        
        .map-popup p {
            margin: 0 0 5px 0;
            font-size: 0.875rem;
            color: var(--text-gray);
            line-height: 1.4;
        }
        
        .map-popup .badge {
            display: inline-block;
            background: var(--pink-base);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 5px;
        }
        
        /* Custom marker styles */
        .custom-marker {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--green-dark);
            border: 4px solid var(--pink-base);
            border-radius: 50%;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .custom-marker:hover {
            transform: scale(1.15);
        }
    </style>
@endsection

@section('content')
<div class="exchange-hero">
    <h1>Close the Loop: <span class="highlight">Track Your Cosmetic Waste Journey!</span></h1>
    <p>Transform your empty cosmetic containers into something beautiful. Join our transparent recycling process and watch your waste become part of the circular economy.</p>
    <a href="{{ route('waste-exchange.create') }}" class="btn-primary">
        Explore the Process →
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
            <div id="map"></div>
        </div>

        <div class="nearby-locations">
            <h3>Nearby Locations</h3>
            
            @forelse($dropPoints->take(3) as $point)
            <div class="location-card" data-lat="{{ $point->latitude }}" data-lng="{{ $point->longitude }}" data-index="{{ $loop->index }}">
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

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

<script>
let map;
let markers = [];

// Drop points data dari server
const dropPoints = @json($dropPoints);

// Inisialisasi peta
function initMap() {
        // ... (kode inisialisasi map Anda) ...
        const centerJawaTimur = [-7.2575, 112.7521];
        map = L.map('map', { 
            center: centerJawaTimur, 
            zoom: 11, 
            scrollWheelZoom: true, 
            zoomControl: true 
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors', 
            maxZoom: 19,
        }).addTo(map);

        // 2. LOOP DATA DAN TAMBAH MARKER UNTUK SEMUA TITIK
        if (dropPointsData.length > 0) {
            dropPointsData.forEach((point, index) => {
                addMarker(point.latitude, point.longitude, point.nama_lokasi, point.alamat, index);
            });
            // Center map ke drop point pertama atau titik tengah semua marker
            map.setView([dropPointsData[0].latitude, dropPointsData[0].longitude], 12);
        } else {
            // Jika tidak ada data, tetap di Jawa Timur
            map.setView(centerJawaTimur, 11);
        }
    }
    
function addMarker(point, index) {
    const lat = parseFloat(point.latitude);
    const lng = parseFloat(point.longitude);
    
    if (isNaN(lat) || isNaN(lng)) {
        console.error(`Invalid coordinates for ${point.nama_lokasi}`);
        return;
    }

    // Custom icon dengan DivIcon
    const customIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div class="custom-marker">📍</div>',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });

    // Buat marker
    const marker = L.marker([lat, lng], {
        icon: customIcon,
        title: point.nama_lokasi
    }).addTo(map);

    // Popup content
    const popupContent = `
        <div class="map-popup">
            <h4>${point.nama_lokasi}</h4>
            <p>${point.alamat}</p>
            <span class="badge">📍 Drop Point Available</span>
        </div>
    `;

    const marker = L.marker([lat, lng], {
            icon: customIcon,
            index: index 
        }).addTo(map);

    marker.bindPopup(popupContent, {
        maxWidth: 250,
        className: 'custom-popup'
    });

    // Event: klik marker
    marker.on('click', function() {
        map.setView([lat, lng], 14, {
            animate: true,
            duration: 0.5
        });
    });

    markers.push(marker);
}

// Event: klik location card
document.querySelectorAll('.location-card').forEach((card) => {
    card.addEventListener('click', function() {
        const index = parseInt(this.dataset.index);
        if (markers[index]) {
            const marker = markers[index];
            
            // Buka popup dan zoom ke marker
            marker.openPopup();
            map.setView(marker.getLatLng(), 15, {
                animate: true,
                duration: 0.6
            });
            
            // Bounce effect
            const markerElement = marker.getElement();
            if (markerElement) {
                markerElement.style.animation = 'bounce 0.6s ease';
                setTimeout(() => {
                    markerElement.style.animation = '';
                }, 600);
            }
        }
    });
    
    // Tambah cursor pointer
    card.style.cursor = 'pointer';
});

// Bounce animation
const style = document.createElement('style');
style.textContent = `
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
`;
document.head.appendChild(style);

// Initialize map setelah DOM ready
document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection