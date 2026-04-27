{{--
    navbar.blade.php
    Included in layouts/app.blade.php via @include('partials.navbar')
    Depends on: style.css, Bootstrap Icons CDN
--}}

{{-- ============================================================
     NAVBAR STYLES — scoped to nav components only
     (global tokens live in style.css)
     ============================================================ --}}
<style>
    /* --- Government identity strip --- */
    .nav-strip {
        background: var(--ink);
        height: 38px;
        display: flex;
        align-items: center;
        font-family: var(--font-body);
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.04em;
        color: #8A9AB5;
    }

    .nav-strip .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-strip__badge {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #8A9AB5;
    }

    .nav-strip__badge i {
        font-size: 12px;
        color: var(--navy-mid);
    }

    .nav-strip__links {
        display: flex;
        gap: 22px;
    }

    .nav-strip__links a {
        color: #5A6A80;
        font-size: 10.5px;
        font-weight: 500;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        transition: color var(--ease);
        text-decoration: none;
    }

    .nav-strip__links a:hover {
        color: #8A9AB5;
    }

    /* --- Main navigation bar --- */
    .nav-main {
        background: var(--white);
        border-bottom: 4px solid var(--navy);
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 0 var(--border);
    }

    .nav-main .container {
        display: flex;
        align-items: center;
        height: 68px;
    }

    /* Logo */
    .nav-logo {
        display: flex;
        align-items: center;
        gap: 11px;
        flex-shrink: 0;
        margin-right: 44px;
        text-decoration: none;
    }

    .nav-logo img {
        height: 40px;
        width: auto;
    }

    .nav-logo__fallback {
        font-family: var(--font-display);
        font-weight: 900;
        font-size: 18px;
        letter-spacing: 0.06em;
        color: var(--navy);
        text-transform: uppercase;
        display: none;
    }

    /* Nav links list */
    .nav-links {
        display: flex;
        align-items: stretch;
        height: 100%;
        flex: 1;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-links > li {
        position: relative;
        display: flex;
        align-items: center;
    }

    .nav-links > li > a {
        display: flex;
        align-items: center;
        gap: 4px;
        height: 100%;
        padding: 0 16px;
        font-family: var(--font-body);
        font-size: 13.5px;
        font-weight: 600;
        letter-spacing: 0.01em;
        color: var(--ink);
        border-bottom: 4px solid transparent;
        margin-bottom: -4px;
        text-decoration: none;
        transition: color var(--ease), border-color var(--ease);
    }

    .nav-links > li > a i {
        font-size: 10px;
        opacity: 0.5;
    }

    .nav-links > li > a:hover,
    .nav-links > li > a.active {
        color: var(--navy);
        border-bottom-color: var(--navy);
    }

    /* Dropdown */
    .nav-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        background: var(--white);
        border: 1px solid var(--border);
        border-top: 3px solid var(--navy);
        min-width: 230px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.10);
        opacity: 0;
        pointer-events: none;
        transform: translateY(-4px);
        transition: opacity 0.14s ease, transform 0.14s ease;
        z-index: 200;
        list-style: none;
        margin: 0;
        padding: 4px 0;
    }

    .nav-links > li:hover .nav-dropdown,
    .nav-links > li:focus-within .nav-dropdown {
        opacity: 1;
        pointer-events: all;
        transform: translateY(0);
    }

    .nav-dropdown a {
        display: block;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 500;
        color: var(--ink);
        border-left: 3px solid transparent;
        text-decoration: none;
        transition: background var(--ease), border-color var(--ease), color var(--ease);
    }

    .nav-dropdown a:hover {
        background: var(--navy-tint);
        border-left-color: var(--navy);
        color: var(--navy);
    }

    .nav-dropdown__divider {
        height: 1px;
        background: var(--border);
        margin: 4px 0;
    }

    /* Right side: search + CTA */
    .nav-right {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-left: auto;
    }

    .nav-search {
        display: flex;
        height: 36px;
        border: 1px solid var(--border);
    }

    .nav-search input {
        border: none;
        outline: none;
        padding: 0 12px;
        font-family: var(--font-body);
        font-size: 13px;
        color: var(--ink);
        width: 150px;
        background: var(--mist);
    }

    .nav-search input::placeholder {
        color: var(--mid);
    }

    .nav-search button {
        background: var(--ink);
        color: var(--white);
        width: 36px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        transition: background var(--ease);
    }

    .nav-search button:hover {
        background: var(--navy);
    }

    /* Responsive */
    @media (max-width: 640px) {
        .nav-search { display: none; }
        .nav-links > li > a { padding: 0 10px; font-size: 12.5px; }
    }
</style>

{{-- ============================================================
     GOVERNMENT IDENTITY STRIP
     ============================================================ --}}
<div class="nav-strip" role="banner">
    <div class="container">
        <div class="nav-strip__badge">
            <i class="bi bi-shield-fill-check" aria-hidden="true"></i>
            Situs Resmi Pemerintah Provinsi DKI Jakarta
        </div>
        <div class="nav-strip__links">
            <a href="#">Bahasa Indonesia</a>
            <a href="{{ url('publickey') }}">Enkripsi</a>
            <a href="#">Aksesibilitas</a>
        </div>
    </div>
</div>

{{-- ============================================================
     MAIN NAVIGATION
     ============================================================ --}}
<nav class="nav-main" aria-label="Navigasi utama">
    <div class="container">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="nav-logo" aria-label="Beranda JakartaProv-CSIRT">
            <img src="{{ asset('jakarta-csirt-logo.png') }}"
                 alt="Jakarta CSIRT Logo"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span class="nav-logo__fallback">CSIRT</span>
        </a>

        {{-- Nav links --}}
        <ul class="nav-links">
            <li>
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    Beranda
                </a>
            </li>
            <li>
                <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                    Profil
                </a>
            </li>
            <li>
                <a href="#" aria-haspopup="true" aria-expanded="false">
                    Publikasi <i class="bi bi-chevron-down" aria-hidden="true"></i>
                </a>
                <ul class="nav-dropdown" aria-label="Submenu Publikasi">
                    <li><a href="{{ route('warnings.index') }}">Peringatan Keamanan</a></li>
                    <li><a href="{{ route('news.index') }}">Berita Siber</a></li>
                    <li><a href="{{ route('infographics.index') }}">Infografis Keamanan</a></li>
                    <li><a href="{{ route('laws.index') }}">Peraturan & Kebijakan</a></li>
                    <li><a href="{{ route('guides.index') }}">Panduan Teknis</a></li>
                    <li><div class="nav-dropdown__divider"></div></li>
                    <li><a href="{{ url('statistics') }}">Statistik Honeypot</a></li>
                    <li><a href="{{ url('rfc2350') }}">RFC 2350</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('events.index') }}"
                   class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                    Event
                </a>
            </li>
            <li>
                <a href="{{ route('contact.create') }}"
                   class="{{ request()->routeIs('contact.*') ? 'active' : '' }}">
                    Hubungi Kami
                </a>
            </li>
        </ul>

        {{-- Search + CTA --}}
        <div class="nav-right">
            <form class="nav-search" role="search" action="{{ url('/search') }}" method="GET">
                <input type="search" name="q" placeholder="Cari..." aria-label="Cari konten situs">
                <button type="submit" aria-label="Kirim pencarian">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>
            </form>
            <a href="{{ route('incidents.create.step1') }}" class="btn-navy">
                Lapor Insiden <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
            </a>
        </div>

    </div>
</nav>