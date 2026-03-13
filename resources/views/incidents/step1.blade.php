@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row g-5">
        @include('incidents._sidebar') {{-- We will create this partial below to avoid repetition --}}
        
        <div class="col-md-7 col-lg-8">
            <div class="mb-4">
                <span class="badge bg-primary">Langkah 1 dari 3</span>
                <h3 class="mt-2">Data Pelapor</h3>
            </div>

            <form action="{{ route('incidents.create.step1.post') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label for="fullName" class="form-label">Full name</label>
                        <input type="text" name="fullName" class="form-control" id="fullName" value="{{ $incident['fullName'] ?? '' }}" required>
                        @error('fullName') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" value="{{ $incident['email'] ?? '' }}" required>
                    </div>
                    <div class="col-12">
                        <label for="phoneNumber" class="form-label">Phone number</label>
                        <input type="text" name="phoneNumber" class="form-control" id="phoneNumber" value="{{ $incident['phoneNumber'] ?? '' }}" required>
                    </div>
                    <div class="col-12">
                        <label for="foundDate" class="form-label">Tanggal Temuan</label>
                        <input type="date" name="foundDate" class="form-control" id="foundDate" value="{{ $incident['foundDate'] ?? '' }}">
                    </div>
                </div>

                <hr class="my-4">
                <button class="w-100 btn btn-primary btn-lg" type="submit">Selanjutnya (Data Website) ➜</button>
            </form>
        </div>
    </div>
</div>
@endsection