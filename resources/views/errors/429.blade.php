@extends('layouts.app')

@section('content')
<style>
    .rate-limit-message { max-width: 720px; margin: 0 auto; padding: var(--space-16) var(--space-6); color: var(--ink); }
    .rate-limit-message h1 { font-family: var(--font-display); font-weight: 800; color: var(--ink); margin-bottom: var(--space-4); }
    .rate-limit-message p { margin-bottom: var(--space-8); line-height: 1.6; }
</style>
<section class="rate-limit-message" aria-labelledby="rate-limit-title">
    <h1 id="rate-limit-title">Terlalu banyak permintaan</h1>
    <p>Anda telah mencapai batas sementara. Tunggu sebentar, lalu coba lagi.</p>
    <a href="{{ route('home') }}" class="btn-navy">Kembali ke Beranda</a>
</section>
@endsection
