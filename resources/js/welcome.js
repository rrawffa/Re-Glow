
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
    window.location.href = window.APP_ROUTES.login;
}

function goToRegister() {
    window.location.href = window.APP_ROUTES.register;
}

window.goToLogin = goToLogin;
window.goToRegister = goToRegister;