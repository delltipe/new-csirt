@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav class="mb-4">
        <a href="{{ route('warnings.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </nav>

    <div class="row g-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-3">{{ $warning->title }}</h1>
            <p class="text-muted border-bottom pb-3">
                <i class="bi bi-calendar-event"></i> Diterbitkan pada: {{ $warning->date->format('d M Y') }}
            </p>

            <img src="{{ $warning->thumbnail }}" class="img-fluid rounded-4 mb-4 shadow-sm" style="width: 100%; max-height: 400px; object-fit: cover;">
            
            <div class="warning-content fs-5 lh-lg">
                {!! nl2br(e($warning->description)) !!}
            </div>

            @if($warning->source)
                <div class="mt-4">
                    <a href="{{ $warning->source }}" target="_blank" class="btn btn-link p-0 text-decoration-none">
                        <i class="bi bi-link-45deg"></i> Sumber Referensi Resmi
                    </a>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="p-4 rounded-4 text-center shadow-sm" style="background: #101823;">
                <h5 class="fw-bold text-white mb-3">Butuh Bantuan?</h5>
                <p class="small mb-4" style="color: #fed7aa;">Laporkan jika sistem Anda terpengaruh oleh ancaman ini.</p>
                <a href="{{ route('incidents.create.step1') }}" class="btn btn-light w-100 fw-bold py-2" style="color: #dc2626;">
                    Lapor Insiden
                </a>
            </div>
        </div>
    </div>
</div>
@endsection