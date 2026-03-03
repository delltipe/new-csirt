
<footer class="bg-dark border-top mt-5">
    <div class="container py-5">
        <div class="row mb-4">
            <!-- About Section -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                        <img src="{{ asset('jakarta-csirt-logo.png') }}" alt="CSIRT Logo" class="img-fluid" style="max-height: 2.5rem;">
                    </a>
                </div>
                <p class="text-secondary small mb-0 about-description">
                    Tim Tanggap Insiden Siber (Computer Security Incident Response Team) Pemerintah Provinsi DKI Jakarta yang selanjutnya disebut dengan JakartaProv-CSIRT merupakan CSIRT Pemprov DKI Jakarta.
                </p>
            </div>
            <!-- Quick Links -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="text-white fw-bold mb-3">Menu Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/') }}" class="text-secondary text-decoration-none d-block mb-2">Home</a></li>
                    <li><a href="{{ url('profile') }}" class="text-secondary text-decoration-none d-block mb-2">Profil</a></li>
                    <li><a href="{{ url('events') }}" class="text-secondary text-decoration-none d-block mb-2">Event</a></li>
                </ul>
            </div>
            <!-- Publications -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="text-white fw-bold mb-3">Publikasi</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ url('warnings') }}" class="text-secondary text-decoration-none d-block mb-2">Peringatan Keamanan</a></li>
                    <li><a href="{{ url('infographics') }}" class="text-secondary text-decoration-none d-block mb-2">Infografis Keamanan Informasi</a></li>
                    <li><a href="{{ url('laws') }}" class="text-secondary text-decoration-none d-block mb-2">Peraturan dan Kebijakan</a></li>
                    <li><a href="{{ url('guides') }}" class="text-secondary text-decoration-none d-block">Panduan</a></li>
                </ul>
            </div>
            <!-- Contact & Stats -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <h5 class="text-white fw-bold mb-3">Hubungi Kami</h5>
                <p class="text-secondary small mb-3">
                    <strong>Lokasi:</strong> Bidang Siber, Sandi dan Aplikasi Diskominfotik Provinsi DKI Jakarta Balaikota Blok H Lantai 13, JL Merdeka Selatan 8-9, Jakarta Pusat 10110<br>
                    <strong>Telepon:</strong> (+62) 813-8887-0152<br>
                    <strong>Email:</strong> csirt@jakarta.go.id
                </p>
                <h6 class="text-white fw-bold mb-2">Pengunjung</h6>
                <p class="text-secondary small mb-0">
                    <span class="fw-bold text-warning">8</span> Online<br>
                    <span class="fw-bold text-warning">36</span> Hari Ini<br>
                    <span class="fw-bold text-warning">89,886</span> Pengunjung<br>
                    <span class="fw-bold text-warning">157</span> Insiden Ditangani<br>
                </p>
            </div>
        </div>
        <!-- Bottom Bar -->
        <div class="border-top pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="text-secondary small mb-3 mb-md-0">
                © 2026 Jakarta CSIRT - Pemerintah Provinsi DKI Jakarta. All rights reserved.
            </p>
            <div class="d-flex gap-3">
                <a href="#" class="text-secondary text-decoration-none small">Kebijakan Privasi</a>
                <a href="#" class="text-secondary text-decoration-none small">Syarat Penggunaan</a>
                <a href="#" class="text-secondary text-decoration-none small">Status Website</a>
            </div>
        </div>
    </div>
</footer>
