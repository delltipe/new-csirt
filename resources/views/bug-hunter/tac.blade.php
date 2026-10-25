@extends('layouts.app')

@section('content')
<style>
.tac-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}
.tac-header .container { position: relative; z-index: 1; }
.tac-header__eyebrow {
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #D6E4F8;
    margin-bottom: 10px;
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

/* Responsive Mobile */
@media (max-width: 640px) {
    .tac-card__head, .tac-terms, .tac-agree {
        padding: 24px 20px;
    }
    .tac-actions {
        flex-direction: column;
    }
    .btn-tac-submit, .btn-tac-cancel {
        justify-content: center;
        width: 100%;
        text-align: center;
    }
}

/* High-Contrast Mode Overrides (D3-03: Visible Distinguishable Disabled Button) */
html.accessibility-contrast-high .btn-tac-submit {
    background: #000080 !important;
    color: #FFFFFF !important;
    border: 2px solid #000000 !important;
}
html.accessibility-contrast-high .btn-tac-submit:hover {
    background: #000040 !important;
    color: #FFFFFF !important;
}
html.accessibility-contrast-high .btn-tac-submit:disabled {
    background: #FFFFFF !important;
    color: #000000 !important;
    border: 2px dashed #000000 !important;
    cursor: not-allowed !important;
    opacity: 0.8 !important;
}
html.accessibility-contrast-high a.btn-tac-cancel {
    background: #FFFFFF !important;
    color: #000000 !important;
    border: 2px solid #000000 !important;
    text-decoration: none !important;
}
html.accessibility-contrast-high a.btn-tac-cancel:hover {
    background: #000000 !important;
    color: #FFFFFF !important;
}
</style>

<div class="tac-header">
    <div class="container">
        <div class="tac-header__eyebrow">
            <i class="bi bi-file-earmark-text-fill" aria-hidden="true"></i>
            Langkah 1: Persetujuan Ketentuan (Simulasi Sandbox)
        </div>
        <h1 class="tac-header__title">Syarat &amp; Ketentuan Simulasi</h1>
        <p class="tac-header__sub">
            Pelajari ketentuan evaluasi dan kewajiban penggunaan data fiktif sebelum melanjutkan ke formulir pelaporan insiden siber.
        </p>
    </div>
</div>

<div class="tac-layout">
    <div class="container">
        <div class="tac-card">
            <div class="tac-card__head">
                <h2 class="tac-card__title">Ketentuan Program Simulasi Pelaporan</h2>
                <span class="tac-version">Versi {{ $version }} (Sandbox)</span>
            </div>

            {{-- Prominent Simulation Notice --}}
            <div style="background: var(--navy-tint); border: 1px solid var(--navy); border-left: 4px solid var(--navy); padding: 14px 18px; margin: 20px 36px 0; font-size: 13.5px; line-height: 1.6; color: var(--ink);">
                <div style="font-weight: 700; font-family: var(--font-display); text-transform: uppercase; margin-bottom: 4px; color: var(--navy); display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-shield-exclamation" aria-hidden="true"></i> Pemberitahuan Mode Pratinjau Publik
                </div>
                <div>
                    Ini adalah lingkungan simulasi publik JakartaProv-CSIRT. <strong>Pengunjung wajib menggunakan data insiden fiktif.</strong>
                    Dilarang keras memasukkan informasi pribadi nyata, kredensial, kata sandi, atau bukti insiden rahasia.
                    Seluruh proses disimpan pada basis data sandbox terisolasi dan tidak diteruskan ke tim kedaruratan riil.
                </div>
            </div>

            <div class="tac-terms" id="tac-terms" tabindex="0" role="region" aria-label="Ketentuan program pelaporan insiden. Gulir hingga bawah untuk mengaktifkan persetujuan.">
                <h3>1. Ruang Lingkup Simulasi</h3>
                <p>
                    Layanan ini disediakan sebagai sarana peninjauan dan evaluasi alur pengguna (user journey) portal pelaporan insiden siber Pemerintah Provinsi DKI Jakarta. Pengujian hanya berlaku pada skenario fiktif domain <strong>*.jakarta.go.id</strong>.
                </p>

                <h3>2. Larangan Penggunaan Data Riil & Aktivitas Berbahaya</h3>
                <ul>
                    <li><strong>Dilarang keras</strong> memasukkan identitas pribadi nyata, data warga, dokumen rahasia, atau bukti sensitif organisasi.</li>
                    <li><strong>Dilarang keras</strong> memasukkan kata sandi, kunci API, credential dump, atau token akses otentik.</li>
                    <li>Dilarang melakukan pengujian yang merusak, membebani server, atau mengganggu stabilitas infrastruktur publik.</li>
                </ul>

                <h3>3. Kepatuhan Data Fiktif</h3>
                <ul>
                    <li>Seluruh data insiden yang dikirimkan (kronologi, dampak, tautan bukti) harus berupa data rekaan/fiktif.</li>
                    <li>Bukti lampiran hanya diizinkan dalam bentuk tautan URL pengujian aman (maksimal 3 tautan). Unggah berkas biner dinonaktifkan demi alasan keamanan.</li>
                </ul>

                <h3>4. Isolasi Database Sandbox</h3>
                <ul>
                    <li>Laporan yang dikirim akan diberi nomor tiket berformat <code>DEMO-YYYY-XXXXXXXX</code>.</li>
                    <li>Seluruh data disimpan pada basis data pengujian sandbox terpisah dan tidak terhubung dengan antrean operasional darurat CSIRT DKI.</li>
                </ul>

                <h3>5. Kerahasiaan & Non-Persistensi Akun</h3>
                <p>
                    Akses evaluasi ini bersifat simulasi sesi tanpa pembuatan akun permanen. Data pengujian dibersihkan secara berkala sesuai kebijakan pemeliharaan sistem.
                </p>
            </div>

            <div class="tac-agree">
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('bug-hunter.agree') }}">
                    @csrf
                    <p style="font-size:12.5px;color:var(--mid);margin-bottom:12px;" id="tac-scroll-hint">Gulir ketentuan hingga bagian bawah, lalu centang persetujuan untuk melanjutkan.</p>
                    <label class="tac-check" for="agree">
                        <input type="checkbox" id="agree" name="agree" value="1" required
                               aria-describedby="tac-scroll-hint @error('agree') err-agree @enderror"
                               @error('agree') aria-invalid="true" @enderror>
                        <span>
                            Saya telah membaca dan menyetujui seluruh <strong>Syarat &amp; Ketentuan Simulasi</strong> di atas
                            (Versi {{ $version }}), serta menyatakan bahwa seluruh data yang saya masukkan adalah data fiktif.
                        </span>
                    </label>

                    @error('agree')
                    <div class="field-error" id="err-agree" role="alert" style="color:var(--alert);font-size:13px;margin-bottom:14px;">
                        <i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}
                    </div>
                    @enderror

                    <div class="tac-actions">
                        <button type="submit" class="btn-tac-submit" id="btn-agree" disabled>
                            <i class="bi bi-check2-circle" aria-hidden="true"></i> Saya Setuju &amp; Lanjutkan
                        </button>
                        <a href="{{ route('login') }}" class="btn-tac-cancel">
                            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Menu Masuk
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
    let hasReachedEnd = false;

    if (!terms || !agree || !btn) return;

    const checkScrollEnd = function () {
        // Tolerant difference of 20px, plus auto-complete if content fits without scrolling
        if ((terms.scrollHeight - terms.scrollTop - terms.clientHeight <= 20) || (terms.scrollHeight <= terms.clientHeight + 20)) {
            hasReachedEnd = true;
        }
    };

    const updateButtonState = function () {
        checkScrollEnd();
        btn.disabled = !(hasReachedEnd && agree.checked);
    };

    terms.addEventListener('scroll', updateButtonState);
    agree.addEventListener('change', updateButtonState);

    // Initial check (in case content already fits or starts at bottom)
    updateButtonState();
});
</script>
@endsection
