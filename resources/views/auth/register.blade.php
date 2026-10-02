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
    min-height: 46px;
    height: auto;
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

.password-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.password-input-group .form-input {
    padding-right: 46px;
}

.btn-toggle-password {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 44px;
    background: transparent;
    border: none;
    color: var(--mid);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 16px;
    transition: color var(--ease);
}

.btn-toggle-password:hover {
    color: var(--ink);
}

.btn-toggle-password:focus-visible {
    outline: 2px solid var(--navy);
    outline-offset: -2px;
}

.field-hint {
    font-size: 12px;
    color: var(--mid);
    margin-top: 4px;
    line-height: 1.4;
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

@media (max-width: 640px) {
    .login-body {
        padding: 24px 20px;
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
html.accessibility-contrast-dark .login-page .btn-toggle-password {
    color: #B0BCC9;
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
html.accessibility-contrast-high .login-page .btn-toggle-password {
    color: #000000 !important;
}
</style>

<div class="login-page">
    <div class="login-layout">

        {{-- Register Card --}}
        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">Daftar Akun</h1>
                <p class="login-sub">Bergabung sebagai pelapor insiden siber JakartaProv-CSIRT</p>
            </div>
            <div class="login-body">
                @if($errors->any())
                    <div class="alert-error" role="alert">
                        <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap <span class="req" aria-hidden="true">*</span></label>
                        <input type="text" class="form-input @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required autofocus
                               aria-describedby="@error('name') err-name @enderror"
                               @error('name') aria-invalid="true" @enderror>
                        @error('name')
                        <div class="field-error" id="err-name" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email <span class="req" aria-hidden="true">*</span></label>
                        <input type="email" class="form-input @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required
                               aria-describedby="@error('email') err-email @enderror"
                               @error('email') aria-invalid="true" @enderror>
                        @error('email')
                        <div class="field-error" id="err-email" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi <span class="req" aria-hidden="true">*</span></label>
                        <div class="password-input-group">
                            <input type="password" class="form-input @error('password') is-invalid @enderror"
                                   id="password" name="password" required
                                   aria-describedby="hint-password @error('password') err-password @enderror"
                                   @error('password') aria-invalid="true" @enderror>
                            <button type="button" class="btn-toggle-password" id="btn-toggle-password"
                                    aria-label="Tampilkan kata sandi" aria-pressed="false"
                                    data-target="password">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="field-hint" id="hint-password">Minimal 8 karakter.</div>
                        @error('password')
                        <div class="field-error" id="err-password" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Ulangi Kata Sandi <span class="req" aria-hidden="true">*</span></label>
                        <div class="password-input-group">
                            <input type="password" class="form-input @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation" name="password_confirmation" required
                                   aria-describedby="@error('password_confirmation') err-password-confirmation @enderror"
                                   @error('password_confirmation') aria-invalid="true" @enderror>
                            <button type="button" class="btn-toggle-password" id="btn-toggle-password-confirmation"
                                    aria-label="Tampilkan ulangi kata sandi" aria-pressed="false"
                                    data-target="password_confirmation">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                        <div class="field-error" id="err-password-confirmation" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">Daftar Akun Pelapor</button>
                </form>

                <p class="login-footer">
                    Sudah punya akun pelapor? <a href="{{ route('login') }}">Masuk di sini</a>
                </p>
            </div>
        </div>

        {{-- Sidebar Context --}}
        <div class="login-sidebar">

            {{-- Direct Public Ticket Tracking Callout --}}
            <div class="track-callout-card">
                <h2><i class="bi bi-search" aria-hidden="true"></i> Sudah Punya Nomor Tiket?</h2>
                <p>
                    Anda <strong>tidak perlu mendaftar atau masuk</strong> untuk mengecek progres penanganan insiden. Masukkan nomor tiket resmi Anda (contoh: <code>INS-2026-0001</code>) untuk melihat status terkini secara instan.
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

<script>
(function () {
    const toggles = document.querySelectorAll('.btn-toggle-password');
    toggles.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            this.setAttribute('aria-pressed', isPassword ? 'true' : 'false');
            this.setAttribute('aria-label', isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            }
        });
    });
})();
</script>
@endsection
