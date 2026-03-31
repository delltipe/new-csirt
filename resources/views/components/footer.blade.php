{{--
    footer.blade.php
    Included in layouts/app.blade.php via @include('partials.footer')
    Depends on: style.css, Bootstrap Icons CDN
--}}

{{-- ============================================================
     FOOTER STYLES — scoped to footer only
     ============================================================ --}}
<style>
    .site-footer {
        background: var(--ink);
        padding: 64px 0 0;
        font-family: var(--font-body);
    }

    /* Main grid: brand | quick links | publications | contact */
    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.5fr;
        gap: 52px;
        padding-bottom: 52px;
    }

    /* Brand column */
    .footer-logo {
        height: 38px;
        width: auto;
        margin-bottom: 18px;
        filter: brightness(0) invert(1) opacity(0.7);
        display: block;
    }

    .footer-desc {
        font-size: 13px;
        color: #5A6A80;
        line-height: 1.8;
        margin-bottom: 22px;
    }

    .footer-social {
        display: flex;
        gap: 8px;
    }

    .footer-social a {
        width: 34px;
        height: 34px;
        border: 1px solid #1E2D42;
        color: #4A5A70;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        text-decoration: none;
        transition: border-color var(--ease), color var(--ease), background var(--ease);
    }

    .footer-social a:hover {
        border-color: var(--navy-mid);
        color: var(--white);
        background: var(--navy);
    }

    /* Column headings */
    .footer-heading {
        font-family: var(--font-display);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 18px;
    }

    /* Link lists */
    .footer-links {
        display: flex;
        flex-direction: column;
        gap: 9px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links a {
        font-size: 13px;
        color: #5A6A80;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: color var(--ease);
    }

    .footer-links a:hover {
        color: rgba(255, 255, 255, 0.8);
    }

    .footer-links a i {
        font-size: 9px;
    }

    /* Contact items */
    .footer-contact__item {
        display: flex;
        gap: 10px;
        margin-bottom: 13px;
        font-size: 13px;
        color: #5A6A80;
        line-height: 1.55;
    }

    .footer-contact__item i {
        font-size: 13px;
        color: #2A4A70;
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Bottom bar */
    .footer-bottom {
        border-top: 1px solid #131D2B;
        padding: 20px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .footer-copy {
        font-size: 12px;
        color: #3A4A5E;
    }

    .footer-legal {
        display: flex;
        gap: 20px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-legal a {
        font-size: 12px;
        color: #3A4A5E;
        text-decoration: none;
        transition: color var(--ease);
    }

    .footer-legal a:hover {
        color: #8A9AB5;
    }

    /* Responsive */
    @media (max-width: 960px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
            gap: 36px;
        }
    }

    @media (max-width: 640px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- ============================================================
     FOOTER MARKUP
     ============================================================ --}}
<footer class="site-footer" aria-label="Footer situs JakartaProv-CSIRT">
    <div class="container">
        <div class="footer-grid">

            {{-- Brand & About --}}
            <div>
                <img class="footer-logo"
                     src="{{ asset('jakarta-csirt-logo.png') }}"
                     alt="JakartaProv-CSIRT"
                     onerror="this.style.display='none'">
                <p class="footer-desc">
                    Tim Tanggap Insiden Siber (Computer Security Incident Response Team) Pemerintah Provinsi DKI Jakarta. Menjaga keamanan infrastruktur digital dan data kritis Jakarta dari ancaman siber.
                </p>
                <div class="footer-social">
                    <a href="#" aria-label="Twitter/X"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram" aria-hidden="true"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube" aria-hidden="true"></i></a>
                    <a href="mailto:csirt@jakarta.go.id" aria-label="Email"><i class="bi bi-envelope" aria-hidden="true"></i></a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="footer-heading">Menu Cepat</h3>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Beranda</a></li>
                    <li><a href="{{ url('profile') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Profil</a></li>
                    <li><a href="{{ url('events') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Event</a></li>
                    <li><a href="{{ route('contact.create') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Hubungi Kami</a></li>
                    <li><a href="{{ url('rfc2350') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>RFC 2350</a></li>
                    <li><a href="{{ url('publickey') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Public Key</a></li>
                </ul>
            </div>

            {{-- Publications --}}
            <div>
                <h3 class="footer-heading">Publikasi</h3>
                <ul class="footer-links">
                    <li><a href="{{ url('warnings') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Peringatan Keamanan</a></li>
                    <li><a href="{{ url('news') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Berita Siber</a></li>
                    <li><a href="{{ url('infographics') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Infografis</a></li>
                    <li><a href="{{ url('laws') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Peraturan & Kebijakan</a></li>
                    <li><a href="{{ url('guides') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Panduan Teknis</a></li>
                    <li><a href="{{ url('statistics') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i>Statistik Honeypot</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="footer-heading">Hubungi Kami</h3>
                <div class="footer-contact__item">
                    <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                    <span>Bidang Siber, Sandi dan Aplikasi Diskominfotik DKI Jakarta — Balaikota Blok H Lt. 13, Jl. Merdeka Selatan 8–9, Jakarta Pusat 10110</span>
                </div>
                <div class="footer-contact__item">
                    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                    <span>(+62) 813-8887-0152</span>
                </div>
                <div class="footer-contact__item">
                    <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                    <span>csirt@jakarta.go.id</span>
                </div>
                <div class="footer-contact__item">
                    <i class="bi bi-clock-fill" aria-hidden="true"></i>
                    <span>Respons tersedia 24 jam / 7 hari seminggu</span>
                </div>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="footer-bottom">
            <p class="footer-copy">
                &copy; {{ date('Y') }} JakartaProv-CSIRT — Pemerintah Provinsi DKI Jakarta. Seluruh hak dilindungi.
            </p>
            <nav aria-label="Tautan legal">
                <ul class="footer-legal">
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat Penggunaan</a></li>
                    <li><a href="#">Status Website</a></li>
                    <li><a href="#">Aksesibilitas</a></li>
                </ul>
            </nav>
        </div>
    </div>
</footer>