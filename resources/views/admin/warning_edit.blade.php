@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Warning</h4>
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
            
            <form method="POST" action="{{ route('admin.warning.update', $warning->id) }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $warning->title) }}" required>
                    @error('title')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="5" required>{{ old('description', $warning->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="thumbnail" class="form-label">Thumbnail URL</label>
                    <input type="text" class="form-control @error('thumbnail') is-invalid @enderror" 
                           id="thumbnail" name="thumbnail" value="{{ old('thumbnail', $warning->thumbnail) }}">
                    @error('thumbnail')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="source" class="form-label">Source</label>
                    <input type="text" class="form-control @error('source') is-invalid @enderror" 
                           id="source" name="source" value="{{ old('source', $warning->source) }}">
                    @error('source')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Date & Time</label>
                    <input type="datetime-local" class="form-control @error('date') is-invalid @enderror" 
                           id="date" name="date" 
                           value="{{ old('date', $warning->date ? $warning->date->format('Y-m-d\TH:i') : '') }}" required>
                    @error('date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">Upload Image (JPG/PNG)</label>
                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept="image/*">
                    @if($warning->file_path)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $warning->file_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $warning->file_path) }}" alt="Current File" style="max-width:100px;max-height:100px;">
                            </a>
                        </div>
                    @endif
                    @error('file')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Warning</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
