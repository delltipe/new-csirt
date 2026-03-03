<div class="navbar-bg">
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
                <li><a href="{{ url('profile') }}" class="nav-link px-2">Profile</a></li>
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
                <a href="{{ route('incidents.create') }}" class="btn btn-primary fw-medium">Lapor Insiden Siber</a>
            </div>
        </header>
    </div>
</div>
