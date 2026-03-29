@extends('layouts.app')

@section('content')

<section class="position-relative overflow-hidden p-3 p-md-5 text-white" style="background: linear-gradient(rgba(16, 24, 35, 0.8), rgba(16, 24, 35, 0.8)), url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; border-bottom: 8px solid #dc2626;">
    <div class="container py-5">
        <div class="col-md-7 p-lg-5 my-5">
            <h1 class="display-3 fw-bolder text-uppercase lh-1 mb-3">Lindungi Data <span class="text-danger">Indonesia.</span></h1>
            <p class="lead fw-normal mb-4">JakartaProv-CSIRT menjaga infrastruktur digital dan data kritis Provinsi DKI Jakarta dari ancaman siber 24 jam sehari, 7 hari seminggu.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="{{ route('incidents.create.step1') }}" class="btn btn-nyc btn-lg px-5 py-3">Lapor Insiden Sekarang</a>
                <a href="{{ url('profile') }}" class="btn btn-outline-light btn-lg px-5 py-3 rounded-0 border-2 fw-bold">Tentang Kami</a>
            </div>
        </div>
    </div>
</section>

<div class="bg-white py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom border-4 border-dark pb-2">
            <div>
                <h2 class="fw-black text-uppercase m-0" style="letter-spacing: -1px;">Berita Terkini</h2>
                <p class="text-secondary mb-0">Informasi terbaru dari JakartaProv-CSIRT</p>
            </div>
            <a href="{{ route('news.index') }}" class="fw-bold text-dark text-decoration-none text-uppercase small">Lihat Semua Berita <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            @forelse($recentNews as $article)
            <div class="col-md-4">
                <div class="card h-100 border-0">
                    <div class="position-relative overflow-hidden" style="height: 250px;">
                        <img src="{{ $article->thumbnail ?? 'https://via.placeholder.com/800x400' }}" class="img-fluid object-fit-cover w-100 h-100 transition-transform" alt="{{ $article->title }}">
                    </div>
                    <div class="card-body px-0 py-3">
                        <small class="text-danger fw-bold text-uppercase">{{ $article->date->format('M d, Y') }}</small>
                        <h4 class="fw-bold mt-1">
                            <a href="{{ route('news.show', $article->id) }}" class="text-dark text-decoration-none hover-red">{{ $article->title }}</a>
                        </h4>
                        <p class="text-secondary small">{{ Str::limit($article->description, 120) }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada berita terkini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<div class="py-5" style="background-color: #f1f5f9;">
    <div class="container">
        <h2 class="text-center fw-black text-uppercase mb-5">Publikasi & Layanan</h2>
        
        <div class="row g-0 border border-2 border-dark">
            <a href="{{ url('warnings') }}" class="col-md-3 p-4 text-center border-end border-bottom border-dark text-decoration-none bg-white hover-navy transition">
                <i class="bi bi-shield-exclamation fs-1 text-danger mb-3 d-block"></i>
                <h5 class="fw-bold text-dark">Peringatan Keamanan</h5>
            </a>
            <a href="{{ url('infographics') }}" class="col-md-3 p-4 text-center border-end border-bottom border-dark text-decoration-none bg-white hover-navy transition">
                <i class="bi bi-file-earmark-bar-graph fs-1 text-primary mb-3 d-block"></i>
                <h5 class="fw-bold text-dark">Infografis Keamanan</h5>
            </a>
            <a href="{{ url('laws') }}" class="col-md-3 p-4 text-center border-end border-bottom border-dark text-decoration-none bg-white hover-navy transition">
                <i class="bi bi-journal-bookmark fs-1 text-dark mb-3 d-block"></i>
                <h5 class="fw-bold text-dark">Peraturan & Kebijakan</h5>
            </a>
            <a href="{{ url('guides') }}" class="col-md-3 p-4 text-center border-bottom border-dark text-decoration-none bg-white hover-navy transition">
                <i class="bi bi-book fs-1 text-success mb-3 d-block"></i>
                <h5 class="fw-bold text-dark">Panduan Teknis</h5>
            </a>
        </div>
    </div>
</div>

<section class="py-6" style="background:#101823; border-top: 10px solid #dc2626;">
    <div class="container text-center py-5">
        <h2 class="display-4 fw-black text-white mb-3 text-uppercase">Butuh Respon Cepat?</h2>
        <p class="fs-5 mb-5 mx-auto text-light opacity-75" style="max-width: 40rem;">
            Tim Jakarta CSIRT tersedia 24/7 untuk menangani insiden keamanan siber di lingkungan Pemerintah Provinsi DKI Jakarta.
        </p>
        <a href="{{ route('incidents.create.step1') }}" class="btn btn-light btn-lg fs-5 fw-black px-5 py-3 rounded-0 shadow-lg">LAPOR INSIDEN SEKARANG</a>
    </div>
</section>

@endsection