@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <a href="{{ route('guides.index') }}" class="text-orange-500 hover:text-orange-400 transition mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-4xl font-bold text-white mb-4">{{ $guide->title }}</h1>
        <div class="flex items-center gap-4 text-slate-300">
            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: {{ match($guide->difficulty_level) { 'beginner' => '#22c55e', 'intermediate' => '#f97316', 'advanced' => '#ef4444', default => '#888888' } }}; color: white;">
                {{ strtoupper($guide->difficulty_level) }}
            </span>
            <span>{{ ucwords(str_replace('_', ' ', $guide->category)) }}</span>
        </div>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-slate-800 rounded-lg border border-slate-700 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6">Isi Panduan</h2>
                    <p class="text-slate-300 leading-relaxed text-lg whitespace-pre-line">{{ $guide->content }}</p>
                </div>
            </div>

            <!-- Sidebar -->
            <aside>
                <!-- Guide Info -->
                <div class="bg-slate-800 rounded-lg p-6 border border-slate-700 accent-border">
                    <h3 class="text-lg font-bold text-white mb-6">Informasi Panduan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-slate-500 text-sm">Tingkat Kesulitan</p>
                            <p class="text-white font-semibold">{{ ucfirst($guide->difficulty_level) }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500 text-sm">Kategori</p>
                            <p class="text-white font-semibold">{{ ucwords(str_replace('_', ' ', $guide->category)) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Related Guides -->
                <div class="mt-6 bg-slate-800 rounded-lg p-6 border border-slate-700">
                    <h3 class="text-lg font-bold text-white mb-4">Panduan Lainnya</h3>
                    <div class="space-y-2">
                        @php
                            $relatedGuides = \App\Models\CybersecurityGuide::where('id', '!=', $guide->id)
                                ->limit(3)
                                ->get();
                        @endphp
                        @forelse($relatedGuides as $related)
                            <a href="{{ route('guides.show', $related) }}" class="block text-slate-400 hover:text-orange-500 transition text-sm">
                                • {{ $related->title }}
                            </a>
                        @empty
                            <p class="text-slate-500 text-sm">Tidak ada panduan lainnya</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
