<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Glow - Education</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
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

        /* Navbar */
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

        .logo img {
            width: 40px;
            height: 40px;
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

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--pink-light) 0%, #FFF 100%);
            padding: 5rem 5% 4rem;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--green-dark);
        }

        .hero .highlight {
            color: var(--pink-base);
        }

        .hero p {
            font-size: 1.125rem;
            color: var(--text-gray);
            max-width: 800px;
            margin: 1.5rem auto;
            line-height: 1.8;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--green-dark);
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 1.5rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(32, 65, 58, 0.3);
        }

        /* Education Catalog */
        .catalog-section {
            padding: 4rem 5%;
            background: white;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 0.75rem;
        }

        .section-header p {
            color: var(--text-gray);
            font-size: 1.125rem;
        }

        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .catalog-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .catalog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .catalog-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--green-dark);
            margin-bottom: 1rem;
        }

        .catalog-card p {
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .card-meta {
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-bottom: 1.5rem;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--pink-base);
            color: var(--green-dark);
            padding: 0.65rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .read-more:hover {
            background: #F7A3B8;
        }

        .card-reactions {
            display: flex;
            gap: 0.5rem;
            font-size: 1.125rem;
        }

        .load-more {
            display: block;
            margin: 3rem auto 0;
            background: var(--green-light);
            color: var(--green-dark);
            padding: 1rem 3rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .load-more:hover {
            background: #A8B399;
        }

        /* Footer */
        footer {
            background: var(--green-dark);
            color: white;
            padding: 3rem 5% 1.5rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer-brand h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .footer-brand p {
            color: rgba(255,255,255,0.8);
            line-height: 1.6;
        }

        .footer-section h4 {
            margin-bottom: 1rem;
            font-size: 1.125rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--pink-base);
        }

        .social-icons {
            display: flex;
            gap: 1rem;
            font-size: 1.5rem;
        }

        .social-icons a {
            color: white;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: var(--pink-base);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .catalog-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                <circle cx="20" cy="20" r="18" fill="#F9B6C7"/>
                <path d="M20 10 L25 15 L20 20 L15 15 Z" fill="#20413A"/>
            </svg>
            Re-Glow
        </div>
        <ul class="nav-menu">
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="#exchange">Exchange Waste</a></li>
            <li><a href="#points">Points</a></li>
            <li><a href="#vouchers">Vouchers</a></li>
            <li><a href="#community">Community</a></li>
            <li><a href="#education" class="active">Education</a></li>
            <li><a href="#faq">FAQ</a></li>
        </ul>
        <div class="nav-icons">
            <button class="icon-btn">🔔</button>
            <img src="https://i.pravatar.cc/150?img=47" alt="Profile" class="profile-pic">
        </div>
    </nav>

   <!-- Hero Section -->
    <section class="hero">
        <h1>
            Explore the World of Cosmetic<br>
            <span class="highlight">Recycling: Sustainable Beauty Starts Here!</span>
        </h1>
        <p>
            Discover the impact of cosmetic waste on our environment and learn practical 
            ways to make your beauty routine more sustainable. Join thousands of users 
            making a difference, one product at a time.
        </p>
        <a href="#catalog" class="cta-btn">
            Start Learning
            <span>↓</span>
        </a>
    </section>

    <!-- Education Catalog -->
    <section class="catalog-section" id="catalog">
        <div class="section-header">
            <h2>Education Catalog</h2>
            <p>Expand your knowledge with our curated collection of sustainability content</p>
        </div>

        @if($konten->count() > 0)
        <div class="catalog-grid">
            @foreach($konten as $item)
            <div class="catalog-card">
                {{-- Tampilkan gambar cover jika ada --}}
                @if($item->gambar_cover)
                <img src="{{ asset('storage/' . $item->gambar_cover) }}" 
                     alt="{{ $item->judul }}" 
                     style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;">
                @endif

                <h3>{{ $item->judul }}</h3>
                <p>{{ Str::limit($item->ringkasan, 150) }}</p>
                
                <div class="card-meta">
                    <span>📅 {{ \Carbon\Carbon::parse($item->tanggal_upload)->format('F d, Y') }}</span>
                    @if($item->waktu_baca)
                    <span>⏱️ {{ $item->waktu_baca }} min read</span>
                    @endif
                    @if($item->penulis)
                    <span>✍️ {{ $item->penulis }}</span>
                    @endif
                </div>
                
                <div class="card-footer">
                    <a href="{{ url('/education/' . $item->id_konten) }}" class="read-more">
                        Read More →
                    </a>
                    <div class="card-reactions">
                        <span title="Reactions">❤️ {{ $item->jumlah_reaksi ?? 0 }}</span>
                        <span title="Thumbs Up">👍</span>
                        <span title="Fire">🔥</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 3rem; background: var(--pink-light); border-radius: 12px;">
            <h3 style="color: var(--green-dark); margin-bottom: 1rem;">No Content Available</h3>
            <p style="color: var(--text-gray);">Educational content will be available soon.</p>
        </div>
        @endif

        <button class="load-more">
            Load More Articles
        </button>
    </section>
    
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-brand">
                <h3>Re-Glow</h3>
                <p>Making beauty sustainable, one product at a time.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#privacy">Privacy Policy</a></li>
                    <li><a href="#terms">Terms & Conditions</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <ul class="footer-links">
                    <li>hello@reglow.com</li>
                    <li>123 Green Street, Eco City, EC 12345</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#">📷</a>
                    <a href="#">🐦</a>
                    <a href="#">📘</a>
                    <a href="#">💼</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2024 Re-Glow. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>