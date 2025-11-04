<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Glow - Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/welcome.css', 'resources/js/welcome.js'])
    <style>
        
    </style>
</head>
<body>
    <div class="container" id="mainContainer">
        <!-- Slide 1: Splash Screen -->
        <div class="slide splash-screen" id="splashScreen">
            <div class="logo-container">
                <img src="{{ asset('assets/re-glow.svg') }}" alt="Re-Glow Logo" class="logo-img">
                <h1 class="logo-text">Re-Glow</h1>
            </div>
            <div class="tap-hint">Tap anywhere to continue</div>
        </div>

        <!-- Slide 2: Welcome Screen -->
        <div class="slide welcome-screen">
            <div class="split-layout">
                <div class="left-panel">
                    <img src="{{ asset('assets/re-glow.svg') }}" alt="Re-Glow Logo" class="logo-img">
                    <h1 class="logo-text">Re-Glow</h1>
                </div>

                <div class="right-panel">
                    <div class="right-content">
                        <h2>Give your cosmetic waste a new life and track your positive impact on the planet.</h2>
                        <p>Already have an account?</p>
                        <button class="btn" onclick="goToLogin()">
                            <span>Login</span>
                            <span class="btn-arrow">›</span>
                        </button>
                        <div class="divider">or</div>
                        <button class="btn" onclick="goToRegister()">
                            <span>Sign Up</span>
                            <span class="btn-arrow">›</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    window.APP_ROUTES = {
        login: "{{ route('login') }}",
        register: "{{ route('register') }}"
    };
</script>
</html>