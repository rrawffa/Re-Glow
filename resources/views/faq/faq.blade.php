<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Re-Glow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJd/r0X+S3E8+n8A0+o7Q/h9l/y0e3F+h/G68oA/9FkS5fE6q+k4G7G8sP+w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@8..30,200..800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --reglow-pink: #d85f8c;
            --reglow-green-dark: #20413A;
            
            --pink-base: var(--reglow-pink);
            --green-dark: var(--reglow-green-dark); 
        }
        .faq-page-content h2 {
            color: var(--reglow-green-dark);
            font-family: 'Bricolage Grotesque', sans-serif; 
            font-weight: 700;
        }
        .accordion-button { 
            font-weight: 500; 
            padding: 1rem 1.25rem;
            font-family: 'DM Sans', sans-serif; 
            color: var(--reglow-green-dark); 
        }
        .accordion-button:not(.collapsed) { 
            color: var(--reglow-pink); 
            background-color: #fce4ec; 
            box-shadow: none; 
            font-weight: 700; 
        }
        .nav-link.active { color: var(--reglow-pink) !important; font-weight: 500; border-bottom: 2px solid var(--reglow-pink); }
        .faq-page-content { padding: 40px 0; }
        .faq-search-bar { max-width: 600px; margin: 30px auto; }
        .faq-categories .btn { margin: 5px; border-radius: 20px; font-size: 0.85rem; }
        .faq-categories .btn-outline-secondary.active {
            background-color: var(--reglow-pink);
            border-color: var(--reglow-pink);
            color: white;
        }
        .accordion-button { font-weight: 500; padding: 1rem 1.25rem; }
        .accordion-button:not(.collapsed) { color: var(--reglow-pink); background-color: #fce4ec; box-shadow: none; }
        .accordion-item { margin-bottom: 10px; border-radius: 8px; border: 1px solid #ddd; }
        .highlight-dot { color: var(--reglow-pink); margin-left: 5px; font-size: 1.5em; vertical-align: middle; }
        .support-contact { background-color: #f8f9fa; padding: 40px; border-radius: 8px; text-align: center; margin-top: 40px; }
        .btn-support-email { background-color: var(--reglow-pink); border-color: var(--reglow-pink); color: white; margin: 5px; }
        .btn-support-chat { background-color: white; border: 1px solid var(--reglow-pink); color: var(--reglow-pink); margin: 5px; }

        footer {
            background: var(--green-dark); 
            color: white;
            padding: 3rem 5% 1.5rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto; 
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer-brand h3 { font-size: 1.5rem; margin-bottom: 1rem; }
        .footer-brand p { color: rgba(255,255,255,0.8); line-height: 1.6; }
        .footer-section h4 { margin-bottom: 1rem; font-size: 1.125rem; }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 0.75rem; }
        .footer-links li:not(:has(a)) { color: rgba(255,255,255,0.8); }

        .footer-links a { color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s; }
        .footer-links a:hover { color: var(--pink-base); }

        .social-icons { display: flex; gap: 1rem; font-size: 1.2rem; }
        .social-icons a { color: white; transition: color 0.3s; }
        .social-icons a:hover { color: var(--pink-base); }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
        }

        @media (max-width: 768px) {
            .footer-content { grid-template-columns: 1fr; gap: 2rem; }
        }
    </style>
</head>
<body>

    @include('layouts.navbar') 
    <main class="faq-page-content">
        <div class="container">
            <section class="text-center mb-5">
                <h2 class="fw-bold">Frequently Asked Questions</h2>
                <p class="text-muted">Find answers to common questions about Re-Glow products, sustainability practices, and our commitment to eco-friendly beauty.</p>
            </section>
            
            <section class="faq-search-bar">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="search" class="form-control border-start-0" placeholder="Search FAQ...">
                </div>
            </section>

            <section class="faq-categories text-center mb-5">
                <button type="button" class="btn btn-outline-secondary active">All</button>
                <button type="button" class="btn btn-outline-secondary">Products</button>
                <button type="button" class="btn btn-outline-secondary">Shipping</button>
                <button type="button" class="btn btn-outline-secondary">Sustainability</button>
                <button type="button" class="btn btn-outline-secondary">Returns</button>
                <button type="button" class="btn btn-outline-secondary">Account</button>
            </section>

            <section class="faq-list">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false">What makes Re-Glow products sustainable?</button></h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Jawaban tentang keberlanjutan produk akan ditempatkan di sini.</div></div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true">
                                Are Re-Glow products suitable for sensitive skin? 
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">**Jawaban Pertanyaan yang Dilingkari:** Ya, sebagian besar produk Re-Glow diformulasikan untuk kulit sensitif, bebas dari sulfat, paraben, dan pewangi buatan.</div></div>
                    </div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false">How long does shipping take?</button></h2><div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Waktu pengiriman standar adalah 3-7 hari kerja tergantung lokasi.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false">Can I return products if I'm not satisfied?</button></h2><div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Kami menawarkan garansi kepuasan 30 hari.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false">How do I track my order?</button></h2><div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Anda dapat melacak pesanan Anda melalui tautan yang dikirimkan ke email Anda.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false">Do you offer bulk or wholesale pricing?</button></h2><div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Ya, kami menawarkan harga grosir.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false">What is your packaging made from?</button></h2><div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Sebagian besar kemasan kami terbuat dari plastik daur ulang (PCR).</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false">How do I create an account?</button></h2><div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Klik ikon avatar di sudut kanan atas dan pilih 'Daftar' untuk membuat akun baru.</div></div></div>
                </div>
            </section>

            <section class="support-contact shadow-sm">
                <h3 class="fw-bold">Still have questions?</h3>
                <p>Our customer support team is here to help you with any additional questions.</p>
                <div class="contact-buttons mt-4">
                    <a href="mailto:support@reglow.com" class="btn btn-lg btn-support-email">
                        <i class="fas fa-envelope me-2"></i> Email Support
                    </a>
                    <a href="#" class="btn btn-lg btn-support-chat">
                        <i class="fas fa-comment-dots me-2"></i> Live Chat
                    </a>
                </div>
            </section>
        </div>
    </main>

    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html> 