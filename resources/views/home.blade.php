@extends('layouts.app')

@section('content')
<!-- Hero Banner Section -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <!-- Left Content -->
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    Lindungi Data Indonesia
                </h1>
                <p class="text-lg text-slate-300 mb-8">
                    Jakarta CSIRT siap membantu melindungi infrastruktur kritis dan data digital Anda dari ancaman siber 24/7.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('incidents.create') }}" class="btn-report">Lapor Insiden Sekarang</a>
                    <a href="{{ route('contact.create') }}" class="px-6 py-2 border-2 border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white rounded transition font-semibold">
                        Hubungi Kami
                    </a>
                </div>
            </div>
            <!-- Right Image Placeholder -->
            <div class="bg-slate-700 rounded-lg h-64 md:h-80 flex items-center justify-center">
                <span class="text-slate-400">Gambar Banner</span>
            </div>
        </div>
    </div>
</section>

<!-- Recent News Section -->
<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="mb-12">
            <h2 class="section-title">Berita Terkini</h2>
            <p class="section-subtitle">Informasi terkini seputar keamanan siber di lingkungan Pemprov DKI Jakarta</p>
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse($recentNews as $article)
                <div class="card accent-border">
                    <div class="bg-slate-700 h-40 flex items-center justify-center">
                        @if($article->image_url)
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-slate-400">Gambar</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2 line-clamp-2">{{ $article->title }}</h3>
                        <p class="text-slate-400 text-sm mb-4 line-clamp-2">{{ $article->excerpt ?? Str::limit($article->content, 100) }}</p>
                        <p class="text-xs text-slate-500 mb-4">{{ $article->published_at?->format('d M Y') ?? 'N/A' }}</p>
                        <a href="{{ route('news.show', $article) }}" class="text-orange-500 hover:text-orange-400 transition font-semibold text-sm">Baca Selengkapnya →</a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8">
                    <p class="text-slate-400">Belum ada berita terbaru</p>
                </div>
            @endforelse
        </div>

        <!-- View All News Link -->
        <div class="text-right">
            <a href="{{ route('news.index') }}" class="text-orange-500 hover:text-orange-400 transition font-semibold">
                Lihat semua berita →
            </a>
        </div>
    </div>
</section>

<!-- Main Services Section -->
<section class="py-16 md:py-20 bg-slate-800/50">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="mb-12">
            <h2 class="section-title">Layanan Utama</h2>
            <p class="section-subtitle">Layanan/Informasi seputar keamanan siber di lingkungan Pemprov DKI Jakarta</p>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Warning Posts -->
            <div class="card accent-border group cursor-pointer">
                <div class="bg-slate-700 h-32 flex items-center justify-center group-hover:bg-slate-600 transition">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-orange-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-white mb-2">Warning Posts</h3>
                    <p class="text-slate-400 text-sm">Alert keamanan dan notifikasi ancaman terbaru</p>
                    <a href="{{ route('warnings.index') }}" class="inline-block mt-4 text-orange-500 hover:text-orange-400 transition font-semibold text-sm">Lihat →</a>
                </div>
            </div>

            <!-- Infographics -->
            <div class="card accent-border group cursor-pointer">
                <div class="bg-slate-700 h-32 flex items-center justify-center group-hover:bg-slate-600 transition">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-orange-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-white mb-2">Infografis</h3>
                    <p class="text-slate-400 text-sm">Panduan visual tentang keamanan siber</p>
                    <a href="{{ route('infographics.index') }}" class="inline-block mt-4 text-orange-500 hover:text-orange-400 transition font-semibold text-sm">Lihat →</a>
                </div>
            </div>

            <!-- Laws & Regulations -->
            <div class="card accent-border group cursor-pointer">
                <div class="bg-slate-700 h-32 flex items-center justify-center group-hover:bg-slate-600 transition">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-orange-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.001m20-11.748C21.5 6.253 17.5 10.998 17.5 17"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-white mb-2">Hukum & Regulasi</h3>
                    <p class="text-slate-400 text-sm">Undang-undang keamanan siber Indonesia</p>
                    <a href="{{ route('laws.index') }}" class="inline-block mt-4 text-orange-500 hover:text-orange-400 transition font-semibold text-sm">Lihat →</a>
                </div>
            </div>

            <!-- Guides -->
            <div class="card accent-border group cursor-pointer">
                <div class="bg-slate-700 h-32 flex items-center justify-center group-hover:bg-slate-600 transition">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-orange-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.001m20-11.748C21.5 6.253 17.5 10.998 17.5 17"></path>
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-white mb-2">Panduan</h3>
                    <p class="text-slate-400 text-sm">Panduan praktis keamanan siber untuk individu</p>
                    <a href="{{ route('guides.index') }}" class="inline-block mt-4 text-orange-500 hover:text-orange-400 transition font-semibold text-sm">Lihat →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Events Section -->
<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="mb-12">
            <h2 class="section-title">Event Mendatang</h2>
            <p class="section-subtitle">Bergabunglah dengan kami dalam workshop, webinar, dan pelatihan keamanan siber</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($upcomingEvents as $event)
                <div class="card accent-border">
                    <div class="bg-slate-700 h-40 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-orange-500">{{ $event->event_date->format('d') }}</div>
                            <div class="text-slate-400">{{ $event->event_date->format('M Y') }}</div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ $event->title }}</h3>
                        <p class="text-slate-400 text-sm mb-3">{{ Str::limit($event->description, 80) }}</p>
                        <p class="text-xs text-slate-500 mb-3">{{ ucwords($event->event_type) }} @if($event->location) • {{ $event->location }} @endif</p>
                        <a href="{{ route('events.show', $event) }}" class="text-orange-500 hover:text-orange-400 transition font-semibold text-sm">Lihat Detail →</a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-8">
                    <p class="text-slate-400">Belum ada event mendatang</p>
                </div>
            @endforelse
        </div>

        @if($upcomingEvents->count() > 0)
            <div class="text-center mt-8">
                <a href="{{ route('events.index') }}" class="text-orange-500 hover:text-orange-400 transition font-semibold">
                    Lihat semua event →
                </a>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 md:py-20 bg-gradient-to-r from-orange-600 to-orange-700">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Memiliki Insiden Keamanan Siber?</h2>
        <p class="text-lg text-orange-100 mb-8 max-w-2xl mx-auto">
            Tim Jakarta CSIRT siap membantu Anda 24/7. Laporkan insiden keamanan siber Anda sekarang dan dapatkan respons cepat dari para ahli kami.
        </p>
        <a href="{{ route('incidents.create') }}" class="inline-block bg-white text-orange-600 hover:bg-orange-50 font-bold py-3 px-8 rounded transition">
            Lapor Insiden Sekarang
        </a>
    </div>
</section>
@endsection
