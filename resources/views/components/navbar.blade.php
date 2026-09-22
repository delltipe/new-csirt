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
        min-height: 38px;
        display: flex;
        align-items: center;
        font-family: var(--font-body);
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.04em;
        color: var(--muted-on-dark);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-strip .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding-top: 4px;
        padding-bottom: 4px;
    }

    .nav-strip__badge {
        display: flex;
        align-items: center;
        gap: 7px;
        color: var(--muted-on-dark);
    }

    .nav-strip__badge i {
        font-size: 12px;
        color: var(--navy-mid);
    }

    .nav-strip__links {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .nav-strip__links a {
        color: var(--muted-on-dark);
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        transition: color var(--ease);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .nav-strip__links a:hover {
        color: var(--white);
    }

    .nav-strip__links a.track-link {
        color: #93C5FD;
    }
    .nav-strip__links a.track-link:hover {
        color: var(--white);
        text-decoration: underline;
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
        min-height: 68px;
        position: relative;
    }

    /* Logo */
    .nav-logo {
        display: flex;
        align-items: center;
        gap: 11px;
        flex-shrink: 0;
        margin-right: 20px;
        text-decoration: none;
    }

    .nav-logo img {
        height: 40px;
        width: auto;
    }

    .nav-logo__fallback {
        font-family: var(--font-display);
        font-weight: 800;
        font-size: 18px;
        letter-spacing: 0.06em;
        color: var(--navy);
        text-transform: uppercase;
        display: none;
    }

    /* Partner logos cluster (brand row) */
    .nav-partners {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-left: 16px;
        margin-right: 20px;
        border-left: 1px solid var(--border);
        flex-shrink: 0;
    }

    .nav-partners a {
        display: flex;
        align-items: center;
        line-height: 0;
        text-decoration: none;
        opacity: 0.9;
        transition: opacity var(--ease);
    }

    .nav-partners a:hover {
        opacity: 1;
    }

    .nav-partner {
        display: flex;
        align-items: center;
        line-height: 0;
    }

    .nav-partner img.partner-dark {
        display: none;
    }

    .nav-partners img {
        height: 34px;
        width: auto;
        max-width: 80px;
        object-fit: contain;
    }

    /* Mobile toggle button (hamburger) */
    .nav-toggle {
        display: none;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: transparent;
        border: 1px solid var(--border);
        color: var(--navy);
        font-size: 24px;
        cursor: pointer;
        margin-left: auto;
        padding: 0;
        transition: background var(--ease), color var(--ease);
    }
    .nav-toggle:hover {
        background: var(--navy-tint);
    }
    .nav-toggle:focus-visible {
        outline: 2px solid var(--navy);
        outline-offset: 2px;
    }

    /* Nav Collapse Container (desktop vs mobile) */
    .nav-collapse {
        display: flex;
        align-items: center;
        flex: 1;
        justify-content: flex-end;
        height: 100%;
    }

    /* Nav links list */
    .nav-links {
        display: flex;
        align-items: stretch;
        height: 100%;
        margin: 0 0 0 auto;
        list-style: none;
        padding: 0;
    }

    .nav-links > li {
        position: relative;
        display: flex;
        align-items: center;
    }

    /* Hover bridge: covers the nav's 4px border-bottom gap so the
       dropdown stays open while moving the cursor down from the parent link */
    .nav-links > li::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        height: 6px;
    }

    .nav-links > li > a {
        display: flex;
        align-items: center;
        gap: 4px;
        height: 100%;
        padding: 0 12px;
        font-family: var(--font-body);
        font-size: 13.5px;
        font-weight: 600;
        letter-spacing: 0.01em;
        color: var(--ink);
        border-bottom: 4px solid transparent;
        margin-bottom: -4px;
        text-decoration: none;
        white-space: nowrap;
        transition: color var(--ease), border-color var(--ease);
    }

    .nav-links > li > a i {
        font-size: 11px;
        opacity: 0.7;
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
        margin-left: 12px;
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
        width: 120px;
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

    /* Logout button (authenticated users) */
    .nav-logout {
        display: flex;
        align-items: center;
    }
    .nav-logout button {
        background: transparent;
        color: var(--mid);
        border: 1px solid var(--border);
        width: 36px;
        height: 36px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: color var(--ease), border-color var(--ease);
    }
    .nav-logout button:hover {
        color: var(--alert);
        border-color: var(--alert);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .nav-partners { display: none; }
    }

    @media (max-width: 992px) {
        .nav-toggle {
            display: flex;
        }

        .nav-collapse {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--white);
            border-bottom: 4px solid var(--navy);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
            padding: 20px 24px 28px;
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
            z-index: 150;
        }

        .nav-collapse.is-open {
            display: flex;
        }

        .nav-links {
            flex-direction: column;
            align-items: stretch;
            height: auto;
            margin: 0;
            width: 100%;
        }

        .nav-links > li {
            width: 100%;
            border-bottom: 1px solid var(--border);
            flex-direction: column;
            align-items: stretch;
        }

        .nav-links > li > a {
            padding: 13px 0;
            height: auto;
            font-size: 14.5px;
            border-bottom: none;
            margin-bottom: 0;
            justify-content: space-between;
        }

        .nav-dropdown {
            position: static;
            transform: none;
            opacity: 1;
            pointer-events: all;
            box-shadow: none;
            border: 1px solid var(--border);
            border-left: 3px solid var(--navy);
            background: var(--mist);
            margin: 0 0 10px 0;
            padding: 4px 0;
            display: none;
        }

        .nav-links > li.dropdown-open .nav-dropdown,
        .nav-links > li:hover .nav-dropdown,
        .nav-links > li:focus-within .nav-dropdown {
            display: block;
        }

        .nav-right {
            margin-left: 0;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            width: 100%;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav-search {
            width: 100%;
        }
        .nav-search input {
            flex: 1;
            width: 100%;
        }
    }

    @media (max-width: 640px) {
        .nav-strip .container {
            justify-content: center;
            text-align: center;
        }
        .nav-strip__links {
            width: 100%;
            justify-content: center;
        }
        .nav-right .btn-navy {
            flex: 1;
            text-align: center;
            justify-content: center;
        }
    }
</style>

{{-- ============================================================
     GOVERNMENT IDENTITY STRIP (BSSA DKI & Public Ticket Tracking)
     ============================================================ --}}
<div class="nav-strip">
    <div class="container">
        <div class="nav-strip__badge">
            <i class="bi bi-shield-check" aria-hidden="true"></i>
            <span>Portal Resmi JakartaProv-CSIRT · Bidang Siber dan Sandi (BSSA) Diskominfotik DKI</span>
        </div>
        <div class="nav-strip__links">
            <a href="{{ route('ticket.track') }}" class="track-link">
                <i class="bi bi-search" aria-hidden="true"></i> Lacak Status Tiket
            </a>
            <a href="https://diskominfotik.jakarta.go.id" target="_blank" rel="noopener">
                Diskominfotik DKI <i class="bi bi-box-arrow-up-right" style="font-size:10px;" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</div>

{{-- ============================================================
     MAIN NAVIGATION
     ============================================================ --}}
<nav class="nav-main" aria-label="Navigasi utama">
    <div class="container">

        {{-- Logo — optimized 224x80 --}}
        <a href="{{ route('home') }}" class="nav-logo" aria-label="Beranda JakartaProv-CSIRT">
            <img src="{{ asset('jakarta-csirt-logo.png') }}"
                 alt="Jakarta CSIRT Logo"
                 width="134" height="48"
                 loading="eager" decoding="async" fetchpriority="high"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span class="nav-logo__fallback">CSIRT</span>
        </a>

        {{-- Partner logos with outbound links --}}
        <div class="nav-partners" role="group" aria-label="Logo instansi mitra">
            <a href="https://www.jakarta.go.id/" target="_blank" rel="noopener" title="Jaya Raya" aria-label="Logo Jaya Raya">
                <img src="{{ asset('jaya_raya.png') }}" alt="Jaya Raya" width="68" height="72" loading="eager" decoding="async">
            </a>
            <a href="https://diskominfotik.jakarta.go.id/" target="_blank" rel="noopener" title="Diskominfo DKI Jakarta" aria-label="Logo Diskominfo DKI Jakarta">
                <img src="{{ asset('logo_diskominfo.png') }}" alt="Diskominfo DKI Jakarta" width="68" height="77" loading="eager" decoding="async">
            </a>
            <a href="https://jakarta500.id/" target="_blank" rel="noopener" title="5 Abad Jakarta" aria-label="Logo 5 Abad Jakarta">
                <span class="nav-partner">
                    <img id="partner-5abad" src="{{ asset('logo_5abad.png') }}" data-light="{{ asset('logo_5abad.png') }}" data-dark="{{ asset('logo_5abad_white.svg') }}" alt="5 Abad Jakarta" width="80" height="55" loading="eager" decoding="async" fetchpriority="low">
                </span>
            </a>
            <span class="nav-partner">
                <img id="partner-hutri81" src="{{ asset('HUTRI81.png') }}" data-light="{{ asset('HUTRI81.png') }}" data-dark="{{ asset('hutri81_white.png') }}" alt="HUT RI ke-81" width="55" height="55" loading="eager" decoding="async" fetchpriority="low">
            </span>
        </div>

        {{-- Mobile Hamburger Toggle Button --}}
        <button class="nav-toggle" id="nav-toggle" type="button"
                aria-expanded="false" aria-label="Buka menu navigasi" aria-controls="nav-collapse">
            <i class="bi bi-list" id="nav-toggle-icon" aria-hidden="true"></i>
        </button>

        {{-- Collapsible Nav Container --}}
        <div class="nav-collapse" id="nav-collapse">
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
                <li id="nav-dropdown-parent">
                    <a href="#" aria-haspopup="true" aria-expanded="false" id="btn-nav-dropdown">
                        Publikasi <i class="bi bi-chevron-down" aria-hidden="true"></i>
                    </a>
                    <ul class="nav-dropdown" aria-label="Submenu Publikasi">
                        <li><a href="{{ route('warnings.index') }}">Peringatan Keamanan</a></li>
                        <li><a href="{{ route('news.index') }}">Berita Siber</a></li>
                        <li><a href="{{ route('infographics.index') }}">Infografis Keamanan</a></li>
                        <li><a href="{{ route('laws.index') }}">Peraturan & Kebijakan</a></li>
                        <li><a href="{{ route('guides.index') }}">Panduan Teknis</a></li>
                        <li><div class="nav-dropdown__divider"></div></li>
                        <li><a href="{{ route('ticket.track') }}"><strong><i class="bi bi-search" aria-hidden="true"></i> Lacak Status Tiket</strong></a></li>
                        <li><a href="{{ url('statistics') }}">Statistik Honeypot</a></li>
                        <li><a href="{{ url('rfc2350') }}">RFC 2350</a></li>
                        <li><a href="{{ route('publickey') }}">Public Key</a></li>
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

                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="btn-navy">
                            Admin <i class="bi bi-speedometer2" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="{{ route('bug-hunter.dashboard') }}" class="btn-navy">
                            Lapor Insiden <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
                        </a>
                    @endif
                    <form class="nav-logout" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" aria-label="Keluar" title="Keluar">
                            <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-navy">
                        Masuk <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                    </a>
                @endauth
            </div>
        </div>

    </div>
</nav>

<script>
(function() {
    const navToggle = document.getElementById('nav-toggle');
    const navCollapse = document.getElementById('nav-collapse');
    const toggleIcon = document.getElementById('nav-toggle-icon');
    const dropdownParent = document.getElementById('nav-dropdown-parent');
    const btnNavDropdown = document.getElementById('btn-nav-dropdown');

    if (navToggle && navCollapse) {
        navToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = navCollapse.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (toggleIcon) {
                toggleIcon.className = isOpen ? 'bi bi-x-lg' : 'bi bi-list';
            }
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (navCollapse.classList.contains('is-open') && !navCollapse.contains(e.target) && !navToggle.contains(e.target)) {
                navCollapse.classList.remove('is-open');
                navToggle.setAttribute('aria-expanded', 'false');
                if (toggleIcon) toggleIcon.className = 'bi bi-list';
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navCollapse.classList.contains('is-open')) {
                navCollapse.classList.remove('is-open');
                navToggle.setAttribute('aria-expanded', 'false');
                if (toggleIcon) toggleIcon.className = 'bi bi-list';
                navToggle.focus();
            }
        });
    }

    // Mobile dropdown toggle on tap
    if (btnNavDropdown && dropdownParent) {
        btnNavDropdown.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                dropdownParent.classList.toggle('dropdown-open');
                const isExpanded = dropdownParent.classList.contains('dropdown-open');
                btnNavDropdown.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
            }
        });
    }
})();
</script>