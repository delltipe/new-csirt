@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Event & Webinar</h1>
        <p class="text-lg text-slate-300">Bergabunglah dengan kami dalam berbagai acara keamanan siber</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <!-- Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($events as $event)
                <div class="card accent-border hover:shadow-lg transition">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-8 text-white text-center">
                        <div class="text-6xl font-bold">{{ $event->event_date->format('d') }}</div>
                        <div class="text-2xl">{{ $event->event_date->format('M') }}</div>
                        <div class="text-sm opacity-90">{{ $event->event_date->format('Y') }}</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-white mb-2">{{ $event->title }}</h3>
                        <p class="text-slate-400 text-sm mb-4 line-clamp-2">{{ $event->description }}</p>
                        
                        <div class="space-y-2 mb-4 text-sm text-slate-400">
                            @if($event->event_type)
                                <p>
                                    <span class="text-slate-500">Tipe:</span>
                                    <span class="text-white font-semibold">{{ ucfirst($event->event_type) }}</span>
                                </p>
                            @endif
                            @if($event->location)
                                <p>
                                    <span class="text-slate-500">Lokasi:</span>
                                    <span class="text-white">{{ $event->location }}</span>
                                </p>
                            @endif
                            @if($event->capacity)
                                <p>
                                    <span class="text-slate-500">Kapasitas:</span>
                                    <span class="text-white">{{ $event->capacity }} Peserta</span>
                                </p>
                            @endif
                        </div>

                        <a href="{{ route('events.show', $event) }}" class="text-orange-500 hover:text-orange-400 transition font-semibold text-sm">
                            Detail & Daftar →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12">
                    <p class="text-slate-400 text-lg">Belum ada event mendatang</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $events->links() }}
        </div>
    </div>
</section>
@endsection
