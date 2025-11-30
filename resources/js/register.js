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
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const usernameError = document.getElementById('usernameError');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');

        function validatePassword(pass) {
            
            const minLength = pass.length >= 8;
            const hasNumber = /\d/.test(pass);
            const hasUpperCase = /[A-Z]/.test(pass);
            return minLength && hasNumber && hasUpperCase;
        }

        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Reset errors
            [username, email, password, confirmPassword].forEach(field => field.classList.remove('error'));
            [usernameError, emailError, passwordError, confirmPasswordError].forEach(error => error.classList.remove('show'));

            // Validate username
            if (!username.value.trim()) {
                username.classList.add('error');
                usernameError.textContent = 'Username tidak boleh kosong';
                usernameError.classList.add('show');
                isValid = false;
            } else if (username.value.trim().length < 3) {
                username.classList.add('error');
                usernameError.textContent = 'Username minimal 3 karakter';
                usernameError.classList.add('show');
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
                const firstError = document.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Real-time validation
        username.addEventListener('input', function() {
            if (this.value.trim() && this.value.trim().length >= 3) {
                this.classList.remove('error');
                usernameError.classList.remove('show');
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