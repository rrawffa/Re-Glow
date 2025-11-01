
<!DOCTYPE html>
<html lang="id">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Glow - Login</title>
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
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
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

        .logo-img {
            width: 300px;
            height: 300px;
            margin-bottom: 20px;
            filter: drop-shadow(0 10px 30px rgba(249, 182, 199, 0.3));
            object-fit: contain;
        }

        .logo-text {
            font-size: 4rem;
            font-weight: 700;
            color: #F9B6C7;
            letter-spacing: -2px;
            text-shadow: 0 5px 20px rgba(249, 182, 199, 0.3);
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

        .login-form {
            width: 100%;
            max-width: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            font-size: 2.3rem;
            margin-bottom: 35px;
            text-align: center;
            line-height: 1.2;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 8px;
            min-height: 70px;
            width: 100%;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.2rem;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 16px 20px 16px 55px;
            border: 2px solid transparent;
            border-radius: 50px;
            font-size: 1rem;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #20413A;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        input.error {
            border-color: #ff4444;
        }

        .error-message {
            color: #ff4444;
            font-size: 0.85rem;
            margin-top: 5px;
            margin-left: 20px;
            display: none;
            font-weight: 500;
        }

        .error-message.show {
            display: block;
        }

        .eye-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 1.2rem;
            user-select: none;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: #20413A;
            font-weight: 500;
            margin-bottom: 20px;
            width: 100%;
            justify-content: flex-start;
            font-size: 0.9rem;
            margin-top: -5px;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 8px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 16px 24px;
            background: #20413A;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: 'Bricolage Grotesque', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 12px;
        }

        .btn-submit:hover {
            background: #163026;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(32, 65, 58, 0.3);
        }

        .signup-link {
            text-align: center;
            color: white;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .signup-link a {
            color: #20413A;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-panel {
                min-height: 40vh;
                padding: 40px 30px;
            }

            .right-panel {
                min-height: 60vh;
                padding: 40px 30px;
            }

            .logo-text {
                font-size: 3rem;
            }

            .logo-img {
                width: 200px;
                height: 200px;
            }

            h2 {
                font-size: 1.9rem;
                margin-bottom: 25px;
            }

            .login-form {
                max-width: 320px;
            }

            .btn-submit {
                padding: 14px 20px;
                font-size: 1rem;
            }

            .remember-me {
                font-size: 0.85rem;
            }
        }
    </style>
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

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁' : '👁️‍🗨️';
        });

        // Form validation
        const form = document.getElementById('loginForm');
        const email = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Reset errors
            email.classList.remove('error');
            password.classList.remove('error');
            emailError.classList.remove('show');
            passwordError.classList.remove('show');

            // Validate email
            if (!email.value.trim()) {
                email.classList.add('error');
                emailError.textContent = 'Email tidak boleh kosong';
                emailError.classList.add('show');
                isValid = false;
            } else if (!email.value.includes('@gmail.com')) {
                email.classList.add('error');
                emailError.textContent = 'Email harus menggunakan @gmail.com';
                emailError.classList.add('show');
                isValid = false;
            }

            // Validate password
            if (!password.value.trim()) {
                password.classList.add('error');
                passwordError.textContent = 'Password tidak boleh kosong';
                passwordError.classList.add('show');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Real-time validation
        email.addEventListener('input', function() {
            if (this.value.trim() && this.value.includes('@gmail.com')) {
                this.classList.remove('error');
                emailError.classList.remove('show');
            }
        });

        password.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('error');
                passwordError.classList.remove('show');
            }
        });
    </script>
</body>
</html>