{{--
    create.blade.php  (resources/views/incidents/create.blade.php)
    Route: GET /incidents/create  →  IncidentController@create
           POST /incidents         →  IncidentController@store

    Uses vanilla JS only — no framework required.
    All Tailwind classes have been replaced with our design system classes.
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
.lapor-header::before {
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
.lapor-header .container { position: relative; z-index: 1; }
.lapor-header__eyebrow {
    font-family: var(--font-body);
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.35);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.lapor-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.2);
}
.lapor-header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 52px);
    font-weight: 900;
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
   LAYOUT: form column + sidebar column
   ============================================================ */
.lapor-layout {
    padding: 48px 0 80px;
    background: var(--mist);
}
.lapor-layout .container {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 32px;
    align-items: start;
}

/* ============================================================
   STEP INDICATORS
   ============================================================ */
.step-indicators {
    display: flex;
    gap: 0;
    border: 1px solid var(--border);
    margin-bottom: 0;
    background: var(--white);
}
.step-indicator {
    flex: 1;
    padding: 14px 12px;
    text-align: center;
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--mid);
    background: var(--white);
    border-right: 1px solid var(--border);
    border-bottom: 3px solid transparent;
    transition: color var(--ease), border-color var(--ease), background var(--ease);
    user-select: none;
}
.step-indicator:last-child { border-right: none; }
.step-indicator.is-active {
    color: var(--navy);
    border-bottom-color: var(--navy);
    background: var(--navy-tint);
}
.step-indicator.is-done {
    color: var(--navy);
    border-bottom-color: var(--navy);
}
.step-indicator .step-num {
    display: inline-block;
    width: 20px; height: 20px;
    border-radius: 50%;
    background: var(--border);
    color: var(--mid);
    font-size: 11px;
    line-height: 20px;
    text-align: center;
    margin-right: 6px;
    transition: background var(--ease), color var(--ease);
}
.step-indicator.is-active .step-num {
    background: var(--navy);
    color: var(--white);
}
.step-indicator.is-done .step-num {
    background: var(--navy);
    color: var(--white);
}


/* ============================================================
   FORM CARD
   ============================================================ */
.form-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: none; /* shares border with indicators */
}

.form-step {
    display: none;
    padding: 36px 32px;
}
.form-step.is-visible { display: block; }

.form-step__title {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 900;
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


/* ============================================================
   FORM ELEMENTS — override Bootstrap defaults
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
    height: 44px;
    border: 1px solid var(--border);
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 400;
    padding: 0 14px;
    outline: none;
    transition: border-color var(--ease), box-shadow var(--ease);
    border-radius: 0; /* sharp corners per design */
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

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-row.cols-3 {
    grid-template-columns: 1fr 1fr 1fr;
}
.form-field { margin-bottom: 20px; }
.form-field:last-child { margin-bottom: 0; }


/* ============================================================
   CAPTCHA BOX
   ============================================================ */
.captcha-box {
    background: var(--mist);
    border: 1px solid var(--border);
    border-left: 3px solid var(--navy);
    padding: 18px 20px;
    margin-bottom: 20px;
}
.captcha-box .lapor-label {
    color: var(--navy);
    margin-bottom: 8px;
}


/* ============================================================
   NAVIGATION BUTTONS
   ============================================================ */
.step-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 24px;
    border-top: 1px solid var(--border);
    margin-top: 28px;
}
.step-nav.first { justify-content: flex-end; }

.btn-step-next {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 13px 28px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
}
.btn-step-next:hover { background: var(--navy-dim); }

.btn-step-prev {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    color: var(--mid);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 500;
    padding: 13px 0;
    border: none;
    cursor: pointer;
    transition: color var(--ease);
}
.btn-step-prev:hover { color: var(--ink); }

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 900;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 15px 36px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
}
.btn-submit:hover { background: var(--navy-dim); }


/* ============================================================
   SIDEBAR
   ============================================================ */
.lapor-sidebar {
    position: sticky;
    top: calc(68px + 24px); /* nav height + gap */
}
.sidebar-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 3px solid var(--navy);
    padding: 24px;
    margin-bottom: 16px;
}
.sidebar-card__title {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sidebar-card__title i { color: var(--navy); font-size: 15px; }

.sidebar-rules {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.sidebar-rules li {
    font-size: 13px;
    color: var(--mid);
    line-height: 1.6;
    padding-left: 16px;
    position: relative;
}
.sidebar-rules li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 8px;
    width: 5px; height: 5px;
    background: var(--navy);
    border-radius: 50%;
}
.sidebar-rules li strong { color: var(--ink); }
.sidebar-rules li.rule-important {
    color: var(--navy);
    font-weight: 600;
}
.sidebar-rules li.rule-important::before { background: var(--navy); }

/* Progress tracker in sidebar */
.sidebar-progress {
    display: flex;
    flex-direction: column;
    gap: 0;
}
.progress-step {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid var(--border);
    font-size: 13px;
    color: var(--mid);
}
.progress-step:last-child { border-bottom: none; }
.progress-step.is-active { color: var(--navy); font-weight: 600; }
.progress-step.is-done { color: var(--mid); }
.progress-dot {
    width: 28px; height: 28px;
    border-radius: 50%;
    border: 2px solid var(--border);
    background: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-size: 12px;
    font-weight: 800;
    color: var(--mid);
    flex-shrink: 0;
    transition: all var(--ease);
}
.progress-step.is-active .progress-dot {
    border-color: var(--navy);
    background: var(--navy);
    color: var(--white);
}
.progress-step.is-done .progress-dot {
    border-color: var(--navy);
    background: var(--navy-tint);
    color: var(--navy);
}


/* ============================================================
   VALIDATION ERROR LIST (Laravel @errors)
   ============================================================ */
.validation-summary {
    background: var(--alert-bg);
    border: 1px solid var(--alert);
    border-left: 3px solid var(--alert);
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
   RESPONSIVE
   ============================================================ */
@media (max-width: 900px) {
    .lapor-layout .container {
        grid-template-columns: 1fr;
    }
    .lapor-sidebar { position: static; }
}
@media (max-width: 640px) {
    .form-row, .form-row.cols-3 { grid-template-columns: 1fr; }
    .form-step { padding: 24px 18px; }
}
</style>


{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<div class="lapor-header">
    <div class="container">
        <div class="lapor-header__eyebrow">
            <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
            JakartaProv-CSIRT
        </div>
        <h1>Lapor Insiden Siber</h1>
        <p class="lapor-header__sub">
            Bantu kami melindungi infrastruktur digital Jakarta dengan melaporkan insiden keamanan siber yang Anda temukan.
        </p>
    </div>
</div>


{{-- ============================================================
     MAIN LAYOUT
     ============================================================ --}}
<div class="lapor-layout">
    <div class="container">

        {{-- ===================================================
             FORM COLUMN
             =================================================== --}}
        <div>
            {{-- Laravel validation errors --}}
            @if ($errors->any())
            <div class="validation-summary" role="alert">
                <p><i class="bi bi-exclamation-circle-fill"></i> Harap perbaiki kesalahan berikut:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Step indicators --}}
            <div class="step-indicators" role="tablist" aria-label="Langkah pengisian laporan">
                <div class="step-indicator is-active" id="ind-1" role="tab" aria-selected="true">
                    <span class="step-num">1</span>Pelapor
                </div>
                <div class="step-indicator" id="ind-2" role="tab" aria-selected="false">
                    <span class="step-num">2</span>Website
                </div>
                <div class="step-indicator" id="ind-3" role="tab" aria-selected="false">
                    <span class="step-num">3</span>Detail
                </div>
            </div>

            {{-- Form --}}
            <form id="lapor-form"
                  action="{{ route('incidents.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  novalidate>
                @csrf
                <input type="hidden" id="current-step" name="current_step" value="1">

                <div class="form-card">

                    {{-- =========================================
                         STEP 1 — Data Pelapor
                         ========================================= --}}
                    <div class="form-step is-visible" data-step="1">
                        <h2 class="form-step__title">Data Pelapor</h2>
                        <div class="form-step__divider"></div>

                        <div class="form-row">
                            <div class="form-field">
                                <label class="lapor-label" for="fullName">
                                    Nama Lengkap <span class="req">*</span>
                                </label>
                                <input type="text" id="fullName" name="fullName"
                                       class="lapor-input @error('fullName') is-invalid @enderror"
                                       value="{{ old('fullName') }}" required
                                       placeholder="Masukkan nama lengkap">
                                @error('fullName')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-field">
                                <label class="lapor-label" for="email">
                                    Alamat Email <span class="req">*</span>
                                </label>
                                <input type="email" id="email" name="email"
                                       class="lapor-input @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required
                                       placeholder="nama@domain.com">
                                @error('email')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-field">
                                <label class="lapor-label" for="phoneNumber">
                                    Nomor HP <span class="req">*</span>
                                </label>
                                <input type="tel" id="phoneNumber" name="phoneNumber"
                                       class="lapor-input @error('phoneNumber') is-invalid @enderror"
                                       value="{{ old('phoneNumber') }}" required
                                       placeholder="08xxxxxxxxxx">
                                @error('phoneNumber')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-field">
                                <label class="lapor-label" for="foundDate">Tanggal Temuan</label>
                                <input type="date" id="foundDate" name="foundDate"
                                       class="lapor-input"
                                       value="{{ old('foundDate') }}">
                            </div>
                        </div>

                        <div class="step-nav first">
                            <button type="button" class="btn-step-next btn-next">
                                Selanjutnya: Data Website <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>


                    {{-- =========================================
                         STEP 2 — Data Website
                         ========================================= --}}
                    <div class="form-step" data-step="2">
                        <h2 class="form-step__title">Data Website</h2>
                        <div class="form-step__divider"></div>

                        <div class="form-field">
                            <label class="lapor-label" for="domain">
                                Nama Domain <span class="req">*</span>
                            </label>
                            <input type="text" id="domain" name="domain"
                                   class="lapor-input @error('domain') is-invalid @enderror"
                                   value="{{ old('domain') }}" required
                                   placeholder="contoh: portal.jakarta.go.id">
                            @error('domain')
                            <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label class="lapor-label" for="url">
                                URL Terdampak <span class="req">*</span>
                            </label>
                            <input type="url" id="url" name="url"
                                   class="lapor-input @error('url') is-invalid @enderror"
                                   value="{{ old('url') }}" required
                                   placeholder="https://portal.jakarta.go.id/halaman/...">
                            @error('url')
                            <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="step-nav">
                            <button type="button" class="btn-step-prev btn-prev">
                                <i class="bi bi-arrow-left"></i> Sebelumnya
                            </button>
                            <button type="button" class="btn-step-next btn-next">
                                Selanjutnya: Detail Laporan <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>


                    {{-- =========================================
                         STEP 3 — Detail Laporan
                         ========================================= --}}
                    <div class="form-step" data-step="3">
                        <h2 class="form-step__title">Detail Laporan</h2>
                        <div class="form-step__divider"></div>

                        <div class="form-field">
                            <label class="lapor-label" for="laporDesc">
                                Deskripsi Insiden <span class="req">*</span>
                            </label>
                            <textarea id="laporDesc" name="laporDesc" rows="5"
                                      class="lapor-textarea @error('laporDesc') is-invalid @enderror"
                                      required
                                      placeholder="Jelaskan insiden yang ditemukan secara detail...">{{ old('laporDesc') }}</textarea>
                            @error('laporDesc')
                            <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row cols-3">
                            <div class="form-field">
                                <label class="lapor-label" for="riskType">Jenis Risiko</label>
                                <input type="text" id="riskType" name="riskType"
                                       class="lapor-input"
                                       value="{{ old('riskType') }}"
                                       placeholder="Misal: XSS, SQL Injection">
                            </div>
                            <div class="form-field">
                                <label class="lapor-label" for="riskLevel">Tingkat Risiko</label>
                                <select id="riskLevel" name="riskLevel" class="lapor-select lapor-input">
                                    <option value="" disabled {{ old('riskLevel') ? '' : 'selected' }}>Pilih...</option>
                                    <option value="Low"      {{ old('riskLevel') == 'Low'      ? 'selected' : '' }}>Low</option>
                                    <option value="Medium"   {{ old('riskLevel') == 'Medium'   ? 'selected' : '' }}>Medium</option>
                                    <option value="High"     {{ old('riskLevel') == 'High'     ? 'selected' : '' }}>High</option>
                                    <option value="Critical" {{ old('riskLevel') == 'Critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label class="lapor-label" for="cvssScore">CVSS Score</label>
                                <input type="number" step="0.1" min="0" max="10"
                                       id="cvssScore" name="cvssScore"
                                       class="lapor-input"
                                       value="{{ old('cvssScore') }}"
                                       placeholder="0.0 – 10.0">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-field">
                                <label class="lapor-label" for="videoUrl">URL Bukti Video</label>
                                <input type="url" id="videoUrl" name="videoUrl"
                                       class="lapor-input"
                                       value="{{ old('videoUrl') }}"
                                       placeholder="https://youtube.com/...">
                            </div>
                            <div class="form-field">
                                <label class="lapor-label" for="reference">Referensi (Opsional)</label>
                                <input type="text" id="reference" name="reference"
                                       class="lapor-input"
                                       value="{{ old('reference') }}"
                                       placeholder="CVE-xxxx, link artikel, dll.">
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="lapor-label" for="recommendation">Rekomendasi Perbaikan</label>
                            <input type="text" id="recommendation" name="recommendation"
                                   class="lapor-input"
                                   value="{{ old('recommendation') }}"
                                   placeholder="Saran perbaikan yang Anda rekomendasikan">
                        </div>

                        <div class="form-field">
                            <label class="lapor-label" for="proofPic">
                                Screenshot Bukti <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--mid);">(PNG/JPG, maks. 2MB)</span>
                            </label>
                            <input type="file" id="proofPic" name="proofPic"
                                   class="lapor-input"
                                   accept="image/png, image/jpeg">
                        </div>

                        <div class="captcha-box">
                            <label class="lapor-label" for="captcha">
                                <i class="bi bi-shield-lock" style="color:var(--navy)"></i>
                                Verifikasi Keamanan — Ketik "JKT" untuk melanjutkan <span class="req">*</span>
                            </label>
                            <input type="text" id="captcha" name="captcha"
                                   class="lapor-input"
                                   placeholder="Masukkan kode verifikasi" required
                                   autocomplete="off">
                        </div>

                        <div class="step-nav">
                            <button type="button" class="btn-step-prev btn-prev">
                                <i class="bi bi-arrow-left"></i> Sebelumnya
                            </button>
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-send-fill"></i> Kirim Laporan Resmi
                            </button>
                        </div>
                    </div>

                </div>{{-- /.form-card --}}
            </form>
        </div>{{-- /form column --}}


        {{-- ===================================================
             SIDEBAR COLUMN
             =================================================== --}}
        <aside class="lapor-sidebar" aria-label="Informasi laporan">

            {{-- Progress tracker --}}
            <div class="sidebar-card">
                <div class="sidebar-card__title">
                    <i class="bi bi-list-check"></i> Progres Laporan
                </div>
                <div class="sidebar-progress">
                    <div class="progress-step is-active" id="prog-1">
                        <div class="progress-dot">1</div>
                        <span>Data Pelapor</span>
                    </div>
                    <div class="progress-step" id="prog-2">
                        <div class="progress-dot">2</div>
                        <span>Data Website</span>
                    </div>
                    <div class="progress-step" id="prog-3">
                        <div class="progress-dot">3</div>
                        <span>Detail Laporan</span>
                    </div>
                </div>
            </div>

            {{-- Rules --}}
            <div class="sidebar-card">
                <div class="sidebar-card__title">
                    <i class="bi bi-info-circle"></i> Peraturan Laporan
                </div>
                <ul class="sidebar-rules">
                    <li>Lingkup domain yang dilaporkan adalah <strong>*.jakarta.go.id</strong></li>
                    <li>Isi nama lengkap dan kontak yang benar untuk keperluan <strong>sertifikat apresiasi</strong>.</li>
                    <li>Proses validasi memerlukan waktu maksimal <strong>7 hari kerja</strong>.</li>
                    <li>Jika dinyatakan VALID dan tidak duplikat, sertifikat dikirim maksimal <strong>4 hari kerja</strong> setelahnya.</li>
                    <li class="rule-important">
                        <i class="bi bi-person-fill-lock"></i>
                        Satu temuan hanya untuk satu orang pelapor.
                    </li>
                </ul>
            </div>

            {{-- Urgent contact --}}
            <div class="sidebar-card" style="border-top-color: var(--alert);">
                <div class="sidebar-card__title" style="color:var(--alert);">
                    <i class="bi bi-telephone-fill" style="color:var(--alert)"></i> Insiden Kritis?
                </div>
                <p style="font-size:13px;color:var(--mid);line-height:1.65;margin-bottom:12px;">
                    Untuk insiden yang membutuhkan penanganan segera (serangan aktif, kebocoran data besar), hubungi kami langsung:
                </p>
                <a href="tel:+6281388870152"
                   style="display:flex;align-items:center;gap:8px;font-family:var(--font-display);font-size:15px;font-weight:800;color:var(--navy);letter-spacing:0.02em;text-decoration:none;">
                    <i class="bi bi-telephone"></i> (+62) 813-8887-0152
                </a>
            </div>

        </aside>

    </div>
</div>


{{-- ============================================================
     VANILLA JS STEP WIZARD — no framework required
     ============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const TOTAL_STEPS = 3;
    const stepCurrentInput = document.getElementById('current-step');

    // All .form-step divs in order
    const steps     = Array.from(document.querySelectorAll('.form-step'));
    // Top indicator tabs
    const indicators = [
        document.getElementById('ind-1'),
        document.getElementById('ind-2'),
        document.getElementById('ind-3'),
    ];
    // Sidebar progress dots
    const progSteps = [
        document.getElementById('prog-1'),
        document.getElementById('prog-2'),
        document.getElementById('prog-3'),
    ];

    let currentStep = 1;

    /* -------------------------------------------------------
       showStep(n) — renders the correct step, updates UI
       ------------------------------------------------------- */
    function showStep(n) {
        steps.forEach((el, i) => {
            el.classList.toggle('is-visible', i === n - 1);
        });

        indicators.forEach((el, i) => {
            el.classList.remove('is-active', 'is-done');
            if (i === n - 1) el.classList.add('is-active');
            if (i < n - 1)  el.classList.add('is-done');
            el.setAttribute('aria-selected', i === n - 1 ? 'true' : 'false');
        });

        progSteps.forEach((el, i) => {
            el.classList.remove('is-active', 'is-done');
            if (i === n - 1) el.classList.add('is-active');
            if (i < n - 1)  el.classList.add('is-done');
            // Update dot icon for completed steps
            const dot = el.querySelector('.progress-dot');
            if (i < n - 1) {
                dot.innerHTML = '<i class="bi bi-check"></i>';
            } else {
                dot.textContent = i + 1;
            }
        });

        stepCurrentInput.value = n;
        currentStep = n;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* -------------------------------------------------------
       validateStep(n) — checks required fields in step n
       returns true if all pass
       ------------------------------------------------------- */
    function validateStep(n) {
        const stepEl = steps[n - 1];
        const required = Array.from(stepEl.querySelectorAll('[required]'));
        let ok = true;

        required.forEach(input => {
            const invalid = !input.value.trim();
            input.classList.toggle('is-invalid', invalid);
            if (invalid) ok = false;
        });

        return ok;
    }

    /* -------------------------------------------------------
       NEXT buttons
       ------------------------------------------------------- */
    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!validateStep(currentStep)) {
                // Small shake on the first invalid field
                const first = steps[currentStep - 1].querySelector('.is-invalid');
                if (first) {
                    first.focus();
                    first.style.transition = 'none';
                    first.style.transform = 'translateX(-4px)';
                    setTimeout(() => {
                        first.style.transition = 'transform 0.2s ease';
                        first.style.transform = 'translateX(0)';
                    }, 80);
                }
                return;
            }
            if (currentStep < TOTAL_STEPS) showStep(currentStep + 1);
        });
    });

    /* -------------------------------------------------------
       PREV buttons
       ------------------------------------------------------- */
    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', function () {
            if (currentStep > 1) showStep(currentStep - 1);
        });
    });

    /* -------------------------------------------------------
       FINAL SUBMIT — captcha + file validation
       ------------------------------------------------------- */
    document.getElementById('lapor-form').addEventListener('submit', function (e) {
        // Captcha check
        const captcha = document.getElementById('captcha');
        if (captcha.value.trim().toUpperCase() !== 'JKT') {
            e.preventDefault();
            captcha.classList.add('is-invalid');
            captcha.focus();
            captcha.placeholder = 'Kode salah — ketik JKT';
            return;
        }

        // File size/type check
        const file = document.getElementById('proofPic');
        if (file && file.files && file.files[0]) {
            const f = file.files[0];
            if (!['image/png', 'image/jpeg', 'image/jpg'].includes(f.type)) {
                e.preventDefault();
                file.classList.add('is-invalid');
                file.focus();
                alert('Tipe file harus PNG atau JPG/JPEG.');
                return;
            }
            if (f.size > 2 * 1024 * 1024) {
                e.preventDefault();
                file.classList.add('is-invalid');
                file.focus();
                alert('Ukuran file melebihi batas 2MB.');
                return;
            }
        }
    });

    // Remove invalid class on user input
    document.querySelectorAll('.lapor-input, .lapor-select, .lapor-textarea').forEach(el => {
        el.addEventListener('input', function () {
            this.classList.remove('is-invalid');
        });
    });

    // Init
    showStep(1);
});
</script>

@endsection