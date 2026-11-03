@extends('layouts.app')

@section('content')
<style>
    .error-message { max-width: 720px; margin: 0 auto; padding: var(--space-16) var(--space-6); color: var(--ink); }
    .error-message h1 { font-family: var(--font-display); font-weight: 800; color: var(--ink); margin-bottom: var(--space-4); }
    .error-message p { margin-bottom: var(--space-8); line-height: 1.6; }
</style>
<section class="error-message" aria-labelledby="error-title">
    <h1 id="error-title">Halaman tidak ditemukan</h1>
    <p>Halaman yang Anda cari tidak tersedia atau alamatnya telah berubah.</p>
    <a href="{{ route('home') }}" class="btn-navy">Kembali ke Beranda</a>
</section>
@endsection
