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
  :root {
    --footer-bg: #234D45;
    --footer-text: #ffffff;
    --footer-muted: rgba(255,255,255,0.8);
    --footer-line: rgba(255,255,255,0.1);
  }

  footer {
    background-color: var(--footer-bg);
    color: var(--footer-text);
    font-family: "Poppins", sans-serif;
    padding: 3rem 6%;
  }

  .footer-container {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 3rem;
    margin-bottom: 2rem;
  }

  .footer-column h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
  }

  .footer-column h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
  }

  .footer-column p {
    color: var(--footer-muted);
    line-height: 1.6;
  }

  .footer-column ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .footer-column li {
    margin-bottom: 0.6rem;
    line-height: 1.6;
  }

  .footer-column a {
    color: var(--footer-muted);
    text-decoration: none;
    transition: color 0.3s;
  }

  .footer-column a:hover {
    color: #ffffff;
  }

  .social-icons {
    display: flex;
    gap: 1rem;
    font-size: 1.5rem;
  }

  .social-icons a {
    color: #ffffff;
    transition: opacity 0.3s;
  }

  .social-icons a:hover {
    opacity: 0.7;
  }

  .footer-bottom {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid var(--footer-line);
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
  }

  /* Responsif */
  @media (max-width: 900px) {
    .footer-container {
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
    }
  }

  @media (max-width: 600px) {
    .footer-container {
      grid-template-columns: 1fr;
      text-align: center;
    }
    .social-icons {
      justify-content: center;
    }
  }
</style>
