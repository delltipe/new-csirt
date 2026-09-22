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
}
.form-field { margin-bottom: 20px; }
.form-field:last-child { margin-bottom: 0; }

/* ============================================================
   EVIDENCE ROWS
   ============================================================ */
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

.btn-add-evidence {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    color: var(--navy);
    border: 1px solid var(--navy);
    font-family: var(--font-body);
    font-size: 13px;
    font-weight: 600;
    padding: 9px 18px;
    cursor: pointer;
    transition: background var(--ease), color var(--ease);
}
.btn-add-evidence:hover { background: var(--navy-tint); }
.btn-add-evidence:disabled { border-color: var(--border); color: var(--mid); cursor: not-allowed; }

.btn-remove-evidence {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 44px;
    background: transparent;
    color: var(--alert);
    border: 1px solid var(--alert);
    cursor: pointer;
    transition: background var(--ease), color var(--ease);
}
.btn-remove-evidence:hover { background: var(--alert); color: var(--white); }

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
    background: transparent;
    color: var(--mid);
    border: 1px solid var(--border);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 500;
    padding: 12px 24px;
    text-decoration: none;
    transition: color var(--ease), border-color var(--ease);
}
.btn-cancel:hover { color: var(--ink); border-color: var(--mid); }

/* Dark/high-contrast keeps help box readable (token-based, avoids generic button flatten) */
html.accessibility-contrast-dark .lapor-help-box { background: #1a1a1a; border-color: #333333; }
html.accessibility-contrast-dark button.lapor-help-link { background: #1a1a1a; color: #4DA6FF; border-color: #333333; }
html.accessibility-contrast-dark button.lapor-help-link:hover { background: #2a2a3e; border-color: #4DA6FF; color: #4DA6FF; }
html.accessibility-contrast-high .lapor-help-box { background: #FFFFFF; border-color: #000000; }
html.accessibility-contrast-high button.lapor-help-link { background: #FFFFFF; color: #000080; border-color: #000000; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 700px) {
    .form-row, .bukti-row { grid-template-columns: 1fr; }
    .form-card { padding: 24px 18px; }
    .submit-nav { flex-direction: column-reverse; align-items: stretch; }
    .btn-submit, .btn-cancel { justify-content: center; }
}
</style>

<div class="lapor-header">
    <div class="container">
        <div class="lapor-header__eyebrow">
            <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
            Formulir Laporan
        </div>
        <h1>Lapor Insiden Siber</h1>
        <p class="lapor-header__sub">
            Isi seluruh informasi kejadian secara lengkap. Bukti pendukung dapat berupa file atau URL (maksimal 3, masing-masing 5MB).
        </p>
    </div>
</div>

<div class="lapor-layout">
    <div class="container">

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
                    <input type="time" id="field-down-time" name="down_time"
                           class="lapor-input @error('down_time') is-invalid @enderror"
                           value="{{ old('down_time') }}" required
                           aria-describedby="hint-down-time @error('down_time') err-down-time @enderror"
                           @error('down_time') aria-invalid="true" @enderror>
                    <div class="field-hint" id="hint-down-time">Perkiraan durasi layanan tidak dapat diakses (format jam:menit). Isi 00:00 bila tidak ada gangguan layanan.</div>
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

            <fieldset style="border:none;padding:0;margin:0;">
            <legend class="form-step__title" style="font-size:20px; margin-top:8px; padding:0;">Bukti Laporan</legend>
            <div class="form-step__divider"></div>

            <div class="evidence-head">
                <span class="evidence-hint" id="hint-bukti">Maksimal 3 bukti. Setiap bukti: <strong>File</strong> (PNG/JPG/GIF/PDF, maks. 5MB) <strong>atau</strong> <strong>URL</strong> (diawali http:// atau https://). File tidak wajib bila sudah ada URL.</span>
            </div>

            @if ($errors->has('bukti') || collect($errors->keys())->contains(fn ($k) => str_starts_with($k, 'bukti')))
            <div class="field-error" id="err-bukti" role="alert" style="margin-bottom:12px;"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $errors->first('bukti') ?: $errors->first(collect($errors->keys())->first(fn ($k) => str_starts_with($k, 'bukti'))) }}</div>
            @endif

            <div id="field-bukti" role="group" aria-describedby="hint-bukti"></div>

            <div class="form-field">
                <button type="button" class="btn-add-evidence" id="btn-add-evidence" aria-describedby="hint-bukti">
                    <i class="bi bi-plus-circle" aria-hidden="true"></i> Tambah Bukti
                </button>
            </div>
            </fieldset>

            @include('components.captcha', ['question' => $captchaQuestion])

            <div class="submit-nav">
                <a href="{{ route('bug-hunter.dashboard') }}" class="btn-cancel">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i> Batal
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-send-fill"></i> Kirim Laporan
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

    function template(index, jenis, urlValue) {
        const div = document.createElement('div');
        div.className = 'bukti-row';
        div.dataset.index = index;
        div.innerHTML =
            '<div>' +
                '<label class="lapor-label" for="bukti-' + index + '-jenis" style="font-size:11px;">Jenis Bukti ' + (index + 1) + '</label>' +
                '<select id="bukti-' + index + '-jenis" name="bukti[' + index + '][jenis]" class="lapor-select lapor-input bukti-jenis">' +
                    '<option value="file"' + (jenis === 'url' ? '' : ' selected') + '>File</option>' +
                    '<option value="url"' + (jenis === 'url' ? ' selected' : '') + '>URL</option>' +
                '</select>' +
            '</div>' +
            '<div>' +
                '<label class="lapor-label bukti-file-label" for="bukti-' + index + '-file" style="font-size:11px;' + (jenis === 'url' ? 'display:none;' : '') + '">File Bukti ' + (index + 1) + ' (maks. 5MB)</label>' +
                '<input type="file" id="bukti-' + index + '-file" name="bukti[' + index + '][file]" class="lapor-input bukti-file" accept=".png,.jpg,.jpeg,.gif,.pdf" aria-describedby="hint-bukti" style="' + (jenis === 'url' ? 'display:none;' : '') + '">' +
                '<label class="lapor-label bukti-url-label" for="bukti-' + index + '-url" style="font-size:11px;' + (jenis === 'url' ? '' : 'display:none;') + '">URL Bukti ' + (index + 1) + '</label>' +
                '<input type="url" id="bukti-' + index + '-url" name="bukti[' + index + '][url]" class="lapor-input bukti-url" placeholder="https://..." value="' + (urlValue || '') + '" aria-describedby="hint-bukti" style="' + (jenis === 'url' ? '' : 'display:none;') + '">' +
            '</div>' +
            '<button type="button" class="btn-remove-evidence" aria-label="Hapus bukti ' + (index + 1) + '"><i class="bi bi-trash3" aria-hidden="true"></i></button>';

        const jenisSel = div.querySelector('.bukti-jenis');
        const fileInput = div.querySelector('.bukti-file');
        const urlInput = div.querySelector('.bukti-url');
        const fileLabel = div.querySelector('.bukti-file-label');
        const urlLabel = div.querySelector('.bukti-url-label');

        jenisSel.addEventListener('change', function () {
            const isUrl = this.value === 'url';
            fileInput.style.display = isUrl ? 'none' : '';
            urlInput.style.display = isUrl ? '' : 'none';
            fileLabel.style.display = isUrl ? 'none' : '';
            urlLabel.style.display = isUrl ? '' : 'none';
            if (isUrl) { urlInput.focus(); } else { fileInput.focus(); }
        });

        div.querySelector('.btn-remove-evidence').addEventListener('click', function () {
            div.remove();
            count--;
            updateAddBtn();
        });

        return div;
    }

    function updateAddBtn() {
        addBtn.disabled = count >= MAX_EVIDENCE;
    }

    function addRow(prefill) {
        if (count >= MAX_EVIDENCE) return;
        const index = count;
        const row = template(index, prefill ? (prefill.jenis || 'file') : 'file', prefill ? (prefill.url || '') : '');
        list.appendChild(row);
        count++;
        updateAddBtn();
    }

    addBtn.addEventListener('click', function () { addRow(); });

    // Restore any rows from a failed submission
    if (typeof oldBukti === 'object' && Object.keys(oldBukti).length) {
        Object.keys(oldBukti).forEach(function (key) {
            const val = oldBukti[key];
            if (val && val.jenis) addRow(val);
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
})();
</script>

@endsection
