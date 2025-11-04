
<!DOCTYPE html>
<html lang="id">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Glow - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/login.css', 'resources/js/login.js'])
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="{{ asset('assets/re-glow.svg') }}" alt="Re-Glow Logo" class="logo-img">
            <h1 class="logo-text">Re-Glow</h1>
        </div>

        <div class="right-panel">
            <form class="login-form" id="loginForm" method="POST" action="{{ route('login.process') }}">
                @csrf
                <h2>Welcome Back!!</h2>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">✉</span>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               placeholder="Email" 
                               value="{{ old('email', isset($_COOKIE['remember_email']) ? $_COOKIE['remember_email'] : '') }}">
                    </div>
                    <div class="error-message" id="emailError">Email harus menggunakan @gmail.com</div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Password"
                               value="{{ old('password', isset($_COOKIE['remember_password']) ? $_COOKIE['remember_password'] : '') }}">
                        <span class="eye-icon" id="togglePassword">👁</span>
                    </div>
                    <div class="error-message" id="passwordError">Password tidak boleh kosong</div>
                </div>

                <div class="remember-me">
                    <input type="checkbox" 
                           id="rememberMe" 
                           name="remember" 
                           {{ isset($_COOKIE['remember_email']) ? 'checked' : '' }}>
                    <label for="rememberMe">Ingat saya</label>
                </div>

                <button type="submit" class="btn-submit">Enter</button>

                <div class="signup-link">
                    Don't have an account? <a href="{{ route('register') }}">[Sign Up]</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>