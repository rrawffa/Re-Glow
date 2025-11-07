<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Re-Glow')</title>
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
    </style>
    @yield('styles')
</head>
<body>
    @if (request()->is('logistik/*'))
        @include('layouts.logistik-navbar')
    @else
        @include('layouts.navbar')
    @endif

    <main>
        @yield('content')
    </main>
    
    @include('layouts.footer')
    
    @yield('scripts')
</body>
</html>