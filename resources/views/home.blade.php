
@extends('layouts.app')

@section('content')

<!-- BANNER -->
<section class="py-5 text-center container">
    <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-bold">Lindungi Data Indonesia</h1>
            <p class="lead text-body-secondary">Jakarta CSIRT siap membantu melindungi infrastruktur kritis dan data digital Anda dari ancaman siber 24/7.</p>
            <p>
                <a href="{{ route('incidents.create.step1') }}" class="btn btn-primary my-2 fw-bold">Lapor Insiden Sekarang</a>
                <a href="{{ url('contact') }}" class="btn btn-secondary my-2 fw-medium">Hubungi Kami</a>
            </p>
        </div>
    </div>
</section>

<!-- NEWS TITLE -->
<div class="container">
    <div class="col-lg-8 text-center text-lg-start">
        <h1 class="display-5 fw-bold lh-1 text-body-emphasis mb-3">Berita Terkini</h1>
        <p class="fs-5">Informasi terkini seputar keamanan siber di lingkungan Pemprov DKI Jakarta</p>
    </div>
</div>
<!-- NEWS SELECTION -->
<div class="album py-1 bg-body-tertiary">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            
            @forelse($recentNews as $article)
            <div class="col">
                <div class="card shadow-sm h-100">
                    @if($article->image_url)
                        <img src="{{ $article->thumbnail }}" class="card-img-top object-fit-cover" height="225" alt="{{ $article->title }}">
                    @else
                        <svg aria-label="Placeholder: Thumbnail" class="bd-placeholder-img card-img-top" height="225" preserveAspectRatio="xMidYMid slice" role="img" width="100%" xmlns="http://www.w3.org/2000/svg">
                            <rect width="100%" height="100%" fill="#55595c"></rect>
                            <text x="50%" y="50%" fill="#eceeef" dy=".3em">No Image</text>
                        </svg>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <p class="card-text fw-bold">{{ $article->title }}</p>
                        <p class="card-text">{{ Str::limit($article->description, 100) }}</p>
                        
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <a href="{{ route('news.show', $article->id) }}" class="btn btn-sm btn-outline-secondary fw-medium">Lihat Selengkapnya</a>
                            
                            <small class="text-body-secondary">
                                {{ $article->date->diffForHumans() }}
                            </small>
                        </div>
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
    </div>
</div>

<!-- SERVICES TITLE -->
<div class="container">
    <div class="col-lg-8 text-center text-lg-start">
        <h1 class="display-5 fw-bold lh-1 text-body-emphasis mb-3">Publikasi Lainnya</h1>
        <p class="fs-5">Publikasi/informasi seputar keamanan siber di lingkungan Pemprov DKI Jakarta</p>
    </div>
</div>
<!-- SERVICES SELECTION -->
<div class="album py-1 bg-body-tertiary">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
            <!-- Card 1 -->
            <div class="col">
                <div class="card shadow-sm h-100">
                    <svg aria-label="Placeholder: Thumbnail" class="bd-placeholder-img card-img-top" height="225" preserveAspectRatio="xMidYMid slice" role="img" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                        <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                    </svg>
                    <div class="card-body d-flex flex-column">
                        <p class="card-text fw-bold fs-5 text-center">Peringatan Keamanan</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <a href="{{ url('warnings') }}" class="btn btn-sm btn-outline-secondary fw-medium w-100">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col">
                <div class="card shadow-sm h-100">
                    <svg aria-label="Placeholder: Thumbnail" class="bd-placeholder-img card-img-top" height="225" preserveAspectRatio="xMidYMid slice" role="img" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                        <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                    </svg>
                    <div class="card-body d-flex flex-column">
                        <p class="card-text fw-bold fs-5 text-center">Infografis Keamanan Informasi</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <a href="{{ url('infographics') }}" class="btn btn-sm btn-outline-secondary fw-medium w-100">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col">
                <div class="card shadow-sm h-100">
                    <svg aria-label="Placeholder: Thumbnail" class="bd-placeholder-img card-img-top" height="225" preserveAspectRatio="xMidYMid slice" role="img" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                        <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                    </svg>
                    <div class="card-body d-flex flex-column">
                        <p class="card-text fw-bold fs-5 text-center">Peraturan dan Kebijakan</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <a href="{{ url('laws') }}" class="btn btn-sm btn-outline-secondary fw-medium w-100">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="col">
                <div class="card shadow-sm h-100">
                    <svg aria-label="Placeholder: Thumbnail" class="bd-placeholder-img card-img-top" height="225" preserveAspectRatio="xMidYMid slice" role="img" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                        <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                    </svg>
                    <div class="card-body d-flex flex-column">
                        <p class="card-text fw-bold fs-5 text-center">Panduan</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <a href="{{ url('guides') }}" class="btn btn-sm btn-outline-secondary fw-medium w-100">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LAST CTA -->
<section class="py-5 py-md-6" style="background:#101823;">
    <div class="container text-center">
        <h2 class="display-5 fw-bold text-white mb-4">Memiliki Insiden Keamanan Siber?</h2>
        <p class="fs-6 mb-5 mx-auto" style="color: #fed7aa; max-width: 35rem;">
            Tim Jakarta CSIRT siap membantu Anda 24/7. Laporkan insiden keamanan siber Anda sekarang dan dapatkan respons cepat dari para ahli kami.
        </p>
        <a href="{{ route('incidents.create.step1') }}" class="btn btn-light fs-6 fw-bold px-5 py-3" style="color: #ea580c;">Lapor Insiden Sekarang</a>
    </div>
</section>

@endsection
