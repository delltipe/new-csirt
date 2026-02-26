@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Panduan Keamanan Siber</h1>
        <p class="text-lg text-slate-300">Pelajari cara melindungi diri dari ancaman keamanan siber</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <!-- Guides Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($guides as $guide)
                <div class="card accent-border">
                    <div class="bg-gradient-to-br from-slate-700 to-slate-800 p-6 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex-1">{{ $guide->title }}</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: {{ match($guide->difficulty_level) { 'beginner' => '#22c55e', 'intermediate' => '#f97316', 'advanced' => '#ef4444', default => '#888888' } }}; color: white;">
                            {{ strtoupper($guide->difficulty_level) }}
                        </span>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-orange-500 mb-3">{{ ucwords(str_replace('_', ' ', $guide->category)) }}</p>
                        <p class="text-slate-400 text-sm line-clamp-3 mb-4">{{ Str::limit($guide->content, 100) }}</p>
                        <a href="{{ route('guides.show', $guide) }}" class="text-orange-500 hover:text-orange-400 transition font-semibold text-sm">
                            Baca Panduan →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-slate-400 text-lg">Belum ada panduan</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $guides->links() }}
        </div>
    </div>
</section>
@endsection
