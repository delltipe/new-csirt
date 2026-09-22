@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Peraturan & Kebijakan</h4>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.law.update', $law->id) }}">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Judul</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $law->title) }}" required>
                    @error('title')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="5" required>{{ old('description', $law->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="link" class="form-label">URL Dokumen</label>
                    <input type="text" class="form-control @error('link') is-invalid @enderror" 
                           id="link" name="link" value="{{ old('link', $law->link) }}">
                    @error('link')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                   id="date" name="date" value="{{ old('date', $law->date ? $law->date->format('Y-m-d') : '') }}" required>
                            @error('date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="time" class="form-label">Waktu</label>
                            <input type="time" class="form-control @error('time') is-invalid @enderror" 
                                   id="time" name="time" value="{{ old('time', $law->time) }}">
                            @error('time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="downloadAmount" class="form-label">Jumlah Unduhan</label>
                    <input type="number" class="form-control @error('downloadAmount') is-invalid @enderror" 
                           id="downloadAmount" name="downloadAmount" value="{{ old('downloadAmount', $law->downloadAmount ?? 0) }}">
                    @error('downloadAmount')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
