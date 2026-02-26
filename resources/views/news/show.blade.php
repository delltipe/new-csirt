@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <a href="{{ route('news.index') }}" class="text-orange-500 hover:text-orange-400 transition mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-4xl font-bold text-white mb-4">{{ $news->title }}</h1>
        <div class="flex flex-wrap gap-4 text-slate-300">
            @if($news->author)
                <span>Oleh: <strong>{{ $news->author }}</strong></span>
            @endif
            <span>{{ $news->published_at?->format('d M Y') ?? 'N/A' }}</span>
            @if($news->source_url)
                <a href="{{ $news->source_url }}" target="_blank" class="text-orange-500 hover:text-orange-400">Lihat Sumber</a>
            @endif
        </div>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                @if($news->image_url)
                    <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full rounded-lg mb-8 h-96 object-cover">
                @endif
                
                <div class="prose prose-invert max-w-none">
                    <p class="text-lg text-slate-300 leading-relaxed">{{ $news->content }}</p>
                </div>

                @if($news->excerpt)
                    <div class="mt-8 p-6 bg-slate-800 rounded-lg border-l-4 border-orange-500">
                        <p class="text-slate-300"><strong>Ringkasan:</strong> {{ $news->excerpt }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside>
                <!-- Related News -->
                <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
                    <h3 class="text-lg font-bold text-white mb-4">Berita Terkait</h3>
                    <div class="space-y-4">
                        @php
                            $relatedNews = \App\Models\CybersecurityNews::where('id', '!=', $news->id)
                                ->orderBy('published_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp
                        @forelse($relatedNews as $related)
                            <div class="pb-4 border-b border-slate-700 last:border-b-0 last:pb-0">
                                <a href="{{ route('news.show', $related) }}" class="text-white hover:text-orange-500 transition font-semibold text-sm">
                                    {{ $related->title }}
                                </a>
                                <p class="text-xs text-slate-500 mt-1">{{ $related->published_at?->format('d M Y') }}</p>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm">Tidak ada berita terkait</p>
                        @endforelse
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 bg-slate-800 rounded-lg p-6 border border-slate-700">
                    <h3 class="text-lg font-bold text-white mb-4">Lapor Insiden</h3>
                    <p class="text-slate-300 text-sm mb-4">Jika Anda mengalami insiden keamanan siber, segera laporkan ke Jakarta CSIRT.</p>
                    <a href="{{ route('incidents.create') }}" class="block w-full text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded transition">
                        Lapor Sekarang
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
