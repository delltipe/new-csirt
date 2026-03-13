@extends('layouts.app')

@section('content')
<section class="py-5 text-center container">
    <div class="row py-lg-4">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h1 class="fw-bold display-5">Berita Keamanan Siber</h1>
            <p class="lead text-body-secondary">Informasi dan edukasi terbaru untuk menjaga keamanan digital Anda di lingkungan Pemprov DKI Jakarta.</p>
        </div>
    </div>
</section>

<div class="album py-5 bg-body-tertiary">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @forelse($news as $article)
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        @if($article->thumbnail)
                            <img src="{{ $article->thumbnail }}" class="card-img-top" alt="{{ $article->title }}" style="height: 225px; object-fit: cover;">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center text-white" style="height: 225px;">
                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <p class="card-text fw-bold mb-2">{{ $article->title }}</p>
                            <p class="card-text text-secondary small mb-3">
                                {{ Str::limit($article->description, 110) }}
                            </p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <a href="{{ route('news.show', $article->id) }}" class="btn btn-sm btn-outline-secondary fw-medium">Lihat Selengkapnya</a>
                                <small class="text-body-secondary">
                                    {{ $article->date ? $article->date->diffForHumans() : '' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">Belum ada berita yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {!! $news->links('pagination::bootstrap-5') !!}
        </div>
    </div>
</div>
@endsection