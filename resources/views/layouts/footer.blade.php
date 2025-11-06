@vite(['resources/css/layouts/footer.css'])
<footer>
  <div class="footer-container">
    <div class="footer-column brand">
      <h3>Re-Glow</h3>
      <p>Making beauty sustainable, one product at a time.</p>
    </div>

    <div class="footer-column">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="#about">About Us</a></li>
        <li><a href="#privacy">Privacy Policy</a></li>
        <li><a href="#terms">Terms & Conditions</a></li>
      </ul>
    </div>

    <div class="footer-column">
      <h4>Contact</h4>
      <ul>
        <li><a href="mailto:hello@reglow.com">hello@reglow.com</a></li>
        <li>123 Green Street, Eco City,<br>EC 12345</li>
      </ul>
    </div>

    <div class="footer-column">
      <h4>Follow Us</h4>
      <div class="social-icons">
        <a href="#">📷</a>
        <a href="#">🐦</a>
        <a href="#">📘</a>
        <a href="#">💼</a>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© 2024 Re-Glow. All rights reserved.</p>
  </div>
</footer>

<style>
    footer {
        background: var(--green-dark);
        color: white;
        padding: 3rem 5% 1.5rem;
    }

    .footer-content {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 3rem;
        margin-bottom: 2rem;
    }

    .footer-brand h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .footer-brand p {
        color: rgba(255,255,255,0.8);
        line-height: 1.6;
    }

    .footer-section h4 {
        margin-bottom: 1rem;
        font-size: 1.125rem;
    }

    .footer-links {
        list-style: none;
    }

    .footer-links li {
        margin-bottom: 0.75rem;
    }

    .footer-links a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: var(--pink-base);
    }

    .social-icons {
        display: flex;
        gap: 1rem;
        font-size: 1.5rem;
    }

    .social-icons a {
        color: white;
        transition: color 0.3s;
    }

    .social-icons a:hover {
        color: var(--pink-base);
    }

    .footer-bottom {
        text-align: center;
        padding-top: 2rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.6);
    }

    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }
</style>