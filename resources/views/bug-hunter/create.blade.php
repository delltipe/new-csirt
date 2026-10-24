{{--
    create.blade.php  (resources/views/bug-hunter/create.blade.php)
    Komdigi-style single-page incident report form.
    One POST to bug-hunter.store. Reuses the .lapor-* design system classes.
--}}
@extends('layouts.app')

@section('content')

<style>
/* ============================================================
   PAGE HEADER BAND
   ============================================================ */
.lapor-header {
    background: var(--ink);
    padding: 48px 0 40px;
    position: relative;
    overflow: hidden;
}
.lapor-header .container { position: relative; z-index: 1; }
.lapor-header__eyebrow {
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #D6E4F8;
    margin-bottom: 10px;
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
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
    max-width: 540px;
}

/* ============================================================
   LAYOUT
   ============================================================ */
.lapor-layout {
    padding: 48px 0 80px;
    background: var(--mist);
}
.lapor-layout .container {
    max-width: 900px;
}

.form-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 3px solid var(--navy);
    padding: 36px 36px 40px;
}

.form-step__title {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 6px;
    line-height: 1;
}
.form-step__divider {
    height: 2px;
    background: var(--border);
    margin: 16px 0 28px;
}

.lapor-help-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    color: var(--navy);
    background: var(--white);
    border: 1px solid var(--border);
    padding: 4px 8px;
    text-decoration: none;
    transition: background var(--ease), border-color var(--ease), color var(--ease);
    white-space: nowrap;
    flex-shrink: 0;
}
.lapor-help-link:hover {
    background: var(--navy-tint);
    border-color: var(--navy);
    color: var(--navy);
}
.lapor-help-link:focus-visible {
    outline: 2px solid var(--navy);
    outline-offset: 2px;
}
.lapor-help-box {
    margin-top: 10px;
    background: var(--white);
    border: 1px solid var(--border);
    padding: 14px 16px;
}
.lapor-help-box__title {
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.lapor-help-box__title i { color: var(--navy); }
.lapor-help-box ul {
    padding-left: 16px;
    margin: 0;
    list-style: disc;
}
.lapor-help-box li {
    font-size: 13px;
    line-height: 1.6;
    color: var(--ink);
    margin-bottom: 6px;
}
.lapor-help-box li:last-child { margin-bottom: 0; }
.lapor-help-box li strong { color: var(--navy); }
.lapor-help-box__foot {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid var(--border);
    font-size: 12.5px;
    color: var(--mid);
    line-height: 1.6;
}
.lapor-help-box__foot a {
    color: var(--navy);
    text-decoration: underline;
    font-weight: 600;
}

/* ============================================================
   FORM ELEMENTS
   ============================================================ */
.lapor-label {
    display: block;
    font-family: var(--font-body);
    font-size: 12.5px;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 6px;
}
.lapor-label .req { color: var(--alert); margin-left: 2px; }

.lapor-input,
.lapor-select,
.lapor-textarea {
    width: 100%;
    min-height: 44px;
    height: auto;
    border: 1px solid var(--border);
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 400;
    padding: 0 14px;
    outline: none;
    transition: border-color var(--ease), box-shadow var(--ease);
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
    border-color: var(--navy);
    box-shadow: 0 0 0 3px rgba(0, 53, 128, 0.1);
}
.lapor-input.is-invalid,
.lapor-select.is-invalid,
.lapor-textarea.is-invalid {
    border-color: var(--alert);
    box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.1);
}
.lapor-input[type="file"] {
    height: auto;
    padding: 10px 14px;
    cursor: pointer;
    color: var(--mid);
}

.field-error {
    font-size: 12px;
    color: var(--alert);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.field-hint {
    font-size: 12.5px;
    color: var(--mid);
    margin-top: 6px;
    line-height: 1.6;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
.form-row .form-field { margin-bottom: 0; }
.form-field { margin-bottom: 20px; }
.form-field:last-child { margin-bottom: 0; }

/* ============================================================
   EVIDENCE ROWS
   ============================================================ */
.bukti-fieldset {
    border: none;
    padding: 0;
    margin: 0 0 24px;
}

.evidence-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.evidence-hint {
    font-size: 12.5px;
    color: var(--mid);
}

.bukti-row {
    display: grid;
    grid-template-columns: 150px 1fr auto;
    gap: 12px;
    align-items: start;
    padding: 14px;
    background: var(--mist);
    border: 1px solid var(--border);
    margin-bottom: 12px;
}

.bukti-actions {
    margin-top: 4px;
    margin-bottom: 0;
}

.btn-add-evidence {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--white);
    color: var(--navy);
    border: 2px solid var(--navy);
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 700;
    padding: 9px 18px;
    min-height: 44px;
    border-radius: 0;
    cursor: pointer;
    transition: background var(--ease), color var(--ease);
}
.btn-add-evidence:hover { background: var(--navy-tint); }
.btn-add-evidence:disabled { border-color: var(--border); color: var(--mid); cursor: not-allowed; }

.btn-remove-evidence {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    min-width: 44px;
    min-height: 44px;
    height: auto;
    padding: 0;
    align-self: end;
    background: var(--white);
    color: var(--alert);
    border: 1px solid var(--alert);
    border-radius: 0;
    font-size: 16px;
    cursor: pointer;
    transition: background var(--ease), color var(--ease);
}
.btn-remove-evidence:hover { background: var(--alert); color: var(--white); }

/* Progress Stepper */
.lapor-progress-track {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin: 0 0 28px;
    padding: 0 0 20px;
    border-bottom: 1px solid var(--border);
    list-style: none;
}
.visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}
.lapor-progress-step {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    background: var(--mist);
    border: 1px solid var(--border);
    font-size: 12px;
    font-weight: 600;
    color: var(--mid);
}
.lapor-progress-step.is-complete {
    background: var(--white);
    border-color: var(--navy);
    color: var(--navy);
}
.lapor-progress-step.is-active {
    background: var(--navy-tint);
    border-color: var(--navy);
    color: var(--navy);
    font-weight: 700;
}
.lapor-progress-step__num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    background: var(--border);
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
}
.lapor-progress-step.is-complete .lapor-progress-step__num,
.lapor-progress-step.is-active .lapor-progress-step__num {
    background: var(--navy);
    color: var(--white);
}

/* Downtime Quick Presets */
.downtime-presets {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 8px;
}
.btn-downtime-preset {
    background: var(--white);
    border: 1px solid var(--border);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    padding: 5px 11px;
    cursor: pointer;
    transition: background var(--ease), border-color var(--ease), color var(--ease);
}
.btn-downtime-preset:hover {
    background: var(--navy-tint);
    border-color: var(--navy);
    color: var(--navy);
}
.btn-downtime-preset.active {
    background: var(--navy);
    border-color: var(--navy);
    color: var(--white);
}

/* ============================================================
   VALIDATION SUMMARY
   ============================================================ */
.validation-summary {
    background: var(--alert-bg);
    border: 1px solid var(--alert);
    padding: 16px 20px;
    margin-bottom: 24px;
}
.validation-summary p {
    font-size: 13px;
    font-weight: 600;
    color: var(--alert);
    margin-bottom: 8px;
}
.validation-summary ul {
    list-style: disc;
    padding-left: 18px;
}
.validation-summary ul li {
    font-size: 13px;
    color: var(--alert);
    margin-bottom: 4px;
}

/* ============================================================
   SUBMIT
   ============================================================ */
.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 15px 36px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
}
.btn-submit:hover { background: var(--navy-dim); }

.submit-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
    margin-top: 28px;
    flex-wrap: wrap;
}

.btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--white);
    color: var(--ink);
    border: 1px solid var(--border);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 600;
    padding: 12px 24px;
    text-decoration: none;
    transition: color var(--ease), border-color var(--ease), background var(--ease);
}
.btn-cancel:hover { color: var(--navy); border-color: var(--navy); background: var(--mist); }

/* Dark/high-contrast keeps help box readable (token-based, avoids generic button flatten) */
html.accessibility-contrast-dark .lapor-help-box { background: #1a1a1a; border-color: #333333; }
html.accessibility-contrast-dark button.lapor-help-link { background: #1a1a1a; color: #4DA6FF; border-color: #333333; }
html.accessibility-contrast-dark button.lapor-help-link:hover { background: #2a2a3e; border-color: #4DA6FF; color: #4DA6FF; }
html.accessibility-contrast-dark .btn-downtime-preset { background: #1a1a1a; color: #FFFFFF; border-color: #444444; }
html.accessibility-contrast-dark .btn-downtime-preset:hover,
html.accessibility-contrast-dark .btn-downtime-preset.active { background: #4DA6FF; color: #0A0F1A; border-color: #66B3FF; }
html.accessibility-contrast-dark .lapor-progress-step { background: #1a1a1a; border-color: #333333; color: #AAAAAA; }
html.accessibility-contrast-dark .lapor-progress-step.is-complete,
html.accessibility-contrast-dark .lapor-progress-step.is-active { background: #1A2333; border-color: #4DA6FF; color: #4DA6FF; }
html.accessibility-contrast-dark button.btn-add-evidence { background: #1A2333; color: #4DA6FF; border-color: #4DA6FF; }
html.accessibility-contrast-dark button.btn-add-evidence:hover { background: #4DA6FF; color: #0A0F1A; }
html.accessibility-contrast-dark button.btn-remove-evidence { background: #1A2333; color: #FF6666; border-color: #FF6666; }
html.accessibility-contrast-dark a.btn-cancel { background: #1A2333; color: #FFFFFF; border-color: #444444; }
html.accessibility-contrast-high .lapor-help-box { background: #FFFFFF; border-color: #000000; }
html.accessibility-contrast-high button.lapor-help-link { background: #FFFFFF; color: #000080; border-color: #000000; }

/* High-Contrast Mode Overrides */
html.accessibility-contrast-dark .preview-form-alert {
    background: #241A08 !important;
    border-color: #F59E0B !important;
    color: #FDE68A !important;
}
html.accessibility-contrast-dark .preview-form-alert strong {
    color: #FBBF24 !important;
}
html.accessibility-contrast-high .preview-form-alert {
    background: #000000 !important;
    border: 2px solid #FFFF00 !important;
    color: #FFFF00 !important;
}
html.accessibility-contrast-high .preview-form-alert strong {
    color: #FFFF00 !important;
}

html.accessibility-contrast-high .btn-downtime-preset {
    background: #FFFFFF !important;
    color: #000000 !important;
    border: 2px solid #000000 !important;
}

html.accessibility-contrast-high .btn-downtime-preset:hover {
    background: #FFFF00 !important;
    color: #000000 !important;
}

html.accessibility-contrast-high .btn-downtime-preset.active {
    background: #000080 !important;
    color: #FFFFFF !important;
    border: 2px solid #000000 !important;
    outline: 2px solid #000000 !important;
    outline-offset: 1px;
}

html.accessibility-contrast-high .btn-add-evidence {
    background: #FFFFFF !important;
    color: #000080 !important;
    border: 2px solid #000080 !important;
}

html.accessibility-contrast-high .btn-add-evidence:hover {
    background: #000080 !important;
    color: #FFFFFF !important;
}

html.accessibility-contrast-high .btn-remove-evidence {
    background: #FFFFFF !important;
    color: #CC0000 !important;
    border: 2px solid #CC0000 !important;
}

html.accessibility-contrast-high .btn-remove-evidence:hover {
    background: #CC0000 !important;
    color: #FFFFFF !important;
}

html.accessibility-contrast-high .btn-submit {
    background: #000080 !important;
    color: #FFFFFF !important;
    border: 2px solid #000000 !important;
}

html.accessibility-contrast-high .btn-submit:hover {
    background: #000040 !important;
    color: #FFFFFF !important;
}

html.accessibility-contrast-high a.btn-cancel {
    background: #FFFFFF !important;
    color: #000000 !important;
    border: 2px solid #000000 !important;
    text-decoration: none !important;
}

html.accessibility-contrast-high a.btn-cancel:hover {
    background: #FFFF00 !important;
    color: #000000 !important;
}

html.accessibility-contrast-high .lapor-progress-step {
    background: #FFFFFF !important;
    border: 2px solid #000000 !important;
    color: #000000 !important;
}

html.accessibility-contrast-high .lapor-progress-step.is-complete,
html.accessibility-contrast-high .lapor-progress-step.is-active {
    background: #FFFFFF !important;
    border: 2px solid #000080 !important;
    color: #000080 !important;
}

html.accessibility-contrast-high .lapor-progress-step__num {
    background: #000000 !important;
    color: #FFFFFF !important;
}

html.accessibility-contrast-high .lapor-progress-step.is-complete .lapor-progress-step__num,
html.accessibility-contrast-high .lapor-progress-step.is-active .lapor-progress-step__num {
    background: #000080 !important;
    color: #FFFFFF !important;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 700px) {
    .form-row, .bukti-row { grid-template-columns: 1fr; }
    .btn-remove-evidence { justify-self: end; }
    .form-card { padding: 24px 18px; }
    .submit-nav { flex-direction: column-reverse; align-items: stretch; }
    .btn-submit, .btn-cancel { justify-content: center; }
}

@media (max-width: 640px) {
    .lapor-progress-track {
        grid-template-columns: 1fr;
        gap: 8px;
    }
}
</style>

<div class="lapor-header">
    <div class="container">
        <div class="lapor-header__eyebrow">
            <i class="bi bi-shield-shaded" aria-hidden="true"></i>
            Simulasi Pratinjau Publik
        </div>
        <h1>Lapor Insiden Siber (Uji Coba Sandbox)</h1>
        <p class="lapor-header__sub">
            Formulir simulasi pelaporan insiden siber untuk pengujian alur interaktif. Gunakan data fiktif untuk simulasi penerbitan tiket dan peninjauan.
        </p>
    </div>
</div>

<div class="lapor-layout">
    <div class="container">

        <div class="preview-form-alert" role="region" aria-label="Peringatan Data Fiktif Pratinjau Publik" style="background: #FFFBEB; border: 1px solid #F59E0B; border-left: 4px solid #D97706; padding: 18px 22px; margin-bottom: 24px; color: #78350F;">
            <div style="display: flex; gap: 12px; align-items: flex-start;">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 20px; color: #D97706; flex-shrink: 0; margin-top: 2px;" aria-hidden="true"></i>
                <div style="font-size: 13.5px; line-height: 1.6;">
                    <strong style="color: #92400E; display: block; margin-bottom: 4px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.04em;">Peringatan Keamanan &amp; Data Fiktif Lingkungan Pengujian:</strong>
                    Situs ini merupakan <strong>lingkungan pratinjau publik (sandbox preview)</strong>. Dilarang keras memasukkan data pribadi asli, NIK, alamat riil, kata sandi, token otentikasi, dokumen rahasia, maupun laporan insiden siber nyata.
                    Seluruh data yang dikirimkan disimpan pada database isolasi simulasi dan <strong>tidak</strong> diteruskan ke tim penanganan insiden darurat JakartaProv-CSIRT.
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="validation-summary" role="alert" tabindex="-1" id="validation-summary" autofocus>
            <p><i class="bi bi-exclamation-circle-fill"></i> Harap perbaiki {{ $errors->count() }} kesalahan berikut:</p>
            <ul>
                @foreach ($errors->keys() as $field)
                {{-- bukti.N.(jenis|file|url) rows are restored with identical 0-based
                     indexes after a failed submit, so link straight to the control;
                     anything else bukti-related falls back to the group anchor. --}}
                @php
                    if (preg_match('/^bukti\.(\d+)\.(jenis|file|url)$/', $field, $m)) {
                        $anchor = "bukti-{$m[1]}-{$m[2]}";
                    } else {
                        $anchor = str_starts_with($field, 'bukti') ? 'field-bukti' : 'field-' . str_replace(['.', '_'], '-', $field);
                    }
                @endphp
                <li><a href="#{{ $anchor }}" style="color:inherit;">{{ $errors->first($field) }}</a></li>
                @endforeach
            </ul>
        </div>
        <script>document.getElementById('validation-summary')?.focus();</script>
        @endif

        <form class="form-card" method="POST"
              action="{{ route('bug-hunter.store') }}"
              enctype="multipart/form-data" novalidate>
            @csrf

            {{-- Reporting Progress Stepper --}}
            <ol class="lapor-progress-track" aria-label="Tahapan Pelaporan Insiden">
                <li class="lapor-progress-step is-complete">
                    <span class="lapor-progress-step__num" aria-hidden="true"><i class="bi bi-check-lg"></i></span>
                    <span class="lapor-progress-step__text"><span class="visually-hidden">Langkah 1 selesai: </span>1. Panduan &amp; Data Fiktif</span>
                </li>
                <li class="lapor-progress-step is-active" aria-current="step">
                    <span class="lapor-progress-step__num" aria-hidden="true">2</span>
                    <span class="lapor-progress-step__text"><span class="visually-hidden">Langkah 2 aktif: </span>2. Pengisian Detail Insiden</span>
                </li>
                <li class="lapor-progress-step">
                    <span class="lapor-progress-step__num" aria-hidden="true">3</span>
                    <span class="lapor-progress-step__text">3. Tiket Simulasi</span>
                </li>
            </ol>

            <h2 class="form-step__title">Data Insiden</h2>
            <div class="form-step__divider"></div>

            <div class="form-field">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:6px;">
                    <label class="lapor-label" for="field-kategori-insiden" style="margin-bottom:0;">
                        Kategori Insiden <span class="req" aria-hidden="true">*</span>
                    </label>
                    <button type="button" class="lapor-help-link" id="btn-bantuan-kategori"
                            aria-expanded="false" aria-controls="bantuan-kategori">
                        <i class="bi bi-question-circle" aria-hidden="true"></i> Panduan kategori
                    </button>
                </div>
                <select id="field-kategori-insiden" name="kategori_insiden"
                        class="lapor-select lapor-input @error('kategori_insiden') is-invalid @enderror"
                        aria-describedby="hint-kategori-insiden bantuan-kategori @error('kategori_insiden') err-kategori-insiden @enderror"
                        @error('kategori_insiden') aria-invalid="true" @enderror required>
                    <option value="" disabled {{ old('kategori_insiden') ? '' : 'selected' }}>Pilih kategori...</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ old('kategori_insiden') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                <div class="field-hint" id="hint-kategori-insiden">Pilih jenis serangan yang paling mendekati temuan Anda. Klik <em>Panduan kategori</em> untuk penjelasan singkat tiap pilihan.</div>
                <div id="bantuan-kategori" class="lapor-help-box" hidden>
                    <div class="lapor-help-box__title"><i class="bi bi-info-circle" aria-hidden="true"></i> Cara memilih kategori</div>
                    <ul>
                        <li><strong>Website Defacement</strong> — tampilan situs diubah tanpa izin (mis. pesan peretasan).</li>
                        <li><strong>Phishing</strong> — halaman/login palsu yang meniru domain resmi untuk mencuri kredensial.</li>
                        <li><strong>Malware / Ransomware</strong> — file/aplikasi berbahaya, enkripsi data, atau perilaku mencurigakan.</li>
                        <li><strong>Kebocoran Data</strong> — data pribadi/rahasia terekspos atau dapat diakses tanpa otorisasi.</li>
                        <li><strong>DDoS / Penolakan Layanan</strong> — layanan lambat/tidak dapat diakses akibat lonjakan trafik serangan.</li>
                        <li><strong>SQL Injection / XSS</strong> — celah injeksi pada form/URL yang memungkinkan eksekusi kode atau pembacaan data.</li>
                        <li><strong>Social Engineering</strong> — manipulasi pengguna (mis. telepon/email mengatasnamakan instansi).</li>
                        <li><strong>Lainnya</strong> — tidak termasuk di atas; jelaskan detail pada kolom Deskripsi.</li>
                    </ul>
                    <div class="lapor-help-box__foot">
                        Masih ragu? Pilih yang paling mendekati, lalu jelaskan detail di <em>Deskripsi Kejadian</em>. Lihat juga <a href="{{ route('guides.index') }}" target="_blank" rel="noopener">Panduan Teknis</a> untuk mitigasi umum.
                    </div>
                </div>
                @error('kategori_insiden')
                <div class="field-error" id="err-kategori-insiden" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label class="lapor-label" for="field-waktu-kejadian">
                        Waktu Kejadian <span class="req" aria-hidden="true">*</span>
                    </label>
                    <input type="datetime-local" id="field-waktu-kejadian" name="waktu_kejadian"
                           class="lapor-input @error('waktu_kejadian') is-invalid @enderror"
                           value="{{ old('waktu_kejadian') }}" required
                           aria-describedby="hint-waktu-kejadian @error('waktu_kejadian') err-waktu-kejadian @enderror"
                           @error('waktu_kejadian') aria-invalid="true" @enderror>
                    <div class="field-hint" id="hint-waktu-kejadian">Kapan kejadian pertama kali diketahui? Isi tanggal dan jam.</div>
                    @error('waktu_kejadian')
                    <div class="field-error" id="err-waktu-kejadian" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-field">
                    <label class="lapor-label" for="field-down-time">
                        Durasi Gangguan (Down Time) <span class="req" aria-hidden="true">*</span>
                    </label>
                    <div class="downtime-presets" role="group" aria-label="Preset durasi gangguan layanan">
                        <button type="button" class="btn-downtime-preset {{ old('down_time', '00:00') === '00:00' ? 'active' : '' }}" data-value="00:00" aria-pressed="{{ old('down_time', '00:00') === '00:00' ? 'true' : 'false' }}">Tidak Ada (00:00)</button>
                        <button type="button" class="btn-downtime-preset {{ old('down_time') === '00:30' ? 'active' : '' }}" data-value="00:30" aria-pressed="{{ old('down_time') === '00:30' ? 'true' : 'false' }}">&lt; 1 Jam (00:30)</button>
                        <button type="button" class="btn-downtime-preset {{ old('down_time') === '01:30' ? 'active' : '' }}" data-value="01:30" aria-pressed="{{ old('down_time') === '01:30' ? 'true' : 'false' }}">1-2 Jam (01:30)</button>
                        <button type="button" class="btn-downtime-preset {{ old('down_time') === '04:00' ? 'active' : '' }}" data-value="04:00" aria-pressed="{{ old('down_time') === '04:00' ? 'true' : 'false' }}">2-6 Jam (04:00)</button>
                        <button type="button" class="btn-downtime-preset {{ old('down_time') === '08:00' ? 'active' : '' }}" data-value="08:00" aria-pressed="{{ old('down_time') === '08:00' ? 'true' : 'false' }}">&gt; 6 Jam (08:00)</button>
                    </div>
                    <input type="time" id="field-down-time" name="down_time"
                           class="lapor-input @error('down_time') is-invalid @enderror"
                           value="{{ old('down_time', '00:00') }}" required
                           aria-describedby="hint-down-time @error('down_time') err-down-time @enderror"
                           @error('down_time') aria-invalid="true" @enderror>
                    <div class="field-hint" id="hint-down-time">
                        Durasi total layanan down/lumpuh (format jam:menit). <strong>Klik preset 'Tidak Ada (00:00)'</strong> jika temuan tidak menghentikan operasional layanan (misal: vulnerability disclosure, XSS, phishing, data leakage).
                    </div>
                    @error('down_time')
                    <div class="field-error" id="err-down-time" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-field">
                <label class="lapor-label" for="field-lokasi-url">
                    Lokasi Insiden / URL Validasi <span class="req" aria-hidden="true">*</span>
                </label>
                <input type="url" id="field-lokasi-url" name="lokasi_url"
                       class="lapor-input @error('lokasi_url') is-invalid @enderror"
                       value="{{ old('lokasi_url') }}" required
                       aria-describedby="hint-lokasi-url @error('lokasi_url') err-lokasi-url @enderror"
                       @error('lokasi_url') aria-invalid="true" @enderror
                       placeholder="https://portal.jakarta.go.id/halaman/...">
                <div class="field-hint" id="hint-lokasi-url">Alamat halaman terdampak, diawali https://. Utamakan domain *.jakarta.go.id.</div>
                @error('lokasi_url')
                <div class="field-error" id="err-lokasi-url" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-field">
                <label class="lapor-label" for="field-deskripsi">
                    Deskripsi Kejadian <span class="req" aria-hidden="true">*</span>
                </label>
                <textarea id="field-deskripsi" name="deskripsi" rows="5"
                          class="lapor-textarea @error('deskripsi') is-invalid @enderror"
                          required
                          aria-describedby="hint-deskripsi @error('deskripsi') err-deskripsi @enderror"
                          @error('deskripsi') aria-invalid="true" @enderror
                          placeholder="Jelaskan kronologi kejadian secara detail...">{{ old('deskripsi') }}</textarea>
                <div class="field-hint" id="hint-deskripsi">Ceritakan kronologi, dampak, dan langkah reproduksi. Cuplikan payload/teknis boleh disertakan apa adanya.</div>
                @error('deskripsi')
                <div class="field-error" id="err-deskripsi" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-field">
                <label class="lapor-label" for="field-tindakan-teknis">
                    Tindakan Teknis <span class="req" aria-hidden="true">*</span>
                </label>
                <textarea id="field-tindakan-teknis" name="tindakan_teknis" rows="3"
                          class="lapor-textarea @error('tindakan_teknis') is-invalid @enderror"
                          required
                          aria-describedby="hint-tindakan-teknis @error('tindakan_teknis') err-tindakan-teknis @enderror"
                          @error('tindakan_teknis') aria-invalid="true" @enderror
                          placeholder="Langkah teknis yang telah Anda lakukan atau yang Anda rekomendasikan...">{{ old('tindakan_teknis') }}</textarea>
                <div class="field-hint" id="hint-tindakan-teknis">Tulis langkah mitigasi yang sudah dilakukan atau disarankan (mis. blokir IP, rotasi kredensial).</div>
                @error('tindakan_teknis')
                <div class="field-error" id="err-tindakan-teknis" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                @enderror
            </div>

            <fieldset class="bukti-fieldset">
            <legend class="form-step__title" style="font-size:20px; margin-top:8px; padding:0;">Bukti Tautan Simulasi</legend>
            <div class="form-step__divider"></div>

            <div class="evidence-head">
                <span class="evidence-hint" id="hint-bukti"><strong>Unggah file biner dinonaktifkan demi keamanan pratinjau.</strong> Masukkan maksimal 3 tautan URL bukti simulasi atau dokumentasi publik (diawali http:// atau https://). File tidak diperkenankan.</span>
            </div>

            @if ($errors->has('bukti') || collect($errors->keys())->contains(fn ($k) => str_starts_with($k, 'bukti')))
            <div class="field-error" id="err-bukti" role="alert" style="margin-bottom:12px;"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $errors->first('bukti') ?: $errors->first(collect($errors->keys())->first(fn ($k) => str_starts_with($k, 'bukti'))) }}</div>
            @endif

            <div id="field-bukti" role="group" aria-describedby="hint-bukti"></div>

            <div class="form-field bukti-actions">
                <button type="button" class="btn-add-evidence" id="btn-add-evidence" aria-describedby="hint-bukti">
                    <i class="bi bi-plus-circle" aria-hidden="true"></i> Tambah URL Bukti
                </button>
            </div>
            </fieldset>

            <div class="form-field" style="margin-top: 24px; padding: 18px 20px; background: var(--mist); border: 1px solid var(--border);">
                <label class="lapor-checkbox-container" style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; margin-bottom: 0;">
                    <input type="checkbox" name="syarat_ketentuan" value="1" id="field-syarat-ketentuan"
                           class="@error('syarat_ketentuan') is-invalid @enderror"
                           {{ old('syarat_ketentuan') ? 'checked' : '' }} required style="margin-top: 3px; width: 18px; height: 18px; flex-shrink: 0; cursor: pointer;">
                    <span style="font-size: 13px; line-height: 1.6; color: var(--ink);">
                        <strong>Pernyataan Data Fiktif &amp; Simulasi:</strong> Saya memahami dan menyetujui bahwa seluruh informasi yang dikirimkan pada formulir ini adalah data fiktif untuk simulasi pengujian pratinjau publik. Saya menyatakan tidak menyertakan data pribadi asli, kata sandi, token otentikasi, dokumen rahasia, maupun insiden siber riil. <span class="req" style="color: var(--alert); font-weight: bold;">*</span>
                    </span>
                </label>
                @error('syarat_ketentuan')
                <div class="field-error" id="err-syarat-ketentuan" role="alert" style="margin-top: 8px;"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
                @enderror
            </div>

            @include('components.captcha', ['question' => $captchaQuestion])

            <div class="submit-nav">
                <a href="{{ route('home') }}" class="btn-cancel">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Beranda
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-send-fill" aria-hidden="true"></i> Kirim Laporan Simulasi
                </button>
            </div>
        </form>

    </div>
</div>

<script>
(function () {
    const MAX_EVIDENCE = 3;
    const list = document.getElementById('field-bukti');
    const addBtn = document.getElementById('btn-add-evidence');
    let count = 0;

    const oldBukti = @json(old('bukti', []));

    function updateAddBtn() {
        if (addBtn) {
            addBtn.disabled = count >= MAX_EVIDENCE;
        }
    }

    function renumberRows() {
        if (!list) return;
        const rows = list.querySelectorAll('.bukti-row');
        rows.forEach(function (row, idx) {
            row.dataset.index = idx;

            // Hidden Jenis input
            const jenisInput = row.querySelector('.bukti-jenis');
            if (jenisInput) {
                jenisInput.name = 'bukti[' + idx + '][jenis]';
            }

            // URL Bukti
            const urlLabel = row.querySelector('.bukti-url-label');
            const urlInput = row.querySelector('.bukti-url');
            if (urlLabel) {
                urlLabel.setAttribute('for', 'bukti-' + idx + '-url');
                urlLabel.textContent = 'URL Bukti Tautan ' + (idx + 1);
            }
            if (urlInput) {
                urlInput.id = 'bukti-' + idx + '-url';
                urlInput.name = 'bukti[' + idx + '][url]';
            }

            // Remove button
            const removeBtn = row.querySelector('.btn-remove-evidence');
            if (removeBtn) {
                removeBtn.setAttribute('aria-label', 'Hapus bukti tautan ' + (idx + 1));
            }
        });

        count = rows.length;
        updateAddBtn();
    }

    function template(index, urlValue) {
        const div = document.createElement('div');
        div.className = 'bukti-row';
        div.dataset.index = index;
        div.style.gridTemplateColumns = '1fr auto';
        div.innerHTML =
            '<div>' +
                '<input type="hidden" class="bukti-jenis" name="bukti[' + index + '][jenis]" value="url">' +
                '<label class="lapor-label bukti-url-label" for="bukti-' + index + '-url" style="font-size:11px;">URL Bukti Tautan ' + (index + 1) + ' <span class="req">*</span></label>' +
                '<input type="url" id="bukti-' + index + '-url" name="bukti[' + index + '][url]" class="lapor-input bukti-url" placeholder="https://..." value="' + (urlValue || '') + '" aria-describedby="hint-bukti" required>' +
            '</div>' +
            '<button type="button" class="btn-remove-evidence" aria-label="Hapus bukti tautan ' + (index + 1) + '"><i class="bi bi-trash3" aria-hidden="true"></i></button>';

        div.querySelector('.btn-remove-evidence').addEventListener('click', function () {
            const nextRow = div.nextElementSibling;
            const prevRow = div.previousElementSibling;

            div.remove();
            renumberRows();

            // Focus management: move to next, previous, or Add button
            if (nextRow && nextRow.querySelector('.btn-remove-evidence')) {
                nextRow.querySelector('.btn-remove-evidence').focus();
            } else if (prevRow && prevRow.querySelector('.btn-remove-evidence')) {
                prevRow.querySelector('.btn-remove-evidence').focus();
            } else if (addBtn) {
                addBtn.focus();
            }
        });

        return div;
    }

    function addRow(prefill, shiftFocus) {
        if (count >= MAX_EVIDENCE) return;
        const row = template(count, prefill ? (prefill.url || '') : '');
        list.appendChild(row);
        renumberRows();

        if (shiftFocus) {
            const focusTarget = row.querySelector('.bukti-url');
            if (focusTarget) focusTarget.focus();
        }
    }

    if (addBtn) {
        addBtn.addEventListener('click', function () { addRow(null, true); });
    }

    // Restore any rows from a failed submission
    if (typeof oldBukti === 'object' && Object.keys(oldBukti).length) {
        Object.keys(oldBukti).forEach(function (key) {
            const val = oldBukti[key];
            if (val && (val.url || val.jenis)) addRow(val, false);
        });
    }

    // Help toggle for Kategori (E) — unobtrusive, boxy, no modal
    (function () {
        var btn = document.getElementById('btn-bantuan-kategori');
        var box = document.getElementById('bantuan-kategori');
        if (!btn || !box) return;
        btn.addEventListener('click', function () {
            var hidden = box.hasAttribute('hidden');
            if (hidden) box.removeAttribute('hidden'); else box.setAttribute('hidden', '');
            btn.setAttribute('aria-expanded', hidden ? 'true' : 'false');
            if (hidden) box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    })();

    // Downtime Quick Preset Chips
    (function () {
        var presetBtns = document.querySelectorAll('.btn-downtime-preset');
        var downTimeInput = document.getElementById('field-down-time');
        if (!presetBtns.length || !downTimeInput) return;

        function syncPresets(val) {
            presetBtns.forEach(function (button) {
                var isMatch = button.getAttribute('data-value') === val;
                button.classList.toggle('active', isMatch);
                button.setAttribute('aria-pressed', isMatch ? 'true' : 'false');
            });
        }

        presetBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var val = this.getAttribute('data-value');
                downTimeInput.value = val;
                syncPresets(val);
                downTimeInput.dispatchEvent(new Event('input', { bubbles: true }));
            });
        });

        downTimeInput.addEventListener('input', function () {
            syncPresets(this.value);
        });
    })();
})();
</script>

@endsection
