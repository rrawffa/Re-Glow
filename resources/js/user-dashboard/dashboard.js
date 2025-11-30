(function () {
        // Short helper
        const $ = sel => document.querySelector(sel);

        // Animate welcome card entrance
        document.addEventListener('DOMContentLoaded', function () {
            const card = $('#welcomeCard');
            if (card) {
                // slight stagger for nicer feel
                setTimeout(() => card.classList.add('visible'), 80);
            }

            // Show toast greeting once
            const username = "{{ addslashes(Session::get('username') ?? 'Pengguna') }}";
            showToast('Selamat datang, ' + username + '!');
            // Start local time updater
            updateLocalTime();
            setInterval(updateLocalTime, 60 * 1000);
        });

        function showToast(message, ms = 3500) {
            const t = $('#dashboardToast');
            if (!t) return;
            t.textContent = message;
            t.classList.add('show');
            clearTimeout(t._hideTimer);
            t._hideTimer = setTimeout(() => t.classList.remove('show'), ms);
        }

        function updateLocalTime() {
            const el = $('#localTime');
            if (!el) return;
            const now = new Date();
            const opts = { hour: '2-digit', minute: '2-digit' };
            el.textContent = '⏰ ' + now.toLocaleTimeString([], opts);
        }

        // Smooth navigation to education when on same page (fallback to link behavior)
        const btnEdu = $('#btnEducation');
        if (btnEdu) {
            btnEdu.addEventListener('click', function (e) {
                // if page contains an anchor target, try smooth scroll, else let default navigate
                const href = this.getAttribute('href') || '';
                if (href.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                // else default navigation to route
            });
        }

        // Profile button small visual feedback
        const btnProfile = $('#btnProfile');
        if (btnProfile) {
            btnProfile.addEventListener('click', function () {
                // brief feedback
                this.style.transform = 'translateY(-2px)';
                setTimeout(() => this.style.transform = '', 180);
            });
        }
    })();