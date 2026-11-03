@extends('layouts.app')

@section('content')
<style>
    .evidence-message { max-width: 720px; margin: 0 auto; padding: var(--space-16) var(--space-6); color: var(--ink); }
    .evidence-message h1 { font-family: var(--font-display); font-weight: 800; color: var(--ink); margin-bottom: var(--space-4); }
    .evidence-message p { margin-bottom: var(--space-8); line-height: 1.6; }
</style>
<section class="evidence-message" aria-labelledby="evidence-message-title">
    <h1 id="evidence-message-title">Lampiran tidak tersedia</h1>
    <p>Lampiran tidak dapat ditemukan atau akun Anda tidak memiliki akses. Pastikan Anda masuk dengan akun yang sesuai.</p>
    <a href="{{ route('home') }}" class="btn-navy">Kembali ke Beranda</a>
</section>
@endsection
