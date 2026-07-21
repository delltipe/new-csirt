@extends('layouts.app')

@section('content')
<style>
.thankyou-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
}
.thankyou-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(
        90deg,
        rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 1px,
        transparent 1px, transparent 80px
    );
    pointer-events: none;
}
.thankyou-header .container { position: relative; z-index: 1; }
.thankyou-header__title {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 900;
    text-transform: uppercase;
    color: var(--white);
    letter-spacing: 0.02em;
    margin: 0;
}
.thankyou-card {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 48px;
    max-width: 640px;
    margin: -40px auto 60px;
    position: relative;
    z-index: 2;
}
.thankyou-card h2 {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 28px;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 12px;
}
.thankyou-card p {
    font-family: var(--font-body);
    color: var(--mid);
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 12px;
}
.thankyou-card ul {
    font-family: var(--font-body);
    color: var(--mid);
    font-size: 14px;
    margin-bottom: 8px;
    padding-left: 20px;
}
.thankyou-card li {
    margin-bottom: 4px;
}
.btn-back {
    display: inline-block;
    margin-top: 24px;
    padding: 12px 28px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
}
.btn-back:hover {
    background: var(--navy-mid);
    color: var(--white);
}
</style>

<div class="thankyou-header">
    <div class="container">
        <h1 class="thankyou-header__title">Pesan Terkirim</h1>
    </div>
</div>

<div class="thankyou-card">
    <h2>Pesan Anda Telah Dikirim</h2>
    <p>Terima kasih telah menghubungi Jakarta CSIRT. Kami telah menerima pesan Anda dan akan merespons sesegera mungkin.</p>
    <p><strong style="color: var(--ink);">Waktu Respons:</strong></p>
    <ul>
        <li>Insiden mendesak: 1-2 jam</li>
        <li>Pertanyaan umum: 1-2 hari kerja</li>
        <li>Permintaan kerja sama: 2-5 hari kerja</li>
    </ul>
    <a href="{{ route('home') }}" class="btn-back">Kembali ke Beranda</a>
</div>
@endsection
