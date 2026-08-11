@extends('layouts.app')

@section('content')
<style>
.tac-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}
.tac-header::before {
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
.tac-header .container { position: relative; z-index: 1; }
.tac-header__eyebrow {
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
.tac-header__eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1px;
    background: rgba(255,255,255,0.25);
}
.tac-header__title {
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}
.tac-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255,255,255,0.5);
    max-width: 620px;
}

.tac-layout {
    padding: 48px 0 80px;
    background: var(--mist);
}

.tac-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 4px solid var(--navy);
    max-width: 860px;
    margin: 0 auto;
}

.tac-card__head {
    padding: 28px 36px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.tac-card__title {
    font-family: var(--font-display);
    font-size: 22px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
}

.tac-version {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--navy);
    background: var(--navy-tint);
    border: 1px solid var(--navy);
    padding: 5px 12px;
}

.tac-terms {
    max-height: 420px;
    overflow-y: auto;
    padding: 28px 36px;
    border-bottom: 1px solid var(--border);
}

.tac-terms h3 {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 24px 0 10px;
}
.tac-terms h3:first-child { margin-top: 0; }

.tac-terms p, .tac-terms li {
    font-size: 14px;
    line-height: 1.75;
    color: var(--mid);
}

.tac-terms ul {
    padding-left: 20px;
    margin-bottom: 8px;
}

.tac-agree {
    padding: 28px 36px;
}

.tac-check {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 24px;
    cursor: pointer;
}

.tac-check input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-top: 2px;
    accent-color: var(--navy);
    flex-shrink: 0;
}

.tac-check span {
    font-size: 14px;
    color: var(--ink);
    line-height: 1.6;
}

.tac-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-tac-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 13px 30px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
    text-decoration: none;
}
.btn-tac-submit:hover { background: var(--navy-dim); color: var(--white); }
.btn-tac-submit:disabled { background: var(--border); color: var(--mid); cursor: not-allowed; }

.btn-tac-cancel {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: var(--mid);
    border: 1px solid var(--border);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 500;
    padding: 12px 28px;
    text-decoration: none;
    transition: color var(--ease), border-color var(--ease);
}
.btn-tac-cancel:hover { color: var(--ink); border-color: var(--mid); }
</style>

<div class="tac-header">
    <div class="container">
        <div class="tac-header__eyebrow">
            <i class="bi bi-file-earmark-text-fill" aria-hidden="true"></i>
            Sebelum Melanjutkan
        </div>
        <h1 class="tac-header__title">Syarat & Ketentuan</h1>
        <p class="tac-header__sub">
            Baca seluruh ketentuan pelaporan insiden siber sebelum mengisi formulir. Persetujuan Anda tercatat untuk keperluan audit.
        </p>
    </div>
</div>

<div class="tac-layout">
    <div class="container">
        <div class="tac-card">
            <div class="tac-card__head">
                <h2 class="tac-card__title">Ketentuan Program Pelaporan Insiden</h2>
                <span class="tac-version">Versi {{ $version }}</span>
            </div>

            <div class="tac-terms" id="tac-terms">
                <h3>1. Ruang Lingkup</h3>
                <p>
                    Program ini terbuka untuk pelaporan insiden keamanan siber pada aset digital yang dikelola
                    Pemerintah Provinsi DKI Jakarta, khususnya domain <strong>*.jakarta.go.id</strong>.
                </p>

                <h3>2. Larangan</h3>
                <ul>
                    <li>Dilarang melakukan pengujian yang merusak, mengeksploitasi data, atau mengganggu layanan tanpa izin.</li>
                    <li>Dilarang mengakses, mengubah, atau menghapus data yang bukan milik Anda.</li>
                    <li>Dilarang membocorkan temuan kepada publik sebelum diselesaikan oleh tim CSIRT.</li>
                </ul>

                <h3>3. Keabsahan Laporan</h3>
                <ul>
                    <li>Laporan harus jujur dan dapat diverifikasi dengan bukti pendukung (file atau URL).</li>
                    <li>Satu temuan hanya diakui untuk satu pelapor. Laporan duplikat tidak akan diproses.</li>
                    <li>Informasi kontak yang diberikan harus benar untuk keperluan komunikasi dan sertifikat apresiasi.</li>
                </ul>

                <h3>4. Proses Penanganan</h3>
                <ul>
                    <li>Validasi dilakukan maksimal <strong>7 hari kerja</strong>.</li>
                    <li>Jika dinyatakan valid dan tidak duplikat, sertifikat apresiasi dikirim maksimal <strong>4 hari kerja</strong> setelahnya.</li>
                </ul>

                <h3>5. Kerahasiaan</h3>
                <p>
                    Data laporan Anda hanya digunakan untuk keperluan penanganan insiden dan tidak dibagikan kepada pihak
                    ketiga di luar keperluan tersebut.
                </p>
            </div>

            <div class="tac-agree">
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('bug-hunter.agree') }}">
                    @csrf
                    <label class="tac-check" for="agree">
                        <input type="checkbox" id="agree" name="agree" required>
                        <span>
                            Saya telah membaca dan menyetujui seluruh <strong>Syarat & Ketentuan</strong> di atas
                            (Versi {{ $version }}).
                        </span>
                    </label>

                    <div class="tac-actions">
                        <button type="submit" class="btn-tac-submit" id="btn-agree" disabled>
                            <i class="bi bi-check2-circle" aria-hidden="true"></i> Saya Setuju & Lanjutkan
                        </button>
                        <a href="{{ route('bug-hunter.dashboard') }}" class="btn-tac-cancel">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const terms = document.getElementById('tac-terms');
    const agree = document.getElementById('agree');
    const btn = document.getElementById('btn-agree');

    const checkState = function () {
        const scrolled = terms.scrollTop + terms.clientHeight >= terms.scrollHeight - 4;
        btn.disabled = !(scrolled && agree.checked);
    };

    terms.addEventListener('scroll', checkState);
    agree.addEventListener('change', checkState);
    checkState();
});
</script>
@endsection
