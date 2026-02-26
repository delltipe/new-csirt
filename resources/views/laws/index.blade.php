@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Hukum & Regulasi Keamanan Siber</h1>
        <p class="text-lg text-slate-300">Undang-undang dan regulasi keamanan siber Indonesia</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <!-- Laws List -->
        <div class="space-y-6">
            @forelse($posts as $post)
                <div class="card accent-border p-6 hover:shadow-lg transition">
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $post->title }}</h3>
                        <div class="flex flex-wrap gap-4 text-slate-400">
                            @if($post->law_number)
                                <span>Nomor: <strong>{{ $post->law_number }}</strong></span>
                            @endif
                            <span>Tipe: {{ ucwords(str_replace('_', ' ', $post->regulation_type)) }}</span>
                            @if($post->effective_date)
                                <span>Berlaku: {{ $post->effective_date->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>

                    <p class="text-slate-300 mb-4">{{ $post->summary ?? Str::limit($post->content, 200) }}</p>
                    
                    <a href="{{ route('laws.show', $post) }}" class="text-orange-500 hover:text-orange-400 transition font-semibold">
                        Baca Selengkapnya →
                    </a>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-slate-400 text-lg">Belum ada regulasi</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
</section>
@endsection
