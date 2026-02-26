@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-slate-900 to-slate-800 py-12">
    <div class="container mx-auto px-4">
        <a href="{{ route('events.index') }}" class="text-orange-500 hover:text-orange-400 transition mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-4xl font-bold text-white mb-4">{{ $event->title }}</h1>
        <div class="flex flex-wrap gap-6 text-slate-300">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 4h8v2H8V4z M3 6h2v12H3V6z M19 6h2v12h-2V6z M4 18h16v2H4v-2z"></path>
                </svg>
                <span><strong>{{ $event->event_date->format('d M Y') }}</strong></span>
            </div>
            @if($event->location)
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"></path>
                    </svg>
                    <span>{{ $event->location }}</span>
                </div>
            @endif
            @if($event->event_type)
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 5h2v2H5V5z M11 5h2v2h-2V5z M17 5h2v2h-2V5z M5 11h2v2H5v-2z"></path>
                    </svg>
                    <span>{{ ucfirst($event->event_type) }}</span>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Event Details Card -->
                <div class="bg-slate-800 rounded-lg border border-slate-700 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6">Deskripsi Event</h2>
                    <p class="text-slate-300 leading-relaxed text-lg">{{ $event->description }}</p>
                </div>

                <!-- Event Information -->
                <div class="grid grid-cols-2 gap-6 mb-8">
                    @if($event->capacity)
                        <div class="bg-slate-800 rounded-lg border border-slate-700 p-6">
                            <p class="text-slate-400 text-sm mb-1">Kapasitas</p>
                            <p class="text-3xl font-bold text-orange-500">{{ $event->capacity }}</p>
                            <p class="text-xs text-slate-500">Peserta</p>
                        </div>
                    @endif
                    <div class="bg-slate-800 rounded-lg border border-slate-700 p-6">
                        <p class="text-slate-400 text-sm mb-1">Tanggal</p>
                        <p class="text-lg font-bold text-white">{{ $event->event_date->format('d M') }}</p>
                        <p class="text-xs text-slate-500">{{ $event->event_date->format('Y') }}</p>
                    </div>
                </div>

                <!-- Registration Section -->
                @if($event->registration_url)
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-8">
                        <h3 class="text-2xl font-bold text-white mb-2">Tertarik Mengikuti?</h3>
                        <p class="text-orange-100 mb-6">Daftar sekarang untuk mengikuti event ini dan dapatkan akses eksklusif.</p>
                        <a href="{{ $event->registration_url }}" target="_blank" class="inline-block bg-white text-orange-600 hover:bg-orange-50 font-bold py-3 px-8 rounded transition">
                            Daftar Event
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside>
                <!-- Event Summary -->
                <div class="bg-slate-800 rounded-lg p-6 border border-slate-700 accent-border">
                    <h3 class="text-lg font-bold text-white mb-6">Ringkasan Event</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-slate-500 text-sm">Tanggal & Waktu</p>
                            <p class="text-white font-semibold">{{ $event->event_date->format('d M Y') }}</p>
                        </div>

                        @if($event->location)
                            <div>
                                <p class="text-slate-500 text-sm">Lokasi</p>
                                <p class="text-white font-semibold">{{ $event->location }}</p>
                            </div>
                        @endif

                        @if($event->event_type)
                            <div>
                                <p class="text-slate-500 text-sm">Jenis Event</p>
                                <p class="text-white font-semibold">{{ ucfirst($event->event_type) }}</p>
                            </div>
                        @endif

                        @if($event->capacity)
                            <div>
                                <p class="text-slate-500 text-sm">Kapasitas</p>
                                <p class="text-white font-semibold">{{ $event->capacity }} Peserta</p>
                            </div>
                        @endif
                    </div>

                    @if($event->registration_url)
                        <a href="{{ $event->registration_url }}" target="_blank" class="block w-full mt-6 text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded transition">
                            Daftar Sekarang
                        </a>
                    @endif
                </div>

                <!-- Related Events -->
                <div class="mt-6 bg-slate-800 rounded-lg p-6 border border-slate-700">
                    <h3 class="text-lg font-bold text-white mb-4">Event Lainnya</h3>
                    <div class="space-y-4">
                        @php
                            $relatedEvents = \App\Models\Event::where('id', '!=', $event->id)
                                ->where('event_date', '>=', now())
                                ->orderBy('event_date', 'asc')
                                ->limit(3)
                                ->get();
                        @endphp
                        @forelse($relatedEvents as $related)
                            <div class="pb-4 border-b border-slate-700 last:border-b-0 last:pb-0">
                                <a href="{{ route('events.show', $related) }}" class="text-white hover:text-orange-500 transition font-semibold text-sm">
                                    {{ $related->title }}
                                </a>
                                <p class="text-xs text-slate-500 mt-1">{{ $related->event_date->format('d M Y') }}</p>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm">Tidak ada event lainnya</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
