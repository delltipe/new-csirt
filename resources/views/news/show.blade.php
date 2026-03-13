@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Berita
        </a>
    </nav>

    <div class="row g-5">
        <div class="col-lg-8">
            <h1 class="display-4 fw-bold mb-3">{{ $news->title }}</h1>
            
            <div class="d-flex gap-3 text-muted mb-4">
                <span><i class="bi bi-calendar-event"></i> {{ $news->date->format('d M Y') }}</span>
                @if($news->source)
                    <a href="{{ $news->source }}" target="_blank" class="text-decoration-none">Lihat Sumber</a>
                @endif
            </div>

            @if($news->thumbnail)
                <img src="{{ $news->thumbnail }}" alt="{{ $news->title }}" class="img-fluid rounded-4 mb-4 shadow-sm" style="width: 100%; max-height: 400px; object-fit: cover;">
            @endif
            
            <div class="news-content fs-5 leading-relaxed">
                {!! nl2br(e($news->description)) !!}
            </div>
        </div>

        <div class="col-lg-4">
            <div class="p-4 bg-light rounded-4 border">
                <h4 class="fw-bold mb-3">Berita Terkait</h4>
                <ul class="list-unstyled">
                    @php
                        $relatedNews = \App\Models\CybersecurityNews::where('id', '!=', $news->id)->limit(3)->get();
                    @endphp
                    @foreach($relatedNews as $related)
                        <li class="mb-3 pb-3 border-bottom">
                            <a href="{{ route('news.show', $related->id) }}" class="text-decoration-none fw-bold text-dark d-block">
                                {{ $related->title }}
                            </a>
                            <small class="text-muted">{{ $related->date->format('d M Y') }}</small>
                        </li>
                    @endforeach
                </ul>
                
                <hr>
                
                <div class="mt-4 rounded-4 p-4 text-center" style="background: #101823; border: none;">
                    <h5 class="fw-bold text-white mb-3">Memiliki Insiden Siber?</h5>
                    <p class="small mb-4" style="color: #fed7aa;">Tim Jakarta CSIRT siap membantu Anda 24/7.</p>
                    <a href="{{ route('incidents.create.step1') }}" class="btn btn-light w-100 fw-bold py-2" style="color: #ea580c;">
                        Lapor Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection