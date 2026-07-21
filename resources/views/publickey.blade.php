@extends('layouts.app')

@section('content')
<style>
.placeholder-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
}
.placeholder-header::before {
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
.placeholder-header .container { position: relative; z-index: 1; }
.placeholder-header__title {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 900;
    text-transform: uppercase;
    color: var(--white);
    letter-spacing: 0.02em;
    margin: 0;
}
.placeholder-header__subtitle {
    font-family: var(--font-body);
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
    margin-top: 8px;
}
.placeholder-card {
    background: var(--white);
    border: 2px solid var(--border);
    padding: 60px 48px;
    max-width: 720px;
    margin: -40px auto 60px;
    position: relative;
    z-index: 2;
    text-align: center;
}
.placeholder-card p {
    font-family: var(--font-body);
    color: var(--mid);
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 20px;
}
.btn-download {
    display: inline-block;
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
.btn-download:hover {
    background: var(--navy-mid);
    color: var(--white);
}
</style>

<div class="placeholder-header">
    <div class="container">
        <h1 class="placeholder-header__title">Public Key CSIRT</h1>
        <p class="placeholder-header__subtitle">Kriptografi dan verifikasi email</p>
    </div>
</div>

<div class="placeholder-card">
    <p>Public key email csirt@jakarta.go.id belum tersedia untuk diunduh. Silakan hubungi admin untuk informasi lebih lanjut.</p>
    <a href="#" class="btn-download">Unduh Public Key</a>
</div>
@endsection
