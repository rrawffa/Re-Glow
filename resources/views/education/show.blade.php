<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $education->judul }} - Re-Glow</title>
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
            background: #f8f9fa;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Bricolage Grotesque', sans-serif;
        }

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

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .article-header {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .article-title {
            font-size: 2.5rem;
            color: var(--green-dark);
            margin-bottom: 1rem;
        }

        .article-meta {
            display: flex;
            gap: 1.5rem;
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .article-content {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            line-height: 1.8;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--green-dark);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 2rem;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #16332d;
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
        <a href="{{ route('education.index') }}" class="back-btn">
            ← Back to Education
        </a>
    </nav>

    <div class="container">
        <div class="article-header">
            <h1 class="article-title">{{ $education->judul }}</h1>
            <div class="article-meta">
                @if($education->penulis)
                <span>✍️ {{ $education->penulis }}</span>
                @endif
                <span>📅 {{ \Carbon\Carbon::parse($education->tanggal_upload)->format('F d, Y') }}</span>
                @if($education->waktu_baca)
                <span>⏱️ {{ $education->waktu_baca }} min read</span>
                @endif
            </div>
            @if($education->ringkasan)
            <p style="color: var(--text-gray); font-size: 1.1rem; font-style: italic;">
                {{ $education->ringkasan }}
            </p>
            @endif
        </div>

        <div class="article-content">
            @if($education->konten)
                {!! $education->konten !!}
            @else
                <p>Content will be available soon.</p>
            @endif

            <a href="{{ route('education.index') }}" class="back-btn">
                ← Back to Education Catalog
            </a>
        </div>
    </div>
</body>
</html>