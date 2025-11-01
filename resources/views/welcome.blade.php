<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Glow - Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #fef5f8 0%, #fff 100%);
            overflow-x: hidden;
        }

        h1, h2, h3 {
            font-family: 'Bricolage Grotesque', sans-serif;
        }

        .container {
            min-height: 100vh;
            display: flex;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .slide {
            min-width: 100vw;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Splash Screen */
        .splash-screen {
            flex-direction: column;
            animation: fadeIn 0.5s ease-in;
            cursor: pointer;
            position: relative;
        }

        .logo-container {
            text-align: center;
            animation: scaleUp 0.8s ease-out;
        }

        .logo-img {
            width: 300px;
            height: 300px;
            margin-bottom: 20px;
            filter: drop-shadow(0 10px 30px rgba(249, 182, 199, 0.3));
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .logo-container:hover .logo-img {
            transform: scale(1.05);
        }

        .logo-text {
            font-size: 4rem;
            font-weight: 700;
            color: #F9B6C7;
            letter-spacing: -2px;
            text-shadow: 0 5px 20px rgba(249, 182, 199, 0.3);
            transition: transform 0.3s ease;
        }

        .logo-container:hover .logo-text {
            transform: translateY(-5px);
        }

        .tap-hint {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            color: #F9B6C7;
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.8;
            animation: pulse 2s infinite;
        }

        /* Welcome Screen */
        .welcome-screen {
            width: 100%;
            animation: fadeIn 0.6s ease-in;
        }

        .split-layout {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 40px;
        }

        .right-panel {
            flex: 1;
            background: #F9B6C7;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            color: white;
        }

        .right-content {
            width: 100%;
            max-width: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .right-panel h2 {
            font-size: 2.3rem;
            margin-bottom: 12px;
            text-align: center;
            line-height: 1.2;
            font-weight: 700;
        }

        .right-panel p {
            font-size: 1.1rem;
            margin-bottom: 24px;
            text-align: center;
            opacity: 0.95;
            font-weight: 500;
        }

        .btn {
            width: 100%;
            max-width: 280px;
            padding: 16px 24px;
            border: 2px solid white;
            background: transparent;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: 'Bricolage Grotesque', sans-serif;
        }

        .btn:hover {
            background: white;
            color: #F9B6C7;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-arrow {
            font-size: 1.4rem;
        }

        .divider {
            margin: 16px 0;
            color: white;
            font-weight: 500;
            font-size: 1rem;
            opacity: 0.9;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes scaleUp {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                opacity: 0.5;
                transform: translateX(-50%) scale(1);
            }
            50% {
                opacity: 1;
                transform: translateX(-50%) scale(1.05);
            }
            100% {
                opacity: 0.5;
                transform: translateX(-50%) scale(1);
            }
        }

        @media (max-width: 768px) {
            .split-layout {
                flex-direction: column;
            }

            .left-panel, .right-panel {
                min-height: 50vh;
                padding: 40px 30px;
            }

            .logo-text {
                font-size: 3rem;
            }

            .right-panel h2 {
                font-size: 1.9rem;
                margin-bottom: 10px;
                line-height: 1.15;
            }

            .right-panel p {
                font-size: 1rem;
                margin-bottom: 20px;
            }

            .logo-img {
                width: 200px;
                height: 200px;
            }

            .tap-hint {
                bottom: 20px;
                font-size: 0.9rem;
            }

            .right-content {
                max-width: 320px;
                gap: 6px;
            }

            .btn {
                max-width: 250px;
                padding: 14px 20px;
                font-size: 1rem;
            }

            .divider {
                margin: 12px 0;
            }
        }
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

    <script>
        // Function to transition to welcome screen
        function goToWelcomeScreen() {
            document.getElementById('mainContainer').style.transform = 'translateX(-100vw)';
        }

        // Add click event to splash screen
        document.getElementById('splashScreen').addEventListener('click', goToWelcomeScreen);

        // Optional: Add keyboard support (Enter key)
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                goToWelcomeScreen();
            }
        });

        function goToLogin() {
            window.location.href = "{{ route('login') }}";
        }

        function goToRegister() {
            window.location.href = "{{ route('register') }}";
        }
    </script>
</body>
</html>