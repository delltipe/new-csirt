@extends('layouts.app')

@section('content')
<section class="py-5 text-center container">
    <div class="row py-lg-4">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h1 class="fw-bold display-5 text-danger">Peringatan Keamanan</h1>
            <p class="lead text-body-secondary">Notifikasi ancaman siber terbaru sesuai standar teknis Jakarta CSIRT.</p>
        </div>
    </div>
</section>

<div class="album py-5 bg-body-tertiary">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @forelse($warnings as $warning)
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ $warning->thumbnail }}" class="card-img-top" style="height: 225px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <p class="card-text fw-bold mb-2">{{ $warning->title }}</p>
                            <p class="card-text text-secondary small mb-3">
                                {{ Str::limit($warning->description, 110) }}
                            </p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <a href="{{ route('warnings.show', $warning->id) }}" class="btn btn-sm btn-outline-danger fw-medium">Detail Alert</a>
                                <small class="text-body-secondary">
                                    {{ $warning->date->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Tidak ada peringatan keamanan saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection