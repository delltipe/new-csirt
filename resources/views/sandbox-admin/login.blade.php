@extends('layouts.app')

@section('content')
<style>
.sandbox-login-wrap {
    max-width: 480px;
    margin: 60px auto;
    padding: 0 20px;
}

.sandbox-login-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 4px solid var(--navy);
    padding: 36px 32px;
}

.sandbox-login-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--navy-tint);
    color: var(--navy);
    border: 1px solid var(--navy);
    font-family: var(--font-body);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 4px 10px;
    margin-bottom: 16px;
}

.sandbox-login-title {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0 0 8px;
    line-height: 1.2;
}

.sandbox-login-sub {
    font-size: 13.5px;
    color: var(--mid);
    line-height: 1.6;
    margin-bottom: 24px;
}

.sandbox-login-notice {
    background: var(--mist);
    border-left: 3px solid var(--navy);
    padding: 12px 14px;
    font-size: 12.5px;
    color: var(--ink);
    line-height: 1.55;
    margin-bottom: 24px;
}

.sandbox-alert-error {
    background: var(--alert-bg);
    border: 1px solid var(--alert);
    color: var(--alert-dark);
    padding: 12px 14px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 20px;
    line-height: 1.5;
}

.sandbox-form-group {
    margin-bottom: 20px;
}

.sandbox-label {
    display: block;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 6px;
}

.sandbox-input {
    width: 100%;
    height: 42px;
    border: 1px solid var(--border);
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 14px;
    padding: 0 12px;
    outline: none;
    box-sizing: border-box;
    transition: border-color var(--ease);
}

.sandbox-input:focus {
    border-color: var(--navy);
}

.sandbox-btn-submit {
    width: 100%;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 13.5px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 13px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background var(--ease);
    margin-top: 8px;
}

.sandbox-btn-submit:hover {
    background: var(--navy-dim);
}

.sandbox-login-footer {
    text-align: center;
    margin-top: 24px;
}

.sandbox-login-footer a {
    color: var(--navy);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.sandbox-login-footer a:hover {
    text-decoration: underline;
}
</style>

<div class="sandbox-login-wrap">
    <div class="sandbox-login-card">
        <span class="sandbox-login-badge">
            <i class="bi bi-shield-lock-fill" aria-hidden="true"></i> Mode Pratinjau Terbatas
        </span>

        <h1 class="sandbox-login-title">Administrator Sandbox</h1>
        <p class="sandbox-login-sub">
            Masuk untuk meninjau dan memperbarui status laporan insiden pengujian (PostgreSQL).
        </p>

        <div class="sandbox-login-notice">
            <i class="bi bi-info-circle" aria-hidden="true"></i>
            Akses ini khusus pengujian demo pratinjau publik. Autentikasi dikonfigurasi melalui variabel lingkungan aman dan terpisah dari autentikasi pengguna biasa.
        </div>

        @if ($errors->any())
            <div class="sandbox-alert-error" role="alert">
                <i class="bi bi-exclamation-triangle-fill" aria-hidden="true" style="margin-right: 4px;"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('sandbox-admin.login.submit') }}">
            @csrf

            <div class="sandbox-form-group">
                <label for="username" class="sandbox-label">Nama Pengguna Administrator</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="sandbox-input"
                    value="{{ old('username') }}"
                    required
                    autocomplete="username"
                    autofocus
                >
            </div>

            <div class="sandbox-form-group">
                <label for="password" class="sandbox-label">Kata Sandi Administrator</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="sandbox-input"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="sandbox-btn-submit">
                Masuk ke Panel Sandbox <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </button>
        </form>

        <div class="sandbox-login-footer">
            <a href="{{ route('home') }}">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
