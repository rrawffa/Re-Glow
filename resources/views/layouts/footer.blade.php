@vite('resources/css/layouts/footer.css')
<footer>
    <div class="footer-content">
        <!-- Brand Section -->
        <div class="footer-brand">
            <div class="brand-logo">
                <img src="{{ asset('assets/re-glow.svg') }}" alt="Re-Glow Logo" class="footer-logo">
                <h3>Re-Glow</h3>
            </div>
            <p>
                Making beauty sustainable, one product at a time. 
                Join us in our mission to reduce cosmetic waste and 
                promote environmental consciousness.
            </p>
        </div>

        <!-- Quick Links -->
        <div class="footer-section">
            <h4>Quick Links</h4>
            <ul class="footer-links">
                <li>
                    <a href="{{ route('education.index') }}">
                        <i class="bi bi-book"></i>
                        Education
                    </a>
                </li>
                <li>
                    <a href="#about">
                        <i class="bi bi-info-circle"></i>
                        About Us
                    </a>
                </li>
                <li>
                    <a href="#privacy">
                        <i class="bi bi-shield-check"></i>
                        Privacy Policy
                    </a>
                </li>
                <li>
                    <a href="#terms">
                        <i class="bi bi-file-text"></i>
                        Terms & Conditions
                    </a>
                </li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div class="footer-section">
            <h4>Contact</h4>
            <ul class="contact-info">
                <li>
                    <i class="bi bi-envelope"></i>
                    <div>
                        <a href="mailto:hello@reglow.com" style="color: rgba(255,255,255,0.8); text-decoration: none;">
                            hello@reglow.com
                        </a>
                    </div>
                </li>
                <li>
                    <i class="bi bi-geo-alt"></i>
                    <div>
                        Fakultas Ilmu Komputer<br>
                        Universitas Brawijaya<br>
                        Malang, Indonesia
                    </div>
                </li>
                <li>
                    <i class="bi bi-telephone"></i>
                    <div>+62 812 3456 7890</div>
                </li>
            </ul>
        </div>

        <!-- Social Media -->
        <div class="footer-section">
            <h4>Follow Us</h4>
            <div class="social-icons">
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" title="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" title="Twitter">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" title="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" title="LinkedIn">
                    <i class="bi bi-linkedin"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>
            <i class="bi bi-c-circle"></i> 
            2024 Re-Glow. All rights reserved. 
            Made with <i class="bi bi-heart-fill" style="color: var(--pink-base);"></i> for a sustainable future.
        </p>
    </div>
</footer>
