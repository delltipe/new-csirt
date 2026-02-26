@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <a href="{{ route('laws.index') }}" class="text-orange-500 hover:text-orange-400 transition mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-4xl font-bold text-white mb-4">{{ $post->title }}</h1>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-slate-800 rounded-lg border border-slate-700 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6">Konten Regulasi</h2>
                    <p class="text-slate-300 leading-relaxed text-lg whitespace-pre-line">{{ $post->content }}</p>
                </div>
            </div>

            <!-- Sidebar -->
            <aside>
                <!-- Law Details -->
                <div class="bg-slate-800 rounded-lg p-6 border border-slate-700 accent-border">
                    <h3 class="text-lg font-bold text-white mb-6">Detail Regulasi</h3>
                    
                    <div class="space-y-4">
                        @if($post->law_number)
                            <div>
                                <p class="text-slate-500 text-sm">Nomor Hukum</p>
                                <p class="text-white font-semibold">{{ $post->law_number }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-slate-500 text-sm">Tipe Regulasi</p>
                            <p class="text-white font-semibold">{{ ucwords(str_replace('_', ' ', $post->regulation_type)) }}</p>
                        </div>

                        @if($post->effective_date)
                            <div>
                                <p class="text-slate-500 text-sm">Tanggal Berlaku</p>
                                <p class="text-white font-semibold">{{ $post->effective_date->format('d M Y') }}</p>
                            </div>
                        @endif
                    </div>

                    @if($post->document_url)
                        <a href="{{ $post->document_url }}" target="_blank" class="block w-full mt-6 text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded transition">
                            Lihat Dokumen
                        </a>
                    @endif
                </div>

                <!-- Summary -->
                @if($post->summary)
                    <div class="mt-6 bg-slate-800 rounded-lg p-6 border border-slate-700">
                        <h3 class="text-lg font-bold text-white mb-4">Ringkasan</h3>
                        <p class="text-slate-300 text-sm leading-relaxed">{{ $post->summary }}</p>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</section>
@endsection
