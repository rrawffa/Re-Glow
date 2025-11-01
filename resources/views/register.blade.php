<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-Glow - Sign Up</title>
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
            overflow-x: hidden; /* Allow vertical scrolling */
        }

        h1, h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
        }

        .container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 40px;
            min-width: 0;
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
            min-width: 0;
            overflow-y: auto; /* Allow scrolling when needed */
            max-height: 100vh; /* Ensure it doesn't exceed viewport */
        }

        .register-form {
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

        input[type="text"],
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

        input:focus {
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

        .password-requirements {
            color: #20413A;
            font-size: 0.85rem;
            margin-top: 5px;
            margin-left: 20px;
            font-weight: 500;
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
            margin-top: 10px;
            margin-bottom: 12px;
        }

        .btn-submit:hover {
            background: #163026;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(32, 65, 58, 0.3);
        }

        .login-link {
            text-align: center;
            color: white;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .login-link a {
            color: #20413A;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            body {
                overflow-x: hidden; /* Allow vertical scrolling on mobile */
            }

            .container {
                flex-direction: column;
                min-height: 100vh;
            }

            .left-panel {
                min-height: 40vh;
                padding: 40px 30px;
                width: 100vw;
                flex: none;
            }

            .right-panel {
                min-height: 60vh;
                padding: 40px 30px;
                width: 100vw;
                flex: none;
                overflow-y: auto; /* Allow scrolling on mobile */
                max-height: 60vh; /* Limit height on mobile */
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

            .register-form {
                max-width: 320px;
            }

            .btn-submit {
                padding: 14px 20px;
                font-size: 1rem;
            }
        }

        /* Allow scrolling on all devices */
        html, body {
            overflow-x: hidden;
            height: auto; /* Change from 100% to auto */
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
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
            <form class="register-form" id="registerForm" method="POST" action="{{ route('register.process') }}">
                @csrf
                <h2>Start Your Journey now!</h2>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name') }}">
                    </div>
                    <div class="error-message" id="nameError">Nama tidak boleh kosong</div>
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

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁' : '👁️‍🗨️';
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁' : '👁️‍🗨️';
        });

        // Form validation
        const form = document.getElementById('registerForm');
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const nameError = document.getElementById('nameError');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');

        function validatePassword(pass) {
            // Minimal 8 huruf, 1 angka, 1 huruf besar
            const minLength = pass.length >= 8;
            const hasNumber = /\d/.test(pass);
            const hasUpperCase = /[A-Z]/.test(pass);
            return minLength && hasNumber && hasUpperCase;
        }

        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Reset errors
            [name, email, password, confirmPassword].forEach(field => field.classList.remove('error'));
            [nameError, emailError, passwordError, confirmPasswordError].forEach(error => error.classList.remove('show'));

            // Validate name
            if (!name.value.trim()) {
                name.classList.add('error');
                nameError.textContent = 'Nama tidak boleh kosong';
                nameError.classList.add('show');
                isValid = false;
            }

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
            } else if (!validatePassword(password.value)) {
                password.classList.add('error');
                passwordError.textContent = 'Minimal 8 huruf, 1 angka, 1 huruf besar';
                passwordError.classList.add('show');
                isValid = false;
            }

            // Validate confirm password
            if (!confirmPassword.value.trim()) {
                confirmPassword.classList.add('error');
                confirmPasswordError.textContent = 'Konfirmasi password tidak boleh kosong';
                confirmPasswordError.classList.add('show');
                isValid = false;
            } else if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('error');
                confirmPasswordError.textContent = 'Password tidak cocok';
                confirmPasswordError.classList.add('show');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                // Scroll to first error if there are validation errors
                const firstError = document.querySelector('.error.show');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Real-time validation
        name.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('error');
                nameError.classList.remove('show');
            }
        });

        email.addEventListener('input', function() {
            if (this.value.trim() && this.value.includes('@gmail.com')) {
                this.classList.remove('error');
                emailError.classList.remove('show');
            }
        });

        password.addEventListener('input', function() {
            if (this.value.trim() && validatePassword(this.value)) {
                this.classList.remove('error');
                passwordError.classList.remove('show');
            }
        });

        confirmPassword.addEventListener('input', function() {
            if (this.value.trim() && this.value === password.value) {
                this.classList.remove('error');
                confirmPasswordError.classList.remove('show');
            }
        });

        // Remove all scroll prevention code since we want to allow scrolling
    </script>
</body>
</html>