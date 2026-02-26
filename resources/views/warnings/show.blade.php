@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <a href="{{ route('warnings.index') }}" class="text-orange-500 hover:text-orange-400 transition mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-4xl font-bold text-white mb-4">{{ $warning->title }}</h1>
        <div class="flex flex-wrap gap-6 text-slate-300">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-white font-bold" style="background-color: {{ match($warning->severity) { 'critical' => '#dc2626', 'high' => '#f97316', 'medium' => '#eab308', 'low' => '#22c55e', default => '#888888' } }}">
                    {{ strtoupper($warning->severity) }}
                </span>
            </div>
            <span>Jenis: {{ ucwords(str_replace('_', ' ', $warning->threat_type)) }}</span>
            <span>{{ $warning->issued_at?->format('d M Y H:i') }}</span>
        </div>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-slate-800 rounded-lg border border-slate-700 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6">Deskripsi Alert</h2>
                    <p class="text-slate-300 leading-relaxed text-lg whitespace-pre-line">{{ $warning->content }}</p>
                </div>

                @if($warning->affected_systems)
                    <div class="bg-slate-800 rounded-lg border border-slate-700 p-8 mb-8">
                        <h3 class="text-xl font-bold text-white mb-4">Sistem yang Terdampak</h3>
                        <p class="text-slate-300">{{ $warning->affected_systems }}</p>
                    </div>
                @endif

                @if($warning->recommendations)
                    <div class="bg-green-900/20 border border-green-700 rounded-lg p-8">
                        <h3 class="text-xl font-bold text-green-400 mb-4">Rekomendasi</h3>
                        <p class="text-slate-300 leading-relaxed whitespace-pre-line">{{ $warning->recommendations }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside>
                <!-- Alert Details -->
                <div class="bg-slate-800 rounded-lg p-6 border border-slate-700 accent-border">
                    <h3 class="text-lg font-bold text-white mb-6">Detail Alert</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-slate-500 text-sm">Tingkat Keparahan</p>
                            <p class="text-white font-semibold">{{ ucfirst($warning->severity) }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500 text-sm">Jenis Ancaman</p>
                            <p class="text-white font-semibold">{{ ucwords(str_replace('_', ' ', $warning->threat_type)) }}</p>
                        </div>

                        <div>
                            <p class="text-slate-500 text-sm">Waktu Diterbitkan</p>
                            <p class="text-white font-semibold">{{ $warning->issued_at?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Incident Report -->
                <div class="mt-6 bg-slate-800 rounded-lg p-6 border border-slate-700">
                    <h3 class="text-lg font-bold text-white mb-4">Laporkan Insiden</h3>
                    <p class="text-slate-300 text-sm mb-4">Jika Anda terpengaruh oleh ancaman ini, segera laporkan ke Jakarta CSIRT.</p>
                    <a href="{{ route('incidents.create') }}" class="block w-full text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded transition">
                        Lapor Insiden
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $warning->title }} - Jakarta CSIRT</title>
</head>
<body>
    <div class="container my-5">
        <div class="alert alert-danger">
            <span class="severity-badge">{{ strtoupper($warning->severity) }}</span>
            <h1>{{ $warning->title }}</h1>
        </div>

        <div class="warning-details">
            <p><strong>Threat Type:</strong> {{ ucwords(str_replace('_', ' ', $warning->threat_type)) }}</p>
            <p><strong>Issued:</strong> {{ $warning->issued_at->format('l, d F Y H:i') }}</p>
            
            @if($warning->affected_systems)
                <div class="affected-systems">
                    <h3>Affected Systems</h3>
                    <p>{{ $warning->affected_systems }}</p>
                </div>
            @endif

            <div class="warning-content">
                {!! nl2br(e($warning->content)) !!}
            </div>

            @if($warning->recommendations)
                <div class="recommendations">
                    <h3>Recommendations</h3>
                    <p>{{ $warning->recommendations }}</p>
                </div>
            @endif
        </div>

        <a href="{{ route('warnings.index') }}" class="btn btn-secondary mt-3">Back to Warnings</a>
    </div>
</body>
</html>
