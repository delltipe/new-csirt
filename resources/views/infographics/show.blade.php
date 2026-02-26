@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <a href="{{ route('infographics.index') }}" class="text-orange-500 hover:text-orange-400 transition mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-4xl font-bold text-white mb-4">{{ $infographic->title }}</h1>
        <p class="text-lg text-orange-500">Kategori: {{ ucwords($infographic->category) }}</p>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            @if($infographic->image_url)
                <img src="{{ $infographic->image_url }}" alt="{{ $infographic->title }}" class="w-full rounded-lg mb-8 max-h-96 lg:max-h-full object-contain">
            @endif

            @if($infographic->description)
                <div class="bg-slate-800 rounded-lg border border-slate-700 p-8">
                    <h2 class="text-2xl font-bold text-white mb-4">Deskripsi</h2>
                    <p class="text-slate-300 leading-relaxed text-lg">{{ $infographic->description }}</p>
                </div>
            @endif

            <!-- Download/Share Section -->
            <div class="mt-8 flex gap-4">
                @if($infographic->image_url)
                    <a href="{{ $infographic->image_url }}" download class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded transition">
                        Download Infografis
                    </a>
                @endif
                <a href="https://twitter.com/share?url={{ route('infographics.show', $infographic) }}&text={{ urlencode($infographic->title) }}" target="_blank" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded transition">
                    Bagikan
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
