@extends('layouts.app')

@section('content')
<style>
.login-page {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    padding: 60px 20px;
    background: var(--mist);
}

.login-layout {
    max-width: 960px;
    margin: 0 auto;
    width: 100%;
    display: grid;
    grid-template-columns: 460px 1fr;
    gap: 32px;
    align-items: start;
}

.login-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 4px solid var(--navy);
    padding: 0;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
}

.login-header {
    background: var(--navy-dim);
    padding: 32px 36px;
    border-bottom: 3px solid var(--navy);
}

.login-title {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    margin: 0;
}

.login-sub {
    font-size: 13px;
    font-weight: 300;
    color: rgba(255,255,255,0.7);
    margin-top: 6px;
    line-height: 1.5;
}

.login-body {
    padding: 36px;
}

.alert-error {
    background: var(--alert-bg);
    border: 1px solid var(--alert);
    color: var(--alert-dark);
    padding: 14px 16px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
}

.form-group {
    margin-bottom: 22px;
}

.form-label {
    font-family: var(--font-display);
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 8px;
    display: block;
}

.form-label .req { color: var(--alert); margin-left: 2px; }

.form-input {
    width: 100%;
    height: 46px;
    padding: 0 14px;
    border: 1px solid var(--border);
    background: var(--white);
    font-family: var(--font-body);
    font-size: 14px;
    color: var(--ink);
    transition: border-color var(--ease);
}

.form-input:focus {
    outline: none;
    border-color: var(--navy);
}

.form-input.is-invalid {
    border-color: var(--alert);
}

.field-error {
    font-size: 12px;
    color: var(--alert);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.btn-submit {
    width: 100%;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 14px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
}

.btn-submit:hover {
    background: var(--navy-dim);
}

.login-footer {
    margin-top: 22px;
    text-align: center;
    font-size: 13.5px;
    color: var(--mid);
}

.login-footer a {
    color: var(--navy);
    font-weight: 700;
    text-decoration: underline;
}

/* Sidebar Context / Ticket Tracking Callout */
.login-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.track-callout-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-left: 4px solid var(--navy);
    padding: 24px 28px;
}
.track-callout-card h2 {
    font-family: var(--font-display);
    font-size: 17px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.track-callout-card h2 i { color: var(--navy); }
.track-callout-card p {
    font-size: 13.5px;
    color: var(--mid);
    line-height: 1.6;
    margin-bottom: 16px;
}
.btn-track-public {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--white);
    color: var(--navy);
    border: 2px solid var(--navy);
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    padding: 10px 20px;
    text-decoration: none;
    transition: background var(--ease), color var(--ease);
}
.btn-track-public:hover {
    background: var(--navy-tint);
    color: var(--navy);
}

.workflow-card {
    background: var(--white);
    border: 1px solid var(--border);
    padding: 24px 28px;
}
.workflow-card h3 {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--border);
}
.workflow-steps {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.workflow-step-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}
.workflow-step-item__num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background: var(--navy-tint);
    color: var(--navy);
    border: 1px solid var(--navy);
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 800;
    flex-shrink: 0;
}
.workflow-step-item__text strong {
    display: block;
    font-size: 13px;
    color: var(--ink);
    margin-bottom: 2px;
}
.workflow-step-item__text span {
    font-size: 12px;
    color: var(--mid);
    line-height: 1.5;
    display: block;
}

@media (max-width: 860px) {
    .login-layout {
        grid-template-columns: 1fr;
    }
}

/* Dark Mode Overrides (D3-04: Boundary Contrast >= 3:1) */
html.accessibility-contrast-dark .login-page .form-input {
    background: #0F0F0F !important;
    border-color: #666666 !important;
    color: #FFFFFF !important;
}
html.accessibility-contrast-dark .login-page .form-input:focus {
    border-color: #4DA6FF !important;
}

/* High-Contrast Mode Overrides (D3-05: Visible Focus Ring >= 3:1) */
html.accessibility-contrast-high .login-page .form-input {
    border: 2px solid #000000 !important;
    background: #FFFFFF !important;
    color: #000000 !important;
}
html.accessibility-contrast-high .login-page .form-input:focus {
    outline: 3px solid #000000 !important;
    outline-offset: 2px;
    box-shadow: 0 0 0 2px #FFFFFF !important;
    border-color: #000000 !important;
}
</style>

<div class="login-page">
    <div class="login-layout">

        {{-- Login Card --}}
        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">Masuk Akun</h1>
                <p class="login-sub">Portal Resmi Pelaporan Insiden Siber JakartaProv-CSIRT</p>
            </div>
            <div class="login-body">
                @if($errors->any())
                    <div class="alert-error" role="alert">
                        <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email <span class="req" aria-hidden="true">*</span></label>
                        <input type="email" class="form-input @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required autofocus
                               aria-describedby="@error('email') err-email @enderror"
                               @error('email') aria-invalid="true" @enderror>
                        @error('email')
                        <div class="field-error" id="err-email" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi <span class="req" aria-hidden="true">*</span></label>
                        <input type="password" class="form-input @error('password') is-invalid @enderror"
                               id="password" name="password" required
                               aria-describedby="@error('password') err-password @enderror"
                               @error('password') aria-invalid="true" @enderror>
                        @error('password')
                        <div class="field-error" id="err-password" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn-submit">Masuk ke Portal</button>
                </form>

                <p class="login-footer">
                    Belum memiliki akun pelapor? <a href="{{ route('register') }}">Daftar di sini</a>
                </p>
            </div>
        </div>

        {{-- Sidebar Context --}}
        <div class="login-sidebar">

            {{-- Direct Public Ticket Tracking Callout --}}
            <div class="track-callout-card">
                <h2><i class="bi bi-search" aria-hidden="true"></i> Sudah Punya Nomor Tiket?</h2>
                <p>
                    Anda <strong>tidak perlu masuk</strong> untuk mengecek progres penanganan insiden. Masukkan nomor tiket resmi Anda (contoh: <code>INS-2026-0001</code>) untuk melihat status terkini secara instan.
                </p>
                <a href="{{ route('ticket.track') }}" class="btn-track-public">
                    Lacak Status Tiket <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            {{-- 4-Step Disclosure Workflow --}}
            <div class="workflow-card">
                <h3>Alur Pelaporan &amp; Penanganan Insiden</h3>
                <ol class="workflow-steps">
                    <li class="workflow-step-item">
                        <div class="workflow-step-item__num">1</div>
                        <div class="workflow-step-item__text">
                            <strong>Daftar / Masuk Akun Pelapor</strong>
                            <span>Identitas terverifikasi melindungi pelapor siber (responsible disclosure) dan mencegah spam data palsu.</span>
                        </div>
                    </li>
                    <li class="workflow-step-item">
                        <div class="workflow-step-item__num">2</div>
                        <div class="workflow-step-item__text">
                            <strong>Persetujuan Syarat &amp; Ketentuan (TaC)</strong>
                            <span>Komitmen integritas bahwa temuan dilaporkan untuk tujuan mitigasi, bukan eksploitasi merugikan.</span>
                        </div>
                    </li>
                    <li class="workflow-step-item">
                        <div class="workflow-step-item__num">3</div>
                        <div class="workflow-step-item__text">
                            <strong>Kirim Laporan &amp; Bukti Kerentanan</strong>
                            <span>Formulir terstruktur standar Komdigi dengan unggah bukti file atau URL (maks 3 berkas).</span>
                        </div>
                    </li>
                    <li class="workflow-step-item">
                        <div class="workflow-step-item__num">4</div>
                        <div class="workflow-step-item__text">
                            <strong>Terbitkan Nomor Tiket &amp; Penanganan</strong>
                            <span>Dapatkan nomor tiket resmi untuk memantau tahapan validasi, tindak lanjut, hingga pemulihan.</span>
                        </div>
                    </li>
                </ol>
            </div>

        </div>

    </div>
</div>
@endsection
