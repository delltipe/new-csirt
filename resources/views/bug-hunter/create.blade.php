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
    color: var(--muted-on-dark);
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
        <div class="validation-summary" role="alert">
            <p><i class="bi bi-exclamation-circle-fill"></i> Harap perbaiki kesalahan berikut:</p>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="form-card" method="POST"
              action="{{ route('bug-hunter.store') }}"
              enctype="multipart/form-data" novalidate>
            @csrf

            <h2 class="form-step__title">Data Insiden</h2>
            <div class="form-step__divider"></div>

            <div class="form-field">
                <label class="lapor-label" for="kategori_insiden">
                    Kategori Insiden <span class="req">*</span>
                </label>
                <select id="kategori_insiden" name="kategori_insiden"
                        class="lapor-select lapor-input @error('kategori_insiden') is-invalid @enderror">
                    <option value="" disabled {{ old('kategori_insiden') ? '' : 'selected' }}>Pilih kategori...</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ old('kategori_insiden') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                @error('kategori_insiden')
                <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label class="lapor-label" for="waktu_kejadian">
                        Waktu Kejadian <span class="req">*</span>
                    </label>
                    <input type="datetime-local" id="waktu_kejadian" name="waktu_kejadian"
                           class="lapor-input @error('waktu_kejadian') is-invalid @enderror"
                           value="{{ old('waktu_kejadian') }}" required>
                    @error('waktu_kejadian')
                    <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-field">
                    <label class="lapor-label" for="down_time">
                        Down Time <span class="req">*</span>
                    </label>
                    <input type="time" id="down_time" name="down_time"
                           class="lapor-input @error('down_time') is-invalid @enderror"
                           value="{{ old('down_time') }}" required>
                    @error('down_time')
                    <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-field">
                <label class="lapor-label" for="lokasi_url">
                    Lokasi Insiden / URL Validasi <span class="req">*</span>
                </label>
                <input type="url" id="lokasi_url" name="lokasi_url"
                       class="lapor-input @error('lokasi_url') is-invalid @enderror"
                       value="{{ old('lokasi_url') }}" required
                       placeholder="https://portal.jakarta.go.id/halaman/...">
                @error('lokasi_url')
                <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-field">
                <label class="lapor-label" for="deskripsi">
                    Deskripsi Kejadian <span class="req">*</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="5"
                          class="lapor-textarea @error('deskripsi') is-invalid @enderror"
                          required
                          placeholder="Jelaskan kronologi kejadian secara detail...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-field">
                <label class="lapor-label" for="tindakan_teknis">
                    Tindakan Teknis <span class="req">*</span>
                </label>
                <textarea id="tindakan_teknis" name="tindakan_teknis" rows="3"
                          class="lapor-textarea @error('tindakan_teknis') is-invalid @enderror"
                          required
                          placeholder="Langkah teknis yang telah Anda lakukan atau yang Anda rekomendasikan...">{{ old('tindakan_teknis') }}</textarea>
                @error('tindakan_teknis')
                <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <h2 class="form-step__title" style="font-size:20px; margin-top:8px;">Bukti Laporan</h2>
            <div class="form-step__divider"></div>

            <div class="evidence-head">
                <span class="evidence-hint">Maksimal 3 bukti. Setiap bukti: <strong>File</strong> (PNG/JPG/GIF/PDF, maks. 5MB) <strong>atau</strong> <strong>URL</strong>.</span>
            </div>

            <div id="evidence-list"></div>

            <div class="form-field">
                <button type="button" class="btn-add-evidence" id="btn-add-evidence">
                    <i class="bi bi-plus-circle" aria-hidden="true"></i> Tambah Bukti
                </button>
            </div>

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
    const list = document.getElementById('evidence-list');
    const addBtn = document.getElementById('btn-add-evidence');
    let count = 0;

    const oldBukti = @json(old('bukti', []));

    function template(index, jenis, urlValue) {
        const div = document.createElement('div');
        div.className = 'bukti-row';
        div.dataset.index = index;
        div.innerHTML =
            '<select name="bukti[' + index + '][jenis]" class="lapor-select lapor-input bukti-jenis">' +
                '<option value="file"' + (jenis === 'url' ? '' : ' selected') + '>File</option>' +
                '<option value="url"' + (jenis === 'url' ? ' selected' : '') + '>URL</option>' +
            '</select>' +
            '<div>' +
                '<input type="file" name="bukti[' + index + '][file]" class="lapor-input bukti-file" accept=".png,.jpg,.jpeg,.gif,.pdf" style="' + (jenis === 'url' ? 'display:none;' : '') + '">' +
                '<input type="url" name="bukti[' + index + '][url]" class="lapor-input bukti-url" placeholder="https://..." value="' + (urlValue || '') + '" style="' + (jenis === 'url' ? '' : 'display:none;') + '">' +
            '</div>' +
            '<button type="button" class="btn-remove-evidence" aria-label="Hapus bukti"><i class="bi bi-trash3"></i></button>';

        const jenisSel = div.querySelector('.bukti-jenis');
        const fileInput = div.querySelector('.bukti-file');
        const urlInput = div.querySelector('.bukti-url');

        jenisSel.addEventListener('change', function () {
            const isUrl = this.value === 'url';
            fileInput.style.display = isUrl ? 'none' : '';
            urlInput.style.display = isUrl ? '' : 'none';
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
})();
</script>

@endsection
