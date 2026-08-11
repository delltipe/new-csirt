{{--
    thank-you.blade.php  (resources/views/bug-hunter/thank-you.blade.php)
    Shown after a successful incident submission. Displays the ticket number.
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
    font-weight: 800;
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
    font-weight: 800;
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

.ticket-box {
    background: var(--navy-tint);
    border: 1px solid var(--navy);
    border-left: 4px solid var(--navy);
    padding: 18px 24px;
    margin-bottom: 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.ticket-box__label {
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--navy);
    margin-bottom: 4px;
}

.ticket-box__value {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.04em;
    color: var(--navy);
    line-height: 1;
}

.ticket-box__copy {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--navy);
    color: var(--white);
    border: none;
    font-family: var(--font-body);
    font-size: 12.5px;
    font-weight: 600;
    padding: 9px 16px;
    cursor: pointer;
    transition: background var(--ease);
}
.ticket-box__copy:hover { background: var(--navy-dim); }

.next-steps-title {
    font-family: var(--font-display);
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 20px;
}

.flow-steps {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--border);
    border: 1px solid var(--border);
    margin-bottom: 32px;
}

.flow-step {
    background: var(--white);
    padding: 20px 16px;
    text-align: center;
}

.flow-step__icon {
    font-size: 22px;
    color: var(--navy);
    margin-bottom: 10px;
}

.flow-step__label {
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--ink);
}

.flow-step__desc {
    font-size: 12px;
    color: var(--mid);
    margin-top: 4px;
    line-height: 1.5;
}

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
    .flow-steps { grid-template-columns: 1fr; }
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
                    Laporan insiden Anda telah berhasil dikirim kepada tim JakartaProv-CSIRT.
                </p>
            </div>

            <div class="thankyou-card__body">
                @if ($tiketNo)
                <div class="ticket-box">
                    <div>
                        <div class="ticket-box__label">No Tiket Laporan</div>
                        <div class="ticket-box__value">{{ $tiketNo }}</div>
                    </div>
                    <button type="button" class="ticket-box__copy" id="btn-copy-ticket">
                        <i class="bi bi-clipboard" aria-hidden="true"></i> Salin
                    </button>
                </div>
                @endif

                <p class="next-steps-title">Alur Penanganan Laporan</p>

                <div class="flow-steps">
                    <div class="flow-step">
                        <div class="flow-step__icon"><i class="bi bi-shield-check" aria-hidden="true"></i></div>
                        <div class="flow-step__label">Cegah</div>
                        <div class="flow-step__desc">Validasi & verifikasi temuan</div>
                    </div>
                    <div class="flow-step">
                        <div class="flow-step__icon"><i class="bi bi-wrench-adjustable" aria-hidden="true"></i></div>
                        <div class="flow-step__label">Tangani</div>
                        <div class="flow-step__desc">Tindak lanjut & perbaikan</div>
                    </div>
                    <div class="flow-step">
                        <div class="flow-step__icon"><i class="bi bi-arrow-repeat" aria-hidden="true"></i></div>
                        <div class="flow-step__label">Pulihkan</div>
                        <div class="flow-step__desc">Pemulihan & pemantauan</div>
                    </div>
                </div>

                <div class="thankyou-actions">
                    <a href="{{ route('bug-hunter.dashboard') }}" class="btn-back-home">
                        <i class="bi bi-house-fill"></i> Lihat Status Laporan
                    </a>
                    <a href="{{ route('bug-hunter.create') }}" class="btn-report-another">
                        <i class="bi bi-plus-circle"></i> Lapor Insiden Lain
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('btn-copy-ticket').addEventListener('click', function () {
    const value = document.querySelector('.ticket-box__value');
    if (!value) return;
    navigator.clipboard.writeText(value.textContent.trim());
    this.innerHTML = '<i class="bi bi-check-lg"></i> Tersalin';
});
</script>

@endsection
