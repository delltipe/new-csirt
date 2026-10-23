@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER BAND (Matches Lapor Insiden)
   ============================================================ */
.lapor-header {
    background: var(--ink, #0A0F1A);
    padding: 48px 0 40px;
    position: relative;
    overflow: hidden;
}
.lapor-header::before {
    display: none;
}
.lapor-header .container { position: relative; z-index: 1; }
.lapor-header__eyebrow {
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #D6E4F8;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.lapor-header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 52px);
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}
.lapor-header__sub {
    font-family: var(--font-body);
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
    max-width: 540px;
}

/* ============================================================
   LAYOUT & FORM ELEMENTS
   ============================================================ */
.lapor-layout {
    padding: 48px 0 80px;
    background: var(--mist, #F4F5F7);
}

.form-card {
    background: var(--white);
    border: 1px solid var(--border, #D8DCE3);
    border-top: 4px solid var(--navy, #003580);
    padding: 36px 32px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.form-step__title {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink, #0A0F1A);
    margin-bottom: 6px;
    line-height: 1;
}
.form-step__divider {
    height: 2px;
    background: var(--border, #D8DCE3);
    margin: 16px 0 28px;
}

.lapor-label {
    display: block;
    font-family: var(--font-body);
    font-size: 12.5px;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink, #0A0F1A);
    margin-bottom: 6px;
}
.lapor-label .req { color: var(--alert); margin-left: 2px; }

.lapor-input,
.lapor-select,
.lapor-textarea {
    width: 100%;
    height: 44px;
    border: 1px solid var(--border, #D8DCE3);
    background: var(--white);
    color: var(--ink, #0A0F1A);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 400;
    padding: 0 14px;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    border-radius: 0;
    appearance: none;
}
.lapor-textarea {
    height: auto;
    padding: 12px 14px;
    resize: vertical;
}
.lapor-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236B7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
}
.lapor-input:focus,
.lapor-select:focus,
.lapor-textarea:focus {
    border-color: var(--navy, #003580);
    box-shadow: 0 0 0 3px rgba(0, 53, 128, 0.1);
}
.lapor-input.is-invalid,
.lapor-select.is-invalid,
.lapor-textarea.is-invalid {
    border-color: var(--alert);
    box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
}

.field-error {
    font-size: 12px;
    color: var(--alert);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-field { margin-bottom: 20px; }
.form-field:last-child { margin-bottom: 0; }

.step-nav {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding-top: 24px;
    border-top: 1px solid var(--border, #D8DCE3);
    margin-top: 28px;
    gap: 16px;
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--navy, #003580);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 15px 36px;
    border: none;
    border-radius: 0;
    cursor: pointer;
    transition: background 0.2s ease;
}
.btn-submit:hover { background: var(--navy-dim); color: var(--white); }

.btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    color: var(--mid);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 600;
    padding: 15px 20px;
    border: 1px solid var(--border, #D8DCE3);
    border-radius: 0;
    text-decoration: none;
    cursor: pointer;
    transition: color 0.2s ease, border-color 0.2s ease;
}
.btn-ghost:hover { color: var(--ink, #0A0F1A); border-color: #9ca3af; }

/* ============================================================
   DEMO SIMULATION COMPONENTS
   ============================================================ */
.demo-warning-banner {
    background: var(--navy-tint, #E8EFF8);
    border: 1px solid var(--border, #D8DCE3);
    border-left: 4px solid var(--navy, #003580);
    padding: 20px 24px;
    margin-bottom: 28px;
}
.demo-warning-banner__header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}
.demo-warning-banner__icon {
    font-size: 20px;
    color: var(--navy, #003580);
    flex-shrink: 0;
}
.demo-warning-banner__title {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink, #0A0F1A);
    margin: 0;
}
.demo-warning-banner__intro {
    font-family: var(--font-body);
    font-size: 13.5px;
    color: var(--ink, #0A0F1A);
    margin-bottom: 10px;
    line-height: 1.5;
}
.demo-warning-banner__list {
    margin: 0;
    padding-left: 20px;
    font-family: var(--font-body);
    font-size: 13px;
    color: var(--ink, #0A0F1A);
    line-height: 1.6;
}
.demo-warning-banner__list li {
    margin-bottom: 6px;
}
.demo-warning-banner__list li:last-child {
    margin-bottom: 0;
}

.demo-noscript-notice {
    background: var(--alert-bg, #FEF2F2);
    border: 1px solid var(--alert, #B91C1C);
    border-left: 4px solid var(--alert, #B91C1C);
    color: var(--alert-dark, #991B1B);
    padding: 14px 18px;
    font-size: 13.5px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.demo-server-error {
    background: var(--alert-bg, #FEF2F2);
    border: 1px solid var(--alert, #B91C1C);
    border-left: 4px solid var(--alert, #B91C1C);
    color: var(--alert-dark, #991B1B);
    padding: 14px 18px;
    font-size: 13.5px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.demo-success-alert {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    border-left: 4px solid #059669;
    padding: 18px 22px;
    margin-bottom: 24px;
}
.demo-success-alert__inner {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    position: relative;
}
.demo-success-alert__icon {
    font-size: 24px;
    color: #059669;
    flex-shrink: 0;
    line-height: 1;
    margin-top: 2px;
}
.demo-success-alert__content {
    flex: 1;
}
.demo-success-alert__title {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #065F46;
    margin: 0 0 6px;
}
.demo-success-alert__text {
    font-family: var(--font-body);
    font-size: 14px;
    color: #064E3B;
    margin: 0 0 6px;
    line-height: 1.5;
}
.demo-success-alert__hint {
    font-family: var(--font-body);
    font-size: 12.5px;
    color: #047857;
    display: block;
}
.demo-success-alert__close {
    padding: 6px;
    background-size: 10px;
}

/* Modal styles for Review & Completion */
.demo-modal-content {
    border-radius: 0;
    border: 1px solid var(--border, #D8DCE3);
    box-shadow: 0 12px 36px rgba(0,0,0,0.18);
}
.demo-modal-header {
    border-bottom: 1px solid var(--border, #D8DCE3);
    border-radius: 0;
    padding: 20px 24px;
    background: var(--white, #FFFFFF);
}
.demo-badge {
    display: inline-block;
    font-family: var(--font-body);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 3px 8px;
    background: var(--navy-tint, #E8EFF8);
    color: var(--navy, #003580);
    border: 1px solid var(--border, #D8DCE3);
    margin-bottom: 6px;
}
.demo-badge--success {
    background: #D1FAE5;
    color: #065F46;
    border-color: #A7F3D0;
}
.demo-modal-title {
    font-family: var(--font-display);
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink, #0A0F1A);
    margin: 0;
}
.demo-modal-body {
    padding: 24px;
    background: var(--white, #FFFFFF);
}
.demo-modal-footer {
    border-top: 1px solid var(--border, #D8DCE3);
    border-radius: 0;
    padding: 16px 24px;
    background: var(--mist, #F4F5F7);
    gap: 12px;
}
.demo-review-notice {
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    border-left: 3px solid var(--navy, #003580);
    padding: 12px 16px;
    font-size: 13px;
    color: var(--ink, #0A0F1A);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}
.demo-review-table {
    border: 1px solid var(--border, #D8DCE3);
    background: var(--white, #FFFFFF);
    margin-bottom: 16px;
}
.demo-review-row {
    display: grid;
    grid-template-columns: 180px 1fr;
    padding: 10px 16px;
    border-bottom: 1px solid var(--border, #D8DCE3);
    font-size: 13.5px;
    align-items: baseline;
}
.demo-review-row:last-child {
    border-bottom: none;
}
.demo-review-row--full {
    display: block;
}
.demo-review-row--full .demo-review-label {
    display: block;
    margin-bottom: 6px;
}
.demo-review-label {
    font-weight: 600;
    color: var(--mid, #5B6472);
    text-transform: uppercase;
    font-size: 11.5px;
    letter-spacing: 0.04em;
}
.demo-review-value {
    color: var(--ink, #0A0F1A);
    word-break: break-word;
}
.demo-review-message {
    white-space: pre-wrap;
    max-height: 180px;
    overflow-y: auto;
    padding: 10px 14px;
    background: var(--mist, #F4F5F7);
    border: 1px solid var(--border, #D8DCE3);
    font-family: var(--font-body);
    font-size: 13.5px;
    line-height: 1.5;
}
.demo-captcha-disclaimer {
    font-size: 12px;
    color: var(--mid, #5B6472);
    padding: 10px 14px;
    background: var(--mist, #F4F5F7);
    border: 1px dashed var(--border, #D8DCE3);
}

.demo-success-icon {
    font-size: 48px;
    color: #059669;
    line-height: 1;
}
.demo-success-title {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    color: var(--ink, #0A0F1A);
}
.demo-success-text {
    font-size: 14px;
    color: var(--mid, #5B6472);
    max-width: 480px;
    margin: 0 auto;
    line-height: 1.5;
}
.demo-security-pill {
    display: inline-block;
    background: var(--mist, #F4F5F7);
    border: 1px solid var(--border, #D8DCE3);
    font-size: 12px;
    color: var(--mid, #5B6472);
    padding: 8px 14px;
    text-align: left;
}

/* High contrast and dark mode overrides */
html.accessibility-contrast-dark .demo-warning-banner {
    background: #0F1B33;
    border-color: #1E3A8A;
    border-left-color: #60A5FA;
}
html.accessibility-contrast-dark .demo-warning-banner__title,
html.accessibility-contrast-dark .demo-warning-banner__intro,
html.accessibility-contrast-dark .demo-warning-banner__list {
    color: #E2E8F0;
}
html.accessibility-contrast-dark .demo-warning-banner__icon {
    color: #60A5FA;
}
html.accessibility-contrast-dark .demo-review-notice {
    background: #1E293B;
    border-color: #334155;
    border-left-color: #60A5FA;
    color: #E2E8F0;
}
html.accessibility-contrast-dark .demo-review-table,
html.accessibility-contrast-dark .demo-modal-body,
html.accessibility-contrast-dark .demo-modal-header {
    background: #111827;
    border-color: #374151;
}
html.accessibility-contrast-dark .demo-modal-footer {
    background: #1F2937;
    border-color: #374151;
}
html.accessibility-contrast-dark .demo-review-row {
    border-color: #374151;
}
html.accessibility-contrast-dark .demo-review-label {
    color: #9CA3AF;
}
html.accessibility-contrast-dark .demo-review-value {
    color: #F9FAFB;
}
html.accessibility-contrast-dark .demo-review-message,
html.accessibility-contrast-dark .demo-captcha-disclaimer,
html.accessibility-contrast-dark .demo-security-pill {
    background: #1F2937;
    border-color: #374151;
    color: #E5E7EB;
}
html.accessibility-contrast-dark .demo-modal-title,
html.accessibility-contrast-dark .demo-success-title {
    color: #FFFFFF;
}
html.accessibility-contrast-dark .demo-success-alert {
    background: #064E3B;
    border-color: #059669;
    border-left-color: #34D399;
}
html.accessibility-contrast-dark .demo-success-alert__title {
    color: #A7F3D0;
}
html.accessibility-contrast-dark .demo-success-alert__text,
html.accessibility-contrast-dark .demo-success-alert__hint {
    color: #D1FAE5;
}
html.accessibility-contrast-dark .demo-success-alert__icon {
    color: #34D399;
}

@media (max-width: 991px) {
    .form-row { grid-template-columns: 1fr; }
    .form-card { padding: 24px 16px; }
}

@media (max-width: 600px) {
    .demo-review-row {
        grid-template-columns: 1fr;
        gap: 4px;
    }
}

/* ============================================================
   SIDEBAR (Contact Details)
   ============================================================ */
.sidebar-card {
    background: var(--white);
    border: 1px solid var(--border, #D8DCE3);
    border-top: 3px solid var(--navy, #003580);
    padding: 32px 24px;
    margin-bottom: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
}
.contact-item:last-child { margin-bottom: 0; }
.contact-icon {
    width: 48px;
    height: 48px;
    background: var(--mist, #F4F5F7);
    border: 1px solid var(--border, #D8DCE3);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.contact-icon i {
    font-size: 20px;
    color: var(--navy, #003580);
}
.contact-text h4 {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--ink, #0A0F1A);
    margin-bottom: 4px;
}
.contact-text p {
    font-family: var(--font-body);
    font-size: 14px;
    color: var(--mid);
    margin: 0;
    line-height: 1.5;
}
.contact-text a {
    color: var(--navy, #003580);
    text-decoration: underline;
    text-underline-offset: 2px;
    transition: color 0.2s ease;
}
.contact-text a:hover { color: var(--navy-dim); }

/* ============================================================
   MAP WIDGET (Lokasi Kami)
   ============================================================ */
.contact-map {
    border: 1px solid var(--border, #D8DCE3);
}
.contact-map iframe {
    display: block;
    width: 100%;
    height: 320px;
    border: 0;
    border-radius: 0;
    background: var(--mist, #F4F5F7);
}
</style>

<div class="lapor-header">
    <div class="container">
        <div class="lapor-header__eyebrow">
            <i class="bi bi-headset" aria-hidden="true"></i>
            Pusat Bantuan
        </div>
        <h1>Hubungi Kami</h1>
        <p class="lapor-header__sub">
            Hubungi JakartaProv-CSIRT untuk pertanyaan umum, dukungan teknis, kemitraan, atau keperluan media.
        </p>
    </div>
</div>

<section class="lapor-layout">
    <div class="container">
        <div class="row g-5">
            
            {{-- =================================================== 
                 KIRI: FORMULIR PESAN (col-lg-8)
                 =================================================== --}}
            <div class="col-lg-8">
                <form action="{{ route('contact.store') }}" method="POST" class="form-card" id="contactForm" novalidate>
                    @csrf
                    <h2 class="form-step__title">Kirim Pesan</h2>
                    <div class="form-step__divider"></div>

                    {{-- Server Error Alert (e.g. if JavaScript was disabled and request was submitted to server) --}}
                    @if ($errors->has('pesan'))
                        <div class="demo-server-error" role="alert">
                            <i class="bi bi-shield-lock-fill" aria-hidden="true"></i>
                            <div>{{ $errors->first('pesan') }}</div>
                        </div>
                    @endif

                    {{-- JavaScript Disabled Warning --}}
                    <noscript>
                        <div class="demo-noscript-notice" role="alert">
                            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                            <div>
                                <strong>JavaScript dinonaktifkan:</strong> Mode simulasi interaktif kontak memerlukan JavaScript aktif pada peramban. Pengiriman data ke server dinonaktifkan pada pratinjau publik ini.
                            </div>
                        </div>
                    </noscript>

                    {{-- Demonstration Warning Banner (Section 4.A) --}}
                    <div class="demo-warning-banner" role="region" aria-label="Pemberitahuan Mode Simulasi">
                        <div class="demo-warning-banner__header">
                            <i class="bi bi-shield-exclamation demo-warning-banner__icon" aria-hidden="true"></i>
                            <h3 class="demo-warning-banner__title">Mode Simulasi Pratinjau Publik</h3>
                        </div>
                        <div class="demo-warning-banner__intro">
                            Halaman formulir ini beroperasi dalam <strong>mode simulasi</strong> untuk demonstrasi antarmuka publik:
                        </div>
                        <ul class="demo-warning-banner__list">
                            <li><strong>Tidak ada pesan yang dikirim atau disimpan:</strong> Formulir ini hanya mendemonstrasikan alur validasi pada peramban Anda. Tidak ada data yang dikirimkan ke server atau dicatat ke basis data.</li>
                            <li><strong>Wajib gunakan informasi fiktif:</strong> Silakan gunakan nama, email, dan pesan contoh saat mencoba formulir ini.</li>
                            <li><strong>Dilarang memasukkan data sensitif:</strong> Jangan memasukkan rincian insiden siber nyata, bukti kerentanan/eksploitasi, kata sandi, atau data pribadi sensitif. Untuk pelaporan insiden darurat resmi Pemprov DKI Jakarta, hubungi saluran kontak resmi pada panel sebelah kanan.</li>
                        </ul>
                    </div>

                    {{-- In-Page Simulated Completion Alert (Section 4.D) --}}
                    <div id="demoSuccessAlert" class="demo-success-alert" style="display:none;" tabindex="-1" role="status" aria-live="polite">
                        <div class="demo-success-alert__inner">
                            <i class="bi bi-check-circle-fill demo-success-alert__icon" aria-hidden="true"></i>
                            <div class="demo-success-alert__content">
                                <h4 class="demo-success-alert__title">Simulasi Berhasil</h4>
                                <p class="demo-success-alert__text">
                                    Simulasi berhasil. Form berhasil melewati validasi yang tersedia dalam mode demo. Pesan Anda tidak dikirim atau disimpan.
                                </p>
                                <span class="demo-success-alert__hint">
                                    Semua isian formulir telah dibersihkan secara lokal. Tidak ada data yang dikirim ke server, dicatat ke log, atau disimpan ke penyimpanan peramban.
                                </span>
                            </div>
                            <button type="button" class="btn-close demo-success-alert__close" id="btnCloseSuccessAlert" aria-label="Tutup notifikasi keberhasilan"></button>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="name" class="lapor-label">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" id="name" name="name" class="lapor-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Nama Anda">
                            @error('name') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="form-field">
                            <label for="email" class="lapor-label">Email <span class="req">*</span></label>
                            <input type="email" id="email" name="email" class="lapor-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="nama@domain.com">
                            @error('email') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="phone" class="lapor-label">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" class="lapor-input @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                            @error('phone') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>

                        <div class="form-field">
                            <label for="organization" class="lapor-label">Organisasi/Perusahaan</label>
                            <input type="text" id="organization" name="organization" class="lapor-input @error('organization') is-invalid @enderror" value="{{ old('organization') }}" placeholder="Asal instansi">
                            @error('organization') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="inquiry_type" class="lapor-label">Tipe Pertanyaan <span class="req">*</span></label>
                        <select id="inquiry_type" name="inquiry_type" class="lapor-select lapor-input @error('inquiry_type') is-invalid @enderror" required>
                            <option value="" disabled {{ old('inquiry_type') ? '' : 'selected' }}>-- Pilih Tipe Pertanyaan --</option>
                            <option value="general" {{ old('inquiry_type') == 'general' ? 'selected' : '' }}>Informasi Umum</option>
                            <option value="support" {{ old('inquiry_type') == 'support' ? 'selected' : '' }}>Dukungan Teknis</option>
                            <option value="partnership" {{ old('inquiry_type') == 'partnership' ? 'selected' : '' }}>Kemitraan</option>
                            <option value="media" {{ old('inquiry_type') == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="other" {{ old('inquiry_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('inquiry_type') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>

                    <div class="form-field">
                        <label for="subject" class="lapor-label">Subjek <span class="req">*</span></label>
                        <input type="text" id="subject" name="subject" class="lapor-input @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required placeholder="Perihal pesan">
                        @error('subject') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>

                    <div class="form-field">
                        <label for="message" class="lapor-label">Pesan <span class="req">*</span></label>
                        <textarea id="message" name="message" class="lapor-textarea @error('message') is-invalid @enderror" rows="5" required placeholder="Tuliskan detail pertanyaan atau pesan Anda di sini...">{{ old('message') }}</textarea>
                        @error('message') <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div> @enderror
                    </div>

                    @include('components.captcha', ['question' => $captchaQuestion])

                    <div class="step-nav">
                        <a href="{{ route('home') }}" class="btn-ghost">Batalkan</a>
                        <button type="submit" class="btn-submit" id="btnSubmitContact">
                            Kirim Pesan (Mode Demo) <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </form>

                {{-- ===================================================
                     MODAL PRATINJAU PESAN (Review Step - Section 4.C)
                     =================================================== --}}
                <div class="modal fade" id="demoReviewModal" tabindex="-1" aria-labelledby="demoReviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content demo-modal-content">
                            <!-- REVIEW STEP PANE -->
                            <div id="modalReviewPane">
                                <div class="modal-header demo-modal-header">
                                    <div>
                                        <span class="demo-badge">Mode Simulasi</span>
                                        <h4 class="modal-title demo-modal-title" id="demoReviewModalLabel">Pratinjau Pesan Kontak</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup pratinjau"></button>
                                </div>
                                <div class="modal-body demo-modal-body">
                                    <div class="demo-review-notice" role="note">
                                        <i class="bi bi-info-circle-fill me-2" aria-hidden="true"></i>
                                        <span>Ini adalah pratinjau lokal dari formulir yang Anda isi. Pesan ini <strong>tidak akan dikirim atau disimpan</strong> ke server.</span>
                                    </div>

                                    <div class="demo-review-table">
                                        <div class="demo-review-row">
                                            <span class="demo-review-label">Nama Lengkap</span>
                                            <span class="demo-review-value" id="reviewName"></span>
                                        </div>
                                        <div class="demo-review-row">
                                            <span class="demo-review-label">Email</span>
                                            <span class="demo-review-value" id="reviewEmail"></span>
                                        </div>
                                        <div class="demo-review-row">
                                            <span class="demo-review-label">Nomor Telepon</span>
                                            <span class="demo-review-value" id="reviewPhone"></span>
                                        </div>
                                        <div class="demo-review-row">
                                            <span class="demo-review-label">Organisasi / Instansi</span>
                                            <span class="demo-review-value" id="reviewOrganization"></span>
                                        </div>
                                        <div class="demo-review-row">
                                            <span class="demo-review-label">Tipe Pertanyaan</span>
                                            <span class="demo-review-value" id="reviewInquiryType"></span>
                                        </div>
                                        <div class="demo-review-row">
                                            <span class="demo-review-label">Subjek</span>
                                            <span class="demo-review-value" id="reviewSubject"></span>
                                        </div>
                                        <div class="demo-review-row demo-review-row--full">
                                            <span class="demo-review-label">Isi Pesan</span>
                                            <div class="demo-review-value demo-review-message" id="reviewMessage"></div>
                                        </div>
                                    </div>

                                    <div class="demo-captcha-disclaimer">
                                        <i class="bi bi-shield-exclamation me-1" aria-hidden="true"></i>
                                        <strong>Catatan Verifikasi:</strong> Verifikasi matematika sisi server tidak dievaluasi dalam simulasi demo ini. Tidak ada interaksi backend yang dijalankan.
                                    </div>
                                </div>
                                <div class="modal-footer demo-modal-footer">
                                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">
                                        <i class="bi bi-pencil me-1" aria-hidden="true"></i> Ubah Data
                                    </button>
                                    <button type="button" class="btn-submit" id="btnConfirmDemo">
                                        Konfirmasi Simulasi <i class="bi bi-check-lg ms-1" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- SUCCESS STEP PANE (Section 4.D) -->
                            <div id="modalSuccessPane" style="display: none;">
                                <div class="modal-header demo-modal-header">
                                    <div>
                                        <span class="demo-badge demo-badge--success">Simulasi Selesai</span>
                                        <h4 class="modal-title demo-modal-title">Simulasi Berhasil</h4>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup simulasi"></button>
                                </div>
                                <div class="modal-body demo-modal-body text-center py-4">
                                    <div class="demo-success-icon mb-3">
                                        <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                    </div>
                                    <h5 class="demo-success-title mb-2">Simulasi Pengiriman Selesai</h5>
                                    <p class="demo-success-text mb-3">
                                        Simulasi berhasil. Form berhasil melewati validasi yang tersedia dalam mode demo. Pesan Anda tidak dikirim atau disimpan.
                                    </p>
                                    <div class="demo-security-pill">
                                        <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
                                        Data formulir telah dibersihkan secara lokal. Tidak ada catatan basis data yang dibuat dan tidak ada email yang dikirim.
                                    </div>
                                </div>
                                <div class="modal-footer demo-modal-footer justify-content-center">
                                    <button type="button" class="btn-submit" data-bs-dismiss="modal">
                                        <i class="bi bi-arrow-repeat me-1" aria-hidden="true"></i> Selesai & Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- =================================================== 
                 KANAN: INFORMASI KONTAK (col-lg-4)
                 =================================================== --}}
            <div class="col-lg-4">
                <aside class="sidebar-card">
                    <h3 class="form-step__title mb-4">Informasi Kontak</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Email</h4>
                            <p><a href="mailto:csirt@jakarta.go.id">csirt@jakarta.go.id</a></p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Telepon / WhatsApp</h4>
                            <p><a href="https://wa.me/6281388870152" target="_blank" rel="noopener noreferrer">0813-8887-0152</a></p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon" style="border-color: var(--alert); background: var(--alert-bg);">
                            <i class="bi bi-shield-fill-exclamation" style="color: var(--alert);"></i>
                        </div>
                        <div class="contact-text">
                            <h4 style="color: var(--alert);">Hotline Insiden 24/7</h4>
                            <p class="fw-bold" style="color: var(--ink);">1110</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Alamat</h4>
                            <p>Balaikota Blok H Lt. 13,<br>Jl. Merdeka Selatan 8-9,<br>Jakarta Pusat 10110</p>
                        </div>
                    </div>
                </aside>

                <aside class="sidebar-card">
                    <h3 class="form-step__title mb-4">Lokasi Kami</h3>
                    <div class="contact-map">
                        <iframe
                            src="https://maps.google.com/maps?q=Diskominfotik%20DKI&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            width="100%"
                            height="320"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Peta lokasi JakartaProv-CSIRT di Balaikota DKI Jakarta"
                            aria-label="Peta lokasi JakartaProv-CSIRT di Balaikota DKI Jakarta"></iframe>
                    </div>
                </aside>
            </div>

        </div>
    </div>
</section>

{{-- ===================================================
     INTERACTIVE VALIDATION & DEMO CONTROLLER SCRIPT
     =================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.getElementById('contactForm');
    if (!contactForm) return;

    const reviewModalEl = document.getElementById('demoReviewModal');
    const modalReviewPane = document.getElementById('modalReviewPane');
    const modalSuccessPane = document.getElementById('modalSuccessPane');
    const btnConfirmDemo = document.getElementById('btnConfirmDemo');
    const demoSuccessAlert = document.getElementById('demoSuccessAlert');
    const btnCloseSuccessAlert = document.getElementById('btnCloseSuccessAlert');

    let bsReviewModal = null;
    if (reviewModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        bsReviewModal = bootstrap.Modal.getOrCreateInstance(reviewModalEl);
    }

    if (btnCloseSuccessAlert && demoSuccessAlert) {
        btnCloseSuccessAlert.addEventListener('click', function () {
            demoSuccessAlert.style.display = 'none';
        });
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        field.removeAttribute('aria-invalid');
        const errEl = document.getElementById('demo-err-' + field.id);
        if (errEl) {
            errEl.style.display = 'none';
            errEl.textContent = '';
        }
    }

    function setFieldError(field, errorMsg) {
        field.classList.add('is-invalid');
        field.setAttribute('aria-invalid', 'true');
        const errElId = 'demo-err-' + field.id;
        let errEl = document.getElementById(errElId);
        if (!errEl) {
            errEl = document.createElement('div');
            errEl.id = errElId;
            errEl.className = 'field-error demo-field-error';
            errEl.setAttribute('role', 'alert');

            if (field.id === 'field-captcha-answer') {
                const captchaField = field.closest('.captcha-field');
                if (captchaField) {
                    captchaField.appendChild(errEl);
                } else {
                    field.parentNode.appendChild(errEl);
                }
            } else {
                field.parentNode.appendChild(errEl);
            }
        }
        errEl.textContent = '';
        const icon = document.createElement('i');
        icon.className = 'bi bi-exclamation-circle me-1';
        icon.setAttribute('aria-hidden', 'true');
        errEl.appendChild(icon);
        errEl.appendChild(document.createTextNode(errorMsg));
        errEl.style.display = 'flex';
        field.setAttribute('aria-describedby', errElId);
    }

    function validateField(field) {
        if (!field) return true;
        const id = field.id;
        const val = field.value ? field.value.trim() : '';
        let error = null;

        if (id === 'name') {
            if (!val) {
                error = 'Nama lengkap wajib diisi.';
            } else if (val.length > 255) {
                error = 'Nama lengkap tidak boleh lebih dari 255 karakter.';
            }
        } else if (id === 'email') {
            if (!val) {
                error = 'Email wajib diisi.';
            } else {
                const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/;
                if (!emailRegex.test(val)) {
                    error = 'Format alamat email tidak valid (contoh: nama@domain.com).';
                }
            }
        } else if (id === 'phone') {
            if (val && val.length > 20) {
                error = 'Nomor telepon tidak boleh lebih dari 20 karakter.';
            }
        } else if (id === 'organization') {
            if (val && val.length > 255) {
                error = 'Organisasi tidak boleh lebih dari 255 karakter.';
            }
        } else if (id === 'inquiry_type') {
            const allowed = ['general', 'support', 'partnership', 'media', 'other'];
            if (!val || allowed.indexOf(val) === -1) {
                error = 'Silakan pilih tipe pertanyaan.';
            }
        } else if (id === 'subject') {
            if (!val) {
                error = 'Subjek pesan wajib diisi.';
            } else if (val.length > 255) {
                error = 'Subjek pesan tidak boleh lebih dari 255 karakter.';
            }
        } else if (id === 'message') {
            if (!val) {
                error = 'Isi pesan wajib diisi.';
            }
        } else if (id === 'field-captcha-answer') {
            if (field.validity && field.validity.badInput) {
                error = 'Jawaban verifikasi harus berupa angka.';
            } else if (!val) {
                error = 'Jawaban verifikasi wajib diisi untuk simulasi formulir.';
            } else if (isNaN(parseInt(val, 10))) {
                error = 'Jawaban verifikasi harus berupa angka.';
            }
        }

        if (error) {
            setFieldError(field, error);
            return false;
        } else {
            clearFieldError(field);
            return true;
        }
    }

    const trackedInputs = [
        document.getElementById('name'),
        document.getElementById('email'),
        document.getElementById('phone'),
        document.getElementById('organization'),
        document.getElementById('inquiry_type'),
        document.getElementById('subject'),
        document.getElementById('message'),
        document.getElementById('field-captcha-answer')
    ].filter(Boolean);

    trackedInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            if (input.classList.contains('is-invalid')) {
                validateField(input);
            }
        });
        input.addEventListener('blur', function () {
            if (input.value.trim() !== '' || input.hasAttribute('required')) {
                validateField(input);
            }
        });
    });

    function populateReview(data) {
        document.getElementById('reviewName').textContent = data.name;
        document.getElementById('reviewEmail').textContent = data.email;
        document.getElementById('reviewPhone').textContent = data.phone || '(Tidak diisi)';
        document.getElementById('reviewOrganization').textContent = data.organization || '(Tidak diisi)';
        document.getElementById('reviewInquiryType').textContent = data.inquiryTypeLabel;
        document.getElementById('reviewSubject').textContent = data.subject;
        document.getElementById('reviewMessage').textContent = data.message;
    }

    // Intercept form submission — prevents real submission in browser
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        if (demoSuccessAlert) {
            demoSuccessAlert.style.display = 'none';
        }

        let firstInvalid = null;
        let isAllValid = true;

        trackedInputs.forEach(function (field) {
            const ok = validateField(field);
            if (!ok) {
                isAllValid = false;
                if (!firstInvalid) {
                    firstInvalid = field;
                }
            }
        });

        if (!isAllValid) {
            if (firstInvalid) {
                firstInvalid.focus();
            }
            return;
        }

        // Populate review modal safely using textContent
        const inquirySelect = document.getElementById('inquiry_type');
        let selectedLabel = inquirySelect.value;
        if (inquirySelect.selectedIndex >= 0) {
            selectedLabel = inquirySelect.options[inquirySelect.selectedIndex].text;
        }

        populateReview({
            name: document.getElementById('name').value.trim(),
            email: document.getElementById('email').value.trim(),
            phone: document.getElementById('phone').value.trim(),
            organization: document.getElementById('organization').value.trim(),
            inquiryTypeLabel: selectedLabel,
            subject: document.getElementById('subject').value.trim(),
            message: document.getElementById('message').value.trim()
        });

        if (modalReviewPane) modalReviewPane.style.display = 'block';
        if (modalSuccessPane) modalSuccessPane.style.display = 'none';

        if (bsReviewModal) {
            bsReviewModal.show();
        }
    });

    // Confirmation handler inside review modal
    if (btnConfirmDemo) {
        btnConfirmDemo.addEventListener('click', function () {
            // 1. Show success step inside modal
            if (modalReviewPane) modalReviewPane.style.display = 'none';
            if (modalSuccessPane) modalSuccessPane.style.display = 'block';

            // 2. Clear all form inputs safely
            contactForm.reset();
            trackedInputs.forEach(clearFieldError);

            // 3. Show in-page success alert
            if (demoSuccessAlert) {
                demoSuccessAlert.style.display = 'block';
            }
        });
    }

    // Reset modal panes when closed
    if (reviewModalEl) {
        reviewModalEl.addEventListener('hidden.bs.modal', function () {
            if (modalSuccessPane && modalSuccessPane.style.display === 'block') {
                modalReviewPane.style.display = 'block';
                modalSuccessPane.style.display = 'none';
                if (demoSuccessAlert) {
                    demoSuccessAlert.focus();
                }
            }
        });
    }
});
</script>
@endsection
