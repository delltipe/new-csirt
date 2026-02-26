@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Berita Keamanan Siber</h1>
        <p class="text-lg text-slate-300">Artikel terbaru tentang keamanan siber dan ancaman digital</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($news as $article)
                <div class="card accent-border">
                    <div class="bg-slate-700 h-40 flex items-center justify-center">
                        @if($article->image_url)
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-slate-400">Gambar Berita</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2 line-clamp-2">{{ $article->title }}</h3>
                        <p class="text-slate-400 text-sm mb-4 line-clamp-3">{{ $article->excerpt ?? Str::limit($article->content, 100) }}</p>
                        @if($article->author)
                            <p class="text-xs text-slate-500 mb-2">Oleh: {{ $article->author }}</p>
                        @endif
                        <p class="text-xs text-slate-500 mb-4">{{ $article->published_at?->format('d M Y') ?? 'N/A' }}</p>
                        <a href="{{ route('news.show', $article) }}" class="text-orange-500 hover:text-orange-400 transition font-semibold text-sm">Baca Selengkapnya →</a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-slate-400 text-lg">Belum ada berita</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $news->links() }}
        </div>
    </div>
</section>
@endsection
