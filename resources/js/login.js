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