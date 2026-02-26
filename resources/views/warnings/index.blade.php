@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Peringatan Keamanan</h1>
        <p class="text-lg text-slate-300">Alert kritis dan notifikasi ancaman terbaru</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <!-- Warnings List -->
        <div class="space-y-6">
            @forelse($warnings as $warning)
                <div class="card accent-border p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-white mb-2">{{ $warning->title }}</h3>
                            <div class="flex flex-wrap gap-4 text-slate-400">
                                <span class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full" style="background-color: {{ match($warning->severity) { 'critical' => '#dc2626', 'high' => '#f97316', 'medium' => '#eab308', 'low' => '#22c55e', default => '#888888' } }}"></span>
                                    <strong>{{ strtoupper($warning->severity) }}</strong>
                                </span>
                                <span>Jenis: {{ ucwords(str_replace('_', ' ', $warning->threat_type)) }}</span>
                                <span>{{ $warning->issued_at?->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-slate-300 mb-4 line-clamp-2">{{ Str::limit($warning->content, 200) }}</p>
                    
                    @if($warning->affected_systems)
                        <p class="text-sm text-slate-500 mb-4"><strong>Sistem Terdampak:</strong> {{ $warning->affected_systems }}</p>
                    @endif

                    <a href="{{ route('warnings.show', $warning) }}" class="text-orange-500 hover:text-orange-400 transition font-semibold">
                        Lihat Detail Lengkap →
                    </a>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-slate-400 text-lg">Tidak ada peringatan keamanan aktif</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $warnings->links() }}
        </div>
    </div>
</section>
@endsection
