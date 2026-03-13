@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row g-5">
        @include('incidents._sidebar')
        
        <div class="col-md-7 col-lg-8">
            <div class="mb-4">
                <span class="badge bg-secondary">Langkah 2 dari 3</span>
                <h3 class="mt-2">Data Website</h3>
            </div>

            <form action="{{ route('incidents.create.step2.post') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label for="domain" class="form-label">Domain (Contoh: jakarta.go.id)</label>
                        <input type="text" name="domain" class="form-control" id="domain" value="{{ $incident['domain'] ?? '' }}" required>
                    </div>
                    <div class="col-12">
                        <label for="url" class="form-label">URL Spesifik</label>
                        <input type="url" name="url" class="form-control" id="url" value="{{ $incident['url'] ?? '' }}" required>
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex gap-3">
                    <a href="{{ route('incidents.create.step1') }}" class="w-100 btn btn-outline-secondary btn-lg">⬅ Sebelumnya</a>
                    <button class="w-100 btn btn-primary btn-lg" type="submit">Selanjutnya (Detail) ➜</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection