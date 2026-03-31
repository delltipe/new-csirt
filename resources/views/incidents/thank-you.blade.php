{{--
    thank-you.blade.php  (resources/views/incidents/thank-you.blade.php)
    Shown after IncidentController@store successfully saves the report.
--}}
@extends('layouts.app')

@section('content')

<style>
.thankyou-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    background: var(--mist);
    padding: 80px 0;
}

.thankyou-card {
    max-width: 680px;
    margin: 0 auto;
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 4px solid var(--navy);
}

.thankyou-card__header {
    background: var(--navy-dim);
    padding: 40px 48px 36px;
    position: relative;
    overflow: hidden;
}
.thankyou-card__header::before {
    content: '✓';
    position: absolute;
    right: 32px;
    top: 50%;
    transform: translateY(-50%);
    font-family: var(--font-display);
    font-size: 140px;
    font-weight: 900;
    color: rgba(255,255,255,0.04);
    line-height: 1;
    pointer-events: none;
}

.thankyou-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.6);
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 5px 12px;
    margin-bottom: 16px;
}

.thankyou-card__header h1 {
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin-bottom: 10px;
}

.thankyou-card__header p {
    font-size: 14px;
    font-weight: 300;
    color: rgba(255,255,255,0.6);
    line-height: 1.7;
    max-width: 460px;
}

.thankyou-card__body {
    padding: 36px 48px 40px;
}

.next-steps-title {
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 20px;
}

.next-steps {
    list-style: none;
    padding: 0;
    margin: 0 0 32px;
    display: flex;
    flex-direction: column;
    gap: 0;
}

.next-step {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
}
.next-step:last-child { border-bottom: none; }

.next-step__num {
    width: 32px;
    height: 32px;
    background: var(--navy-tint);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 900;
    color: var(--navy);
    flex-shrink: 0;
    margin-top: 1px;
}

.next-step__text {
    font-size: 14px;
    color: var(--mid);
    line-height: 1.65;
}
.next-step__text strong { color: var(--ink); font-weight: 600; }

.thankyou-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-back-home {
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
    padding: 13px 28px;
    text-decoration: none;
    border: none;
    transition: background var(--ease);
}
.btn-back-home:hover { background: var(--navy-dim); color: var(--white); }

.btn-report-another {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: var(--navy);
    border: 1px solid var(--navy);
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 500;
    padding: 12px 28px;
    text-decoration: none;
    transition: background var(--ease), color var(--ease);
}
.btn-report-another:hover { background: var(--navy-tint); }

@media (max-width: 640px) {
    .thankyou-card__header, .thankyou-card__body { padding: 28px 24px; }
    .thankyou-actions { flex-direction: column; }
    .btn-back-home, .btn-report-another { justify-content: center; }
}
</style>

<div class="thankyou-page">
    <div class="container">
        <div class="thankyou-card">

            <div class="thankyou-card__header">
                <div class="thankyou-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Laporan Diterima
                </div>
                <h1>Terima Kasih<br>atas Laporan Anda</h1>
                <p>
                    Laporan insiden Anda telah berhasil dikirim kepada tim JakartaProv-CSIRT. Kami akan segera menindaklanjuti temuan Anda.
                </p>
            </div>

            <div class="thankyou-card__body">
                <p class="next-steps-title">Apa yang terjadi selanjutnya?</p>

                <ul class="next-steps">
                    <li class="next-step">
                        <div class="next-step__num">1</div>
                        <div class="next-step__text">
                            <strong>Analisis laporan</strong> — Tim kami akan menganalisis laporan Anda dalam waktu <strong>24 jam</strong> pada hari kerja.
                        </div>
                    </li>
                    <li class="next-step">
                        <div class="next-step__num">2</div>
                        <div class="next-step__text">
                            <strong>Verifikasi & validasi</strong> — Proses validasi bug bounty memerlukan waktu maksimal <strong>7 hari kerja</strong>.
                        </div>
                    </li>
                    <li class="next-step">
                        <div class="next-step__num">3</div>
                        <div class="next-step__text">
                            <strong>Tindak lanjut</strong> — Tim kami mungkin menghubungi Anda melalui email atau telepon jika memerlukan informasi tambahan.
                        </div>
                    </li>
                    <li class="next-step">
                        <div class="next-step__num">4</div>
                        <div class="next-step__text">
                            <strong>Sertifikat apresiasi</strong> — Jika laporan dinyatakan VALID dan tidak duplikat, sertifikat dikirim maksimal <strong>4 hari kerja</strong> setelahnya.
                        </div>
                    </li>
                </ul>

                <div class="thankyou-actions">
                    <a href="{{ route('home') }}" class="btn-back-home">
                        <i class="bi bi-house-fill"></i> Kembali ke Beranda
                    </a>
                    <a href="{{ route('incidents.create.step1') }}" class="btn-report-another">
                        <i class="bi bi-plus-circle"></i> Lapor Insiden Lain
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection