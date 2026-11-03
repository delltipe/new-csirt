@extends('layouts.app')

@section('content')

@include('secaw.partials.styles')

<style>
/* ============================================================
   PAGE HEADER BAND
   ============================================================ */
.secaw-header {
    background: var(--ink);
    padding: 52px 0 44px;
    position: relative;
    overflow: hidden;
}

.secaw-header .container {
    position: relative;
    z-index: 1;
}

.secaw-header__eyebrow {
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #D6E4F8;
    margin-bottom: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.secaw-header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 5vw, 54px);
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1;
    margin: 0 0 12px;
}

.secaw-header__sub {
    font-size: 15px;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.72);
    line-height: 1.65;
    max-width: 680px;
    margin: 0;
}

/* ============================================================
   LAYOUT SHELL
   ============================================================ */
.secaw-shell {
    background: var(--mist);
    padding: 48px 0 80px;
}

.secaw-layout {
    display: flex;
    flex-direction: column;
    gap: 36px;
}

/* ============================================================
   SECTION HEADINGS & ACCENTS
   ============================================================ */
.secaw-section-header {
    margin-bottom: 20px;
    border-bottom: 2px solid var(--border);
    padding-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 12px;
}

.secaw-section-title {
    font-family: var(--font-display);
    font-size: 24px;
    font-weight: 800;
    letter-spacing: 0.01em;
    text-transform: uppercase;
    color: var(--ink);
    margin: 0;
}

.secaw-section-desc {
    font-size: 14px;
    color: var(--mid);
    margin: 4px 0 0;
}

/* ============================================================
   BADGES & LABELS
   ============================================================ */
.secaw-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 3px 8px;
    border-radius: 0;
    line-height: 1.2;
}

.secaw-badge--available {
    background: #E6F4EA;
    color: #137333;
    border: 1px solid #CEEAD6;
}

.secaw-badge--dev {
    background: var(--navy-tint);
    color: var(--navy);
    border: 1px solid #C4D7F2;
}

.secaw-badge--info {
    background: var(--mist);
    color: var(--mid);
    border: 1px solid var(--border);
}

/* ============================================================
   HERO SPOTLIGHT CARD (ASSESSMENT PREVIEW)
   ============================================================ */
.secaw-hero-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-top: 4px solid var(--navy);
    padding: 36px;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 36px;
    align-items: center;
}

.secaw-hero-card__body h2 {
    font-family: var(--font-display);
    font-size: clamp(22px, 3vw, 30px);
    font-weight: 800;
    color: var(--ink);
    margin: 12px 0 10px;
    line-height: 1.2;
}

.secaw-hero-card__body p {
    font-size: 14.5px;
    color: var(--mid);
    line-height: 1.7;
    margin-bottom: 20px;
}

.secaw-pillar-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    list-style: none;
    padding: 0;
    margin: 0 0 24px;
}

.secaw-pillar-item {
    font-size: 13px;
    color: var(--ink);
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.4;
}

.secaw-pillar-item i {
    color: var(--navy);
    font-size: 14px;
    margin-top: 1px;
    flex-shrink: 0;
}

.secaw-hero-card__side {
    background: var(--mist);
    border: 1px solid var(--border);
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.secaw-metric-box {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.secaw-metric-box__val {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 800;
    color: var(--navy);
    line-height: 1;
}

.secaw-metric-box__lbl {
    font-size: 12px;
    font-weight: 600;
    color: var(--mid);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.secaw-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 20px;
    font-family: var(--font-display);
    font-size: 13.5px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    border-radius: 0;
    text-decoration: none;
    transition: background var(--ease), color var(--ease), border-color var(--ease);
    border: 1px solid transparent;
}

.secaw-btn:focus-visible {
    outline: 3px solid var(--navy);
    outline-offset: 2px;
}

.secaw-btn--primary {
    background: var(--navy);
    color: var(--white);
    border-color: var(--navy);
}

.secaw-btn--primary:hover {
    background: var(--navy-mid);
    border-color: var(--navy-mid);
    color: var(--white);
}

.secaw-btn--secondary {
    background: var(--white);
    color: var(--navy);
    border: 1px solid var(--border);
}

.secaw-btn--secondary:hover {
    background: var(--navy-tint);
    border-color: var(--navy);
    color: var(--navy);
}

.secaw-btn--disabled {
    background: #E2E6ED;
    color: var(--mid);
    border-color: var(--border);
    cursor: not-allowed;
}

/* ============================================================
   GRID OF MODULES
   ============================================================ */
.secaw-modules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.secaw-module-card {
    background: var(--white);
    border: 1px solid var(--border);
    padding: 28px 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform var(--ease), box-shadow var(--ease);
}

.secaw-module-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(10, 15, 26, 0.06);
}

.secaw-module-card__header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
}

.secaw-module-icon {
    width: 44px;
    height: 44px;
    background: var(--navy-tint);
    color: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    border: 1px solid #C4D7F2;
    flex-shrink: 0;
}

.secaw-module-card__title {
    font-family: var(--font-display);
    font-size: 19px;
    font-weight: 800;
    color: var(--ink);
    margin: 0 0 10px;
    line-height: 1.3;
}

.secaw-module-card__desc {
    font-size: 13.5px;
    color: var(--mid);
    line-height: 1.6;
    margin-bottom: 24px;
    flex-grow: 1;
}

/* ============================================================
   CONTACT & INCIDENT ESCALATION SECTION
   ============================================================ */
.secaw-support-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.secaw-support-card {
    background: var(--white);
    border: 1px solid var(--border);
    padding: 28px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.secaw-support-card h3 {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 800;
    color: var(--ink);
    text-transform: uppercase;
    letter-spacing: 0.02em;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.secaw-contact-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.secaw-contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13.5px;
    color: var(--ink);
}

.secaw-contact-item i {
    color: var(--navy);
    font-size: 15px;
    width: 20px;
    text-align: center;
}

.secaw-contact-item a {
    color: var(--navy);
    font-weight: 600;
    text-decoration: none;
}

.secaw-contact-item a:hover {
    text-decoration: underline;
}

/* ============================================================
   ANALYTICS DASHBOARD SECTION
   ============================================================ */
.secaw-integrity-banner {
    background: var(--white);
    border: 1px solid var(--border);
    border-left: 4px solid var(--navy);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
}

.secaw-integrity-banner i {
    font-size: 22px;
    color: var(--navy);
    flex-shrink: 0;
}

.secaw-integrity-banner p {
    font-size: 13.5px;
    color: var(--ink);
    margin: 0;
    line-height: 1.6;
}

.secaw-analytics-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.secaw-chart-card {
    background: var(--white);
    border: 1px solid var(--border);
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 18px;
    position: relative;
    border-radius: 0;
}

.secaw-chart-card__header {
    display: flex;
    gap: 14px;
    align-items: flex-start;
}

.secaw-chart-icon {
    width: 40px;
    height: 40px;
    background: var(--navy-tint);
    color: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    border: 1px solid #C4D7F2;
    flex-shrink: 0;
}

.secaw-chart-title {
    font-family: var(--font-display);
    font-size: 16.5px;
    font-weight: 800;
    color: var(--ink);
    margin: 0 0 5px;
    line-height: 1.35;
}

.secaw-chart-subtitle {
    font-size: 12.5px;
    color: var(--mid);
    line-height: 1.5;
    margin: 0;
}

.secaw-chart-empty {
    background: var(--mist);
    border: 1px dashed var(--border);
    padding: 24px 18px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-height: 155px;
}

.secaw-chart-empty__icon {
    font-size: 26px;
    color: var(--mid);
    opacity: 0.55;
}

.secaw-chart-empty__msg {
    font-size: 12.5px;
    color: var(--mid);
    line-height: 1.5;
    max-width: 420px;
    margin: 0;
}

.secaw-chart-meta {
    background: var(--white);
    border-top: 1px solid var(--border);
    padding-top: 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.secaw-chart-meta__label {
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--navy);
    margin: 0;
}

.secaw-chart-meta__desc {
    font-size: 11.5px;
    color: var(--mid);
    line-height: 1.5;
    margin: 0;
}

/* ============================================================
   RESPONSIVE OVERRIDES
   ============================================================ */
@media (max-width: 992px) {
    .secaw-hero-card {
        grid-template-columns: 1fr;
        padding: 28px;
    }
    .secaw-support-grid {
        grid-template-columns: 1fr;
    }
    .secaw-analytics-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .secaw-shell {
        padding: 32px 0 56px;
    }
    .secaw-pillar-list {
        grid-template-columns: 1fr;
    }
    .secaw-hero-card {
        padding: 20px;
    }
    .secaw-module-card {
        padding: 20px;
    }
}
</style>

{{-- ============================================================
     HERO BANNER
     ============================================================ --}}
<header class="secaw-header" aria-label="Header Security Awareness">
    <div class="container">
        <p class="secaw-header__eyebrow">
            <i class="bi bi-shield-check" aria-hidden="true"></i>
            Edukasi & Kesadaran Siber
        </p>
        <h1>Security Awareness (SecAw)</h1>
        <p class="secaw-header__sub">
            Pusat edukasi terpadu, instrumen evaluasi mandiri, dan penguatan budaya kesadaran keamanan siber bagi aparatur pemerintah daerah serta masyarakat Provinsi DKI Jakarta.
        </p>
    </div>
</header>

{{-- ============================================================
     BREADCRUMB & SUBNAV
     ============================================================ --}}
@include('secaw.partials.subnav', ['activeTitle' => 'Dasbor Utama'])

{{-- ============================================================
     MAIN BODY
     ============================================================ --}}
<div class="secaw-shell">
    <div class="container">
        <div class="secaw-layout">

            {{-- 1. ASESMEN MANDIRI (HERO SPOTLIGHT) --}}
            <section class="secaw-hero-card" aria-labelledby="secaw-assessment-title">
                <div class="secaw-hero-card__body">
                    <div>
                        <span class="secaw-badge secaw-badge--dev">
                            <i class="bi bi-clock-history" aria-hidden="true"></i> Fase 3 — Tahap Pengembangan
                        </span>
                    </div>
                    <h2 id="secaw-assessment-title">Self Assessment Kesadaran Keamanan Siber</h2>
                    <p>
                        Uji pemahaman dan kesiapan keamanan siber Anda secara mandiri melalui 26 butir evaluasi terstandarisasi. Instrumen ini dirancang untuk mengukur kebiasaan siber personal serta kepatuhan tata kelola keamanan informasi di lingkungan kerja dan kehidupan digital sehari-hari.
                    </p>

                    <ul class="secaw-pillar-list" aria-label="Cakupan pilar asesmen mandiri">
                        <li class="secaw-pillar-item">
                            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                            <span><strong>Keamanan Teknis:</strong> Pengelolaan kata sandi, 2FA, pembaruan OS, Wi-Fi publik, dan mitigasi malware.</span>
                        </li>
                        <li class="secaw-pillar-item">
                            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                            <span><strong>Keamanan Sosial:</strong> Antisipasi phishing, etika privasi media sosial, dan penanganan manipulasi siber.</span>
                        </li>
                        <li class="secaw-pillar-item">
                            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                            <span><strong>Etika Informasi:</strong> Deteksi hoaks, konten manipulatif, dan perlindungan kerahasiaan data pribadi.</span>
                        </li>
                        <li class="secaw-pillar-item">
                            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                            <span><strong>Kepatuhan Hukum:</strong> Kesadaran regulasi UU ITE dan saluran pelaporan insiden resmi BSSN/CSIRT.</span>
                        </li>
                    </ul>

                    <div>
                        <button type="button" class="secaw-btn secaw-btn--disabled" disabled aria-disabled="true">
                            <i class="bi bi-ui-checks" aria-hidden="true"></i>
                            Kuesioner Sedang Dipersiapkan (Fase 3)
                        </button>
                    </div>
                </div>

                <aside class="secaw-hero-card__side" aria-label="Informasi parameter kuesioner">
                    <div class="secaw-metric-box">
                        <span class="secaw-metric-box__val">26</span>
                        <span class="secaw-metric-box__lbl">Indikator Pertanyaan</span>
                    </div>
                    <div class="secaw-metric-box">
                        <span class="secaw-metric-box__val">2 Kategori</span>
                        <span class="secaw-metric-box__lbl">16 Siber Teknis & 10 Siber Sosial</span>
                    </div>
                    <div class="secaw-metric-box">
                        <span class="secaw-metric-box__val">Skala 1–4</span>
                        <span class="secaw-metric-box__lbl">Rentang Skor Likert Terstandar</span>
                    </div>
                    <p style="font-size:12px; color:var(--mid); line-height:1.5; margin:0;">
                        Kalkulasi skor otomatis dan rekomendasi personal akan tersedia secara terintegrasi setelah implementasi Fase 3 diselesaikan.
                    </p>
                </aside>
            </section>

            {{-- 2. MODUL EDUKASI & MEDIA SIBER --}}
            <section aria-labelledby="secaw-modules-title">
                <div class="secaw-section-header">
                    <div>
                        <h2 class="secaw-section-title" id="secaw-modules-title">Modul & Referensi Keamanan Siber</h2>
                        <p class="secaw-section-desc">Eksplorasi materi edukasi, dokumen teknis, dan media pendukung kesadaran keamanan informasi.</p>
                    </div>
                </div>

                <div class="secaw-modules-grid">
                    {{-- Card 1: Panduan Teknis & Materi (TERSEDIA) --}}
                    <article class="secaw-module-card">
                        <div>
                            <div class="secaw-module-card__header">
                                <div class="secaw-module-icon">
                                    <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                </div>
                                <span class="secaw-badge secaw-badge--available">
                                    <i class="bi bi-check-lg" aria-hidden="true"></i> Tersedia
                                </span>
                            </div>
                            <h3 class="secaw-module-card__title">Panduan Teknis & SOP</h3>
                            <p class="secaw-module-card__desc">
                                Akses dokumen panduan resmi, pedoman pengamanan akun, pengoperasian sistem, dan instruksi penanganan kerentanan digital.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('secaw.materi') }}" class="secaw-btn secaw-btn--primary w-100">
                                Buka Materi <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                            <div class="mt-2 text-center">
                                <a href="{{ route('guides.index') }}" style="font-size: 11.5px; color: var(--navy); font-weight: 600; text-decoration: none;">
                                    Arsip Panduan Teknis Utama &rarr;
                                </a>
                            </div>
                        </div>
                    </article>

                    {{-- Card 2: Infografis Keamanan (TERSEDIA) --}}
                    <article class="secaw-module-card">
                        <div>
                            <div class="secaw-module-card__header">
                                <div class="secaw-module-icon">
                                    <i class="bi bi-images" aria-hidden="true"></i>
                                </div>
                                <span class="secaw-badge secaw-badge--available">
                                    <i class="bi bi-check-lg" aria-hidden="true"></i> Tersedia
                                </span>
                            </div>
                            <h3 class="secaw-module-card__title">Infografis Edukasi</h3>
                            <p class="secaw-module-card__desc">
                                Visualisasi praktis mengenai pencegahan kebocoran data, tips kata sandi yang kuat, dan kewaspadaan terhadap modus penipuan online.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('secaw.infografis') }}" class="secaw-btn secaw-btn--primary w-100">
                                Galeri Infografis <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                            <div class="mt-2 text-center">
                                <a href="{{ route('infographics.index') }}" style="font-size: 11.5px; color: var(--navy); font-weight: 600; text-decoration: none;">
                                    Katalog Infografis Utama &rarr;
                                </a>
                            </div>
                        </div>
                    </article>

                    {{-- Card 3: Video Webinar & Sosialisasi (TERSEDIA) --}}
                    <article class="secaw-module-card">
                        <div>
                            <div class="secaw-module-card__header">
                                <div class="secaw-module-icon">
                                    <i class="bi bi-play-circle" aria-hidden="true"></i>
                                </div>
                                <span class="secaw-badge secaw-badge--available">
                                    <i class="bi bi-check-lg" aria-hidden="true"></i> Tersedia
                                </span>
                            </div>
                            <h3 class="secaw-module-card__title">Video & Sosialisasi</h3>
                            <p class="secaw-module-card__desc">
                                Rekaman workshop, webinar literasi keamanan digital, dan paparan para praktisi siber seputar tren ancaman teknologi mutakhir.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('secaw.video') }}" class="secaw-btn secaw-btn--primary w-100">
                                Katalog Video <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>

                    {{-- Card 4: Direktori Tools Cybersecurity (TERSEDIA) --}}
                    <article class="secaw-module-card">
                        <div>
                            <div class="secaw-module-card__header">
                                <div class="secaw-module-icon">
                                    <i class="bi bi-tools" aria-hidden="true"></i>
                                </div>
                                <span class="secaw-badge secaw-badge--available">
                                    <i class="bi bi-check-lg" aria-hidden="true"></i> Tersedia
                                </span>
                            </div>
                            <h3 class="secaw-module-card__title">Tools Keamanan Siber</h3>
                            <p class="secaw-module-card__desc">
                                Kumpulan rujukan perangkat pemantauan kebocoran data (data breach), analisis reputasi domain/IP, dan pengujian kesadaran phishing publik.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('secaw.tools') }}" class="secaw-btn secaw-btn--primary w-100">
                                Direktori Tools <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                </div>
            </section>

            {{-- 3. DASBOR ANALITIK KESADARAN SIBER (8 CHART COMPONENTS) --}}
            <section aria-labelledby="secaw-analytics-title">
                <div class="secaw-section-header">
                    <div>
                        <h2 class="secaw-section-title" id="secaw-analytics-title">Dasbor Analitik Kesadaran Siber</h2>
                        <p class="secaw-section-desc">Pemantauan indikator kesadaran keamanan informasi teragregasi di lingkungan Pemerintah Provinsi DKI Jakarta.</p>
                    </div>
                    <div>
                        <span class="secaw-badge secaw-badge--dev">
                            <i class="bi bi-clock-history" aria-hidden="true"></i> Fase 4 — Integrasi Data
                        </span>
                    </div>
                </div>

                {{-- Banner Integritas Data --}}
                <div class="secaw-integrity-banner" role="region" aria-label="Informasi Integritas Data Asesmen">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                    <p>
                        <strong>Transparansi Data Asesmen:</strong> Belum ada sampel data asesmen mandiri tingkat provinsi yang terhimpun. Seluruh komponen analitik di bawah ini disiapkan untuk menyajikan kalkulasi riil dari partisipasi responden evaluasi mandiri (Fase 4), tanpa menggunakan estimasi, manipulasi angka, atau data tiruan.
                    </p>
                </div>

                <div class="secaw-analytics-grid">
                    {{-- 1. Tren Skor Kesadaran Siber Bulanan --}}
                    <article class="secaw-chart-card" aria-labelledby="chart-monthly-title">
                        <div>
                            <div class="secaw-chart-card__header">
                                <div class="secaw-chart-icon">
                                    <i class="bi bi-graph-up-arrow" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="secaw-chart-title" id="chart-monthly-title">Tren Skor Kesadaran Siber Bulanan</h3>
                                    <p class="secaw-chart-subtitle">Rata-rata perkembangan indeks kesadaran keamanan informasi per bulan dalam periode tahun berjalan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="secaw-chart-empty" role="status" aria-label="Status data tren bulanan">
                            <i class="bi bi-bar-chart secaw-chart-empty__icon" aria-hidden="true"></i>
                            <span class="secaw-badge secaw-badge--info">Belum Ada Data Terhimpun</span>
                            <p class="secaw-chart-empty__msg">
                                Belum ada data agregasi skor bulanan. Data akan terhimpun otomatis setelah kuesioner evaluasi mandiri aktif dan diselesaikan oleh responden.
                            </p>
                        </div>

                        <div class="secaw-chart-meta">
                            <span class="secaw-chart-meta__label">Kebutuhan Data (Fase 4)</span>
                            <p class="secaw-chart-meta__desc">
                                <strong>Dimensi:</strong> Periode bulan (Januari–Desember). <strong>Metrik:</strong> Nilai rata-rata skor asesmen (skala Likert 1.00–4.00 terstandar). Formula pembobotan definitif memerlukan konfirmasi teknis BSSA pada Fase 4.
                            </p>
                        </div>
                    </article>

                    {{-- 2. Distribusi Predikat Hasil Asesmen --}}
                    <article class="secaw-chart-card" aria-labelledby="chart-result-title">
                        <div>
                            <div class="secaw-chart-card__header">
                                <div class="secaw-chart-icon">
                                    <i class="bi bi-pie-chart" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="secaw-chart-title" id="chart-result-title">Distribusi Predikat Hasil Asesmen</h3>
                                    <p class="secaw-chart-subtitle">Proporsi klasifikasi tingkat kesadaran responden berdasarkan rentang skor evaluasi terstandarisasi.</p>
                                </div>
                            </div>
                        </div>

                        <div class="secaw-chart-empty" role="status" aria-label="Status data distribusi hasil">
                            <i class="bi bi-pie-chart secaw-chart-empty__icon" aria-hidden="true"></i>
                            <span class="secaw-badge secaw-badge--info">Belum Ada Data Terhimpun</span>
                            <p class="secaw-chart-empty__msg">
                                Menunggu akumulasi hasil evaluasi responden. Predikat akan dikelompokkan secara proporsional sesuai ambang batas kelulusan.
                            </p>
                        </div>

                        <div class="secaw-chart-meta">
                            <span class="secaw-chart-meta__label">Kebutuhan Data (Fase 4)</span>
                            <p class="secaw-chart-meta__desc">
                                <strong>Klasifikasi Rujukan:</strong> Baik Sekali (skor &gt; 3.25–4.00), Baik (skor &gt; 2.50–3.25), Kurang Baik (skor &gt; 1.75–2.50), dan Buruk (skor 1.00–1.75). <strong>Metrik:</strong> Persentase dan volume responden. Ambang batas persentase baku memerlukan konfirmasi regulasi provinsi.
                            </p>
                        </div>
                    </article>

                    {{-- 3. Skor Kesadaran Berdasarkan Kategori --}}
                    <article class="secaw-chart-card" aria-labelledby="chart-category-title">
                        <div>
                            <div class="secaw-chart-card__header">
                                <div class="secaw-chart-icon">
                                    <i class="bi bi-bar-chart-steps" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="secaw-chart-title" id="chart-category-title">Skor Kesadaran Berdasarkan Kategori</h3>
                                    <p class="secaw-chart-subtitle">Perbandingan capaian skor antara pilar siber teknis dan siber sosial terhadap indeks keseluruhan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="secaw-chart-empty" role="status" aria-label="Status data skor kategori">
                            <i class="bi bi-bar-chart-steps secaw-chart-empty__icon" aria-hidden="true"></i>
                            <span class="secaw-badge secaw-badge--info">Belum Ada Data Terhimpun</span>
                            <p class="secaw-chart-empty__msg">
                                Agregasi skor per pilar akan ditampilkan setelah responden mengisi 26 butir pertanyaan evaluasi mandiri.
                            </p>
                        </div>

                        <div class="secaw-chart-meta">
                            <span class="secaw-chart-meta__label">Kebutuhan Data (Fase 4)</span>
                            <p class="secaw-chart-meta__desc">
                                <strong>Dimensi Rujukan:</strong> Indeks Keseluruhan (26 Butir Pertanyaan), Pilar Siber Teknis (16 Butir Pertanyaan), dan Pilar Siber Sosial (10 Butir Pertanyaan). <strong>Metrik:</strong> Rata-rata skor Likert (skala 1.00–4.00).
                            </p>
                        </div>
                    </article>

                    {{-- 4. Distribusi Responden Berdasarkan Gender --}}
                    <article class="secaw-chart-card" aria-labelledby="chart-gender-title">
                        <div>
                            <div class="secaw-chart-card__header">
                                <div class="secaw-chart-icon">
                                    <i class="bi bi-gender-ambiguous" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="secaw-chart-title" id="chart-gender-title">Distribusi Responden Berdasarkan Gender</h3>
                                    <p class="secaw-chart-subtitle">Komposisi partisipasi responden evaluasi kesadaran siber berdasarkan jenis kelamin.</p>
                                </div>
                            </div>
                        </div>

                        <div class="secaw-chart-empty" role="status" aria-label="Status data demografi gender">
                            <i class="bi bi-gender-ambiguous secaw-chart-empty__icon" aria-hidden="true"></i>
                            <span class="secaw-badge secaw-badge--info">Belum Ada Data Demografi</span>
                            <p class="secaw-chart-empty__msg">
                                Belum ada data demografi gender yang terhimpun. Metrik akan diperbarui secara anonim dari data responden.
                            </p>
                        </div>

                        <div class="secaw-chart-meta">
                            <span class="secaw-chart-meta__label">Kebutuhan Data (Fase 4)</span>
                            <p class="secaw-chart-meta__desc">
                                <strong>Klasifikasi Rujukan:</strong> Pria dan Wanita. <strong>Metrik:</strong> Proporsi persentase dan volume responden.
                            </p>
                        </div>
                    </article>

                    {{-- 5. Distribusi Responden Berdasarkan Jenjang Pendidikan --}}
                    <article class="secaw-chart-card" aria-labelledby="chart-education-title">
                        <div>
                            <div class="secaw-chart-card__header">
                                <div class="secaw-chart-icon">
                                    <i class="bi bi-mortarboard" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="secaw-chart-title" id="chart-education-title">Distribusi Responden Berdasarkan Jenjang Pendidikan</h3>
                                    <p class="secaw-chart-subtitle">Sebaran demografi partisipasi responden menurut jenjang pendidikan formal terakhir.</p>
                                </div>
                            </div>
                        </div>

                        <div class="secaw-chart-empty" role="status" aria-label="Status data demografi pendidikan">
                            <i class="bi bi-mortarboard secaw-chart-empty__icon" aria-hidden="true"></i>
                            <span class="secaw-badge secaw-badge--info">Belum Ada Data Demografi</span>
                            <p class="secaw-chart-empty__msg">
                                Menunggu pengisian data latar belakang pendidikan oleh responden terverifikasi.
                            </p>
                        </div>

                        <div class="secaw-chart-meta">
                            <span class="secaw-chart-meta__label">Kebutuhan Data (Fase 4)</span>
                            <p class="secaw-chart-meta__desc">
                                <strong>Klasifikasi Rujukan (Kuesioner):</strong> SD, SMP, SMA, D1, D2, D3, D4/S1, S2, dan S3 (9 jenjang). <strong>Metrik:</strong> Jumlah responden dan persentase per jenjang.
                            </p>
                        </div>
                    </article>

                    {{-- 6. Distribusi Responden Berdasarkan Kelompok Usia --}}
                    <article class="secaw-chart-card" aria-labelledby="chart-age-title">
                        <div>
                            <div class="secaw-chart-card__header">
                                <div class="secaw-chart-icon">
                                    <i class="bi bi-people" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="secaw-chart-title" id="chart-age-title">Distribusi Responden Berdasarkan Kelompok Usia</h3>
                                    <p class="secaw-chart-subtitle">Sebaran demografi responden berdasarkan rentang usia produktif dan literasi siber digital.</p>
                                </div>
                            </div>
                        </div>

                        <div class="secaw-chart-empty" role="status" aria-label="Status data demografi usia">
                            <i class="bi bi-people secaw-chart-empty__icon" aria-hidden="true"></i>
                            <span class="secaw-badge secaw-badge--info">Belum Ada Data Demografi</span>
                            <p class="secaw-chart-empty__msg">
                                Belum ada sampel rentang usia responden. Data akan diagregasikan ke dalam kelompok umur terstandar.
                            </p>
                        </div>

                        <div class="secaw-chart-meta">
                            <span class="secaw-chart-meta__label">Kebutuhan Data (Fase 4)</span>
                            <p class="secaw-chart-meta__desc">
                                <strong>Kelompok Usia Rujukan (Kuesioner):</strong> 15–24 Tahun, 25–34 Tahun, 35–44 Tahun, 45–54 Tahun, 55–64 Tahun, dan 65 Tahun ke Atas. <strong>Metrik:</strong> Persentase sebaran umur responden.
                            </p>
                        </div>
                    </article>

                    {{-- 7. Distribusi Responden Berdasarkan Wilayah Domisili --}}
                    <article class="secaw-chart-card" aria-labelledby="chart-domicile-title">
                        <div>
                            <div class="secaw-chart-card__header">
                                <div class="secaw-chart-icon">
                                    <i class="bi bi-geo-alt" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="secaw-chart-title" id="chart-domicile-title">Distribusi Responden Berdasarkan Wilayah Domisili</h3>
                                    <p class="secaw-chart-subtitle">Sebaran geografis responden di seluruh wilayah administratif Pemerintah Provinsi DKI Jakarta.</p>
                                </div>
                            </div>
                        </div>

                        <div class="secaw-chart-empty" role="status" aria-label="Status data geografis domisili">
                            <i class="bi bi-geo-alt secaw-chart-empty__icon" aria-hidden="true"></i>
                            <span class="secaw-badge secaw-badge--info">Menunggu Data Geografis</span>
                            <p class="secaw-chart-empty__msg">
                                Menunggu data sebaran wilayah responden se-DKI Jakarta. Menampilkan seluruh 5 Kota Administrasi dan 1 Kabupaten Administrasi.
                            </p>
                        </div>

                        <div class="secaw-chart-meta">
                            <span class="secaw-chart-meta__label">Kebutuhan Data (Fase 4)</span>
                            <p class="secaw-chart-meta__desc">
                                <strong>Rujukan Asal (Kota):</strong> 8 Kecamatan Jakarta Barat &amp; Luar JakBar. <strong>Usulan Adaptasi Provinsi DKI Jakarta:</strong> 6 Wilayah Administratif (Kota Adm. Jakarta Pusat, Jakarta Utara, Jakarta Barat, Jakarta Selatan, Jakarta Timur, dan Kab. Adm. Kepulauan Seribu, serta Luar DKI Jakarta). <strong>Metrik:</strong> Persentase kontribusi per wilayah.
                            </p>
                        </div>
                    </article>

                    {{-- 8. Skor Kesadaran Berdasarkan Subkategori Keamanan --}}
                    <article class="secaw-chart-card" aria-labelledby="chart-subcategory-title">
                        <div>
                            <div class="secaw-chart-card__header">
                                <div class="secaw-chart-icon">
                                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h3 class="secaw-chart-title" id="chart-subcategory-title">Skor Kesadaran Berdasarkan Subkategori Keamanan</h3>
                                    <p class="secaw-chart-subtitle">Tingkat pemahaman responden yang dipetakan ke dalam domain spesifik perlindungan informasi digital.</p>
                                </div>
                            </div>
                        </div>

                        <div class="secaw-chart-empty" role="status" aria-label="Status data skor subkategori">
                            <i class="bi bi-shield-check secaw-chart-empty__icon" aria-hidden="true"></i>
                            <span class="secaw-badge secaw-badge--info">Belum Ada Data Terhimpun</span>
                            <p class="secaw-chart-empty__msg">
                                Analisis per domain siber akan dihitung otomatis dari agregasi butir pertanyaan kuesioner asesmen mandiri.
                            </p>
                        </div>

                        <div class="secaw-chart-meta">
                            <span class="secaw-chart-meta__label">Kebutuhan Data (Fase 4)</span>
                            <p class="secaw-chart-meta__desc">
                                <strong>Subkategori Rujukan (11 Domain):</strong> Syarat &amp; Ketentuan Instalasi, Kata Sandi, Internet &amp; Wifi, Keamanan Perangkat, Aduan Insiden Teknis, Hukum/Regulasi Teknis, Rekayasa Sosial, Konten Negatif, Aktivitas Media Sosial, Aduan Insiden Sosial, dan Hukum/Regulasi Sosial. <strong>Metrik:</strong> Nilai rata-rata skor Likert (skala 1.00–4.00).
                            </p>
                        </div>
                    </article>
                </div>
            </section>

            {{-- 4. SALURAN PELAPORAN INSIDEN & KONTEN NEGATIF --}}
            <section aria-labelledby="secaw-contacts-title">
                <div class="secaw-section-header">
                    <div>
                        <h2 class="secaw-section-title" id="secaw-contacts-title">Eskalasi Insiden & Pelaporan Konten</h2>
                        <p class="secaw-section-desc">Pusat penanganan insiden siber resmi Pemerintah Provinsi DKI Jakarta dan kanal aduan nasional.</p>
                    </div>
                </div>

                <div class="secaw-support-grid">
                    {{-- CSIRT DKI Jakarta --}}
                    <div class="secaw-support-card">
                        <h3><i class="bi bi-shield-exclamation text-primary" aria-hidden="true"></i> Tanggap Insiden Siber Jakarta</h3>
                        <p style="font-size:13.5px; color:var(--mid); margin:0; line-height:1.6;">
                            Jika menemukan indikasi peretasan, insiden kebocoran data, atau kerentanan pada sistem elektronik Pemerintah Provinsi DKI Jakarta:
                        </p>
                        <ul class="secaw-contact-list">
                            <li class="secaw-contact-item">
                                <i class="bi bi-whatsapp" aria-hidden="true"></i>
                                <span>Hotline Resmi: <a href="https://wa.me/6281388870152" target="_blank" rel="noopener">0813-8887-0152</a></span>
                            </li>
                            <li class="secaw-contact-item">
                                <i class="bi bi-envelope" aria-hidden="true"></i>
                                <span>Email Tanggap Darurat: <a href="mailto:csirt@jakarta.go.id">csirt@jakarta.go.id</a></span>
                            </li>
                            <li class="secaw-contact-item">
                                <i class="bi bi-megaphone" aria-hidden="true"></i>
                                <span>Portal Laporan: <a href="{{ route('login') }}">Lapor Insiden Siber (Bug Hunter)</a></span>
                            </li>
                        </ul>
                    </div>

                    {{-- Saluran Nasional BSSN & Komdigi --}}
                    <div class="secaw-support-card">
                        <h3><i class="bi bi-globe2 text-primary" aria-hidden="true"></i> Kanal Pelaporan Nasional</h3>
                        <p style="font-size:13.5px; color:var(--mid); margin:0; line-height:1.6;">
                            Kanal rujukan nasional untuk penanganan konten negatif, penipuan online, dan koordinasi keamanan siber nasional:
                        </p>
                        <ul class="secaw-contact-list">
                            <li class="secaw-contact-item">
                                <i class="bi bi-shield-shaded" aria-hidden="true"></i>
                                <span>BSSN RI: <a href="mailto:bantuan70@bssn.go.id">bantuan70@bssn.go.id</a> | (021) 78833610</span>
                            </li>
                            <li class="secaw-contact-item">
                                <i class="bi bi-chat-left-dots" aria-hidden="true"></i>
                                <span>Aduan Konten Komdigi: <a href="https://aduankonten.id" target="_blank" rel="noopener">https://aduankonten.id</a></span>
                            </li>
                            <li class="secaw-contact-item">
                                <i class="bi bi-telephone" aria-hidden="true"></i>
                                <span>Lapor Konten BSSN: <a href="https://wa.me/6281220020070" target="_blank" rel="noopener">0812-2002-0070</a></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

@endsection
