@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Slide Hero #{{ $slide->id }}</h4>
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

            <form method="POST" action="{{ route('admin.hero.update', $slide->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Judul *</label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul" value="{{ old('judul', $slide->judul) }}" required>
                    @error('judul')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Subjudul</label>
                    <textarea class="form-control @error('subjudul') is-invalid @enderror" name="subjudul" rows="3">{{ old('subjudul', $slide->subjudul) }}</textarea>
                    @error('subjudul')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar Saat Ini</label>
                    <div>
                        @if($slide->gambar)
                            @php $img = $slide->gambar; if(!str_starts_with($img,'http')) $img = \Illuminate\Support\Facades\Storage::url($img); @endphp
                            <small style="word-break:break-all;">{{ $slide->gambar }}</small><br>
                            <img src="{{ $img }}" alt="" style="max-height:120px;max-width:100%;border:1px solid var(--border);margin-top:8px;">
                        @else
                            <small style="color:var(--mid);">— belum ada gambar</small>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ganti Gambar (upload)</label>
                    <input type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar" accept=".jpg,.jpeg,.png,.webp">
                    @error('gambar')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Atau URL Gambar Baru</label>
                    <input type="text" class="form-control" name="gambar_url" value="{{ old('gambar_url') }}" placeholder="https://...">
                    <small style="color:var(--mid);">Isi salah satu: upload file atau URL. Kosongkan untuk tetap pakai gambar lama.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tautan CTA</label>
                    <input type="text" class="form-control" name="tautan" value="{{ old('tautan', $slide->tautan) }}" placeholder="/warnings">
                </div>

                <div class="mb-3">
                    <label class="form-label">Teks Tautan</label>
                    <input type="text" class="form-control" name="teks_tautan" value="{{ old('teks_tautan', $slide->teks_tautan) }}">
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="urutan" value="{{ old('urutan', $slide->urutan) }}">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Aktif</label>
                        <select class="form-select" name="is_active">
                            <option value="1" {{ old('is_active', $slide->is_active) ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ !old('is_active', $slide->is_active) ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                </div>

                <div>
                    <a href="{{ route('admin.dashboard') }}#hero" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui Slide</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
