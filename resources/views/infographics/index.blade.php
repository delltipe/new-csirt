@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Infografis Keamanan Siber</h1>
        <p class="text-lg text-slate-300">Panduan visual tentang keamanan siber dan ancaman digital</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <!-- Infographics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($infographics as $infographic)
                <a href="{{ route('infographics.show', $infographic) }}" class="card group cursor-pointer">
                    <div class="bg-slate-700 h-64 flex items-center justify-center overflow-hidden group-hover:opacity-90 transition">
                        @if($infographic->image_url)
                            <img src="{{ $infographic->image_url }}" alt="{{ $infographic->title }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-slate-400">Infografis</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2">{{ $infographic->title }}</h3>
                        <p class="text-sm text-orange-500 mb-3">{{ ucwords($infographic->category) }}</p>
                        <p class="text-slate-400 text-sm line-clamp-2">{{ Str::limit($infographic->description, 80) }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-slate-400 text-lg">Belum ada infografis</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $infographics->links() }}
        </div>
    </div>
</section>
@endsection
