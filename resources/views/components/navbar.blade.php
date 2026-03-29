<!-- <div class="navbar-bg">
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                    <img src="{{ asset('jakarta-csirt-logo.png') }}" alt="CSIRT Logo" class="img-fluid" style="max-height: 2.5rem;">
                </a>
                <form class="ms-3 mb-0" role="search">
                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
                </form>
            </div>
            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="{{ route('home') }}" class="nav-link px-2 link-secondary">Home</a></li>
                <li>
                    <a href="{{ route('profile') }}" 
                    class="nav-link px-2 {{ request()->routeIs('profile') ? 'link-secondary fw-bold' : '' }}">
                    Profile
                    </a>
                </li>
                <li><a href="{{ route('events.index') }}" class="nav-link px-2">Event</a></li>
                <li>
                    <a href="#" class="nav-link px-2 d-block dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Publikasi</a>
                    <ul class="dropdown-menu text-small">
                        <li><a class="dropdown-item" href="{{ route('warnings.index') }}">Peringatan Keamanan</a></li>
                        <li><a class="dropdown-item" href="{{ route('infographics.index') }}">Infografis Keamanan Informasi</a></li>
                        <li><a class="dropdown-item" href="{{ route('laws.index') }}">Peraturan Kebijakan</a></li>
                        <li><a class="dropdown-item" href="{{ route('news.index') }}">Berita Siber (Cyber Blitz)</a></li>
                        <li><a class="dropdown-item" href="{{ url('statistics') }}">Statistik (Honeypot)</a></li>
                        <li><a class="dropdown-item" href="{{ route('guides.index') }}">Panduan Teknis</a></li>
                        <li><a class="dropdown-item" href="{{ url('rfc2350') }}">RFC2350</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ url('publickey') }}">Public Key email csirt@jakarta.go.id</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('contact.create') }}" class="nav-link px-2">Hubungi Kami</a></li>
            </ul>
            <div class="col-md-3 text-end">
                <a href="{{ route('incidents.create.step1') }}" class="btn btn-primary fw-medium">Lapor Insiden Siber</a>
            </div>
        </header>
    </div>
</div> -->
<nav>
    <div class="navbar-top">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold text-uppercase" style="letter-spacing: 1px;">
                    <i class="bi bi-bank me-1"></i> Situs Resmi Pemerintah Provinsi DKI Jakarta
                </span>
            </div>
            <div class="d-none d-md-block">
                <a href="#" class="me-3 small fw-bold">BAHASA INDONESIA</a>
                <a href="{{ url('publickey') }}" class="small fw-bold">ENKRIPSI</a>
            </div>
        </div>
    </div>

    <div class="navbar-main shadow-sm">
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-between">
                
                <a href="/" class="d-flex align-items-center col-md-auto mb-2 mb-md-0 text-decoration-none">
                    <img src="{{ asset('jakarta-csirt-logo.png') }}" alt="CSIRT Logo" style="max-height: 3.2rem;">
                </a>

                <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">Profile</a></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Publikasi</a>
                        <ul class="dropdown-menu shadow-lg border-2 border-dark">
                            <li><a class="dropdown-item fw-bold" href="{{ route('warnings.index') }}">Peringatan Keamanan</a></li>
                            <li><a class="dropdown-item fw-bold" href="{{ route('news.index') }}">Berita Siber</a></li>
                            <li><a class="dropdown-item fw-bold" href="{{ route('laws.index') }}">Peraturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item fw-bold" href="{{ url('statistics') }}">Statistik</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('events.index') }}" class="nav-link">Event</a></li>
                </ul>

                <div class="col-md-auto d-flex align-items-center justify-content-end">
                    <form class="me-2 d-none d-lg-block" role="search">
                        <div class="input-group">
                            <input type="search" class="form-control nyc-search py-2" placeholder="Cari..." style="width: 150px;">
                            <button class="btn btn-dark rounded-0 border-0" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                    <a href="{{ route('incidents.create.step1') }}" class="btn btn-nyc">
                        Lapor Insiden <i class="bi bi-megaphone-fill ms-1"></i>
                    </a>
                </div>

            </header>
        </div>
    </div>
</nav>