@extends('layouts.app')

@section('content')
<style>
.search-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
}
.search-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(
        90deg,
        rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 1px,
        transparent 1px, transparent 80px
    );
    pointer-events: none;
}
.search-header .container { position: relative; z-index: 1; }
.search-header__title {
    font-family: var(--font-display);
    font-size: 38px;
    font-weight: 900;
    text-transform: uppercase;
    color: var(--white);
    letter-spacing: 0.02em;
    margin: 0;
}
.search-form-row {
    max-width: 640px;
}
.search-form-row .form-control {
    border: 2px solid var(--border);
    border-radius: 0;
    padding: 12px 16px;
    font-size: 15px;
}
.search-form-row .btn-search {
    background: var(--navy);
    color: var(--white);
    border: none;
    border-radius: 0;
    padding: 12px 28px;
    font-family: var(--font-display);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-size: 14px;
    cursor: pointer;
}
.search-form-row .btn-search:hover {
    background: var(--navy-dim);
}
.search-section {
    padding: 40px 0;
}
.search-section__title {
    font-family: var(--font-display);
    font-size: 22px;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--ink);
    letter-spacing: 0.02em;
    border-bottom: 2px solid var(--navy);
    padding-bottom: 8px;
    margin-bottom: 20px;
}
.search-result-item {
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
}
.search-result-item:last-child {
    border-bottom: none;
}
.search-result-item__title {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 700;
    margin: 0 0 4px;
}
.search-result-item__title a {
    color: var(--navy);
    text-decoration: none;
}
.search-result-item__title a:hover {
    text-decoration: underline;
}
.search-result-item__excerpt {
    font-size: 14px;
    color: var(--mid);
    margin: 0;
    line-height: 1.5;
}
.search-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--mid);
}
.search-empty__icon {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.3;
}
</style>

<div class="search-header">
    <div class="container">
        <h1 class="search-header__title">Pencarian</h1>
        @if($query)
            <p style="color: rgba(255,255,255,0.5); margin-top: 8px; font-size: 15px;">
                Hasil pencarian untuk "<strong>{{ $query }}</strong>"
            </p>
        @endif
    </div>
</div>

<div class="search-section">
    <div class="container">
        <form action="{{ route('search') }}" method="GET" class="search-form-row d-flex gap-2 mb-5">
            <input type="search" name="q" class="form-control" placeholder="Cari berita, peringatan, event..."
                   value="{{ $query }}" aria-label="Cari konten situs">
            <button type="submit" class="btn-search">Cari</button>
        </form>

        @if(empty(trim($query)))
            <div class="search-empty">
                <div class="search-empty__icon"><i class="bi bi-search"></i></div>
                <p>Ketik kata kunci di atas untuk mulai mencari.</p>
            </div>
        @elseif(strlen(trim($query)) < 2)
            <div class="search-empty">
                <p>Masukkan minimal 2 karakter untuk pencarian.</p>
            </div>
        @else
            @php
                $totalResults = collect($results)->sum->count();
            @endphp

            @if($totalResults === 0)
                <div class="search-empty">
                    <div class="search-empty__icon"><i class="bi bi-search"></i></div>
                    <p>Tidak ada hasil ditemukan untuk "<strong>{{ $query }}</strong>".</p>
                    <p style="font-size: 13px; margin-top: 8px;">Coba kata kunci lain atau periksa ejaan.</p>
                </div>
            @endif

            @if($results['news']->count())
                <div class="mb-4">
                    <h2 class="search-section__title">
                        <i class="bi bi-newspaper me-2"></i>Berita Siber ({{ $results['news']->count() }})
                    </h2>
                    @foreach($results['news'] as $item)
                        <div class="search-result-item">
                            <h3 class="search-result-item__title">
                                <a href="{{ route('news.show', $item->id) }}">{{ $item->title }}</a>
                            </h3>
                            @if($item->description)
                                <p class="search-result-item__excerpt">{{ Str::limit(strip_tags($item->description), 180) }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if($results['warnings']->count())
                <div class="mb-4">
                    <h2 class="search-section__title">
                        <i class="bi bi-exclamation-triangle me-2"></i>Peringatan Keamanan ({{ $results['warnings']->count() }})
                    </h2>
                    @foreach($results['warnings'] as $item)
                        <div class="search-result-item">
                            <h3 class="search-result-item__title">
                                <a href="{{ route('warnings.show', $item->id) }}">{{ $item->title }}</a>
                            </h3>
                            @if($item->description)
                                <p class="search-result-item__excerpt">{{ Str::limit(strip_tags($item->description), 180) }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if($results['events']->count())
                <div class="mb-4">
                    <h2 class="search-section__title">
                        <i class="bi bi-calendar-event me-2"></i>Event ({{ $results['events']->count() }})
                    </h2>
                    @foreach($results['events'] as $item)
                        <div class="search-result-item">
                            <h3 class="search-result-item__title">
                                <a href="{{ route('events.show', $item->id) }}">{{ $item->title }}</a>
                            </h3>
                            @if($item->description)
                                <p class="search-result-item__excerpt">{{ Str::limit(strip_tags($item->description), 180) }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if($results['infographics']->count())
                <div class="mb-4">
                    <h2 class="search-section__title">
                        <i class="bi bi-image me-2"></i>Infografis ({{ $results['infographics']->count() }})
                    </h2>
                    @foreach($results['infographics'] as $item)
                        <div class="search-result-item">
                            <h3 class="search-result-item__title">
                                <a href="{{ route('infographics.show', $item->id) }}">{{ $item->title }}</a>
                            </h3>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($results['laws']->count())
                <div class="mb-4">
                    <h2 class="search-section__title">
                        <i class="bi bi-bank me-2"></i>Peraturan & Kebijakan ({{ $results['laws']->count() }})
                    </h2>
                    @foreach($results['laws'] as $item)
                        <div class="search-result-item">
                            <h3 class="search-result-item__title">
                                <a href="{{ route('laws.show', $item->id) }}">{{ $item->title }}</a>
                            </h3>
                            @if($item->description)
                                <p class="search-result-item__excerpt">{{ Str::limit(strip_tags($item->description), 180) }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if($results['guides']->count())
                <div class="mb-4">
                    <h2 class="search-section__title">
                        <i class="bi bi-book me-2"></i>Panduan Teknis ({{ $results['guides']->count() }})
                    </h2>
                    @foreach($results['guides'] as $item)
                        <div class="search-result-item">
                            <h3 class="search-result-item__title">
                                @if($item->link)
                                    <a href="{{ $item->link }}" target="_blank">{{ $item->title }}</a>
                                @else
                                    {{ $item->title }}
                                @endif
                            </h3>
                            <p class="search-result-item__excerpt">Oleh: {{ $item->author }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
