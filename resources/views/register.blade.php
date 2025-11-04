<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Glow - Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/register.css', 'resources/js/register.js'])
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="{{ asset('assets/re-glow.svg') }}" alt="Re-Glow Logo" class="logo-img">
            <h1 class="logo-text">Re-Glow</h1>
        </div>

        <div class="right-panel">
            <form class="register-form" id="registerForm" method="POST" action="{{ route('register.process') }}">
                @csrf
                <h2>Start Your Journey now!</h2>

                @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="error-message show" style="display: block; margin-bottom: 15px;">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
                @endif

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="username" name="username" placeholder="Username" value="{{ old('username') }}">
                    </div>
                    <div class="error-message" id="usernameError">Username tidak boleh kosong</div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">✉</span>
                        <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                    </div>
                    <div class="error-message" id="emailError">Email harus menggunakan @gmail.com</div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" placeholder="Password">
                        <span class="eye-icon" id="togglePassword">👁</span>
                    </div>
                    <div class="password-requirements">Minimal 8 huruf, 1 angka, 1 huruf besar.</div>
                    <div class="error-message" id="passwordError">Password tidak memenuhi persyaratan</div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Confirm your password">
                        <span class="eye-icon" id="toggleConfirmPassword">👁</span>
                    </div>
                    <div class="error-message" id="confirmPasswordError">Password tidak cocok</div>
                </div>

                <button type="submit" class="btn-submit">Start my journey</button>

                <div class="login-link">
                    Already have an account? <a href="{{ route('login') }}">[Login]</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>