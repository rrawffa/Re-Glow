// Toggle password visibility
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('password');

togglePassword.addEventListener('click', function() {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.textContent = type === 'password' ? '👁' : '👁️‍🗨️';
});

// Auto-fill password dari localStorage jika "Ingat saya" dicentang
document.addEventListener('DOMContentLoaded', function() {
    const rememberMe = document.getElementById('rememberMe');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    // Cek apakah ada data yang disimpan di localStorage
    const savedEmail = localStorage.getItem('remembered_email');
    const savedPassword = localStorage.getItem('remembered_password');
    
    if (savedEmail && savedPassword && rememberMe.checked) {
        emailInput.value = savedEmail;
        passwordInput.value = savedPassword;
    }
});

// Form validation dan handle "Ingat saya"
const form = document.getElementById('loginForm');
const email = document.getElementById('email');
const emailError = document.getElementById('emailError');
const passwordError = document.getElementById('passwordError');
const rememberMe = document.getElementById('rememberMe');

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

    // Handle "Ingat saya" - simpan ke localStorage jika dicentang
    if (isValid && rememberMe.checked) {
        localStorage.setItem('remembered_email', email.value);
        localStorage.setItem('remembered_password', password.value);
    } else {
        // Hapus data dari localStorage jika tidak dicentang atau validasi gagal
        localStorage.removeItem('remembered_email');
        localStorage.removeItem('remembered_password');
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

// Handle perubahan checkbox "Ingat saya"
rememberMe.addEventListener('change', function() {
    if (!this.checked) {
        // Jika checkbox dicabang, hapus data dari localStorage
        localStorage.removeItem('remembered_email');
        localStorage.removeItem('remembered_password');
    }
});