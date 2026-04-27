@extends('layouts.app')

@section('content')

<style>
    /* Tipografi & Header */
    .lapor-header {
        background: var(--ink, #0A0F1A);
        padding: 56px 0 48px;
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
    }
    .lapor-header__title {
        font-family: 'Barlow Condensed', sans-serif;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: -0.02em;
        color: #ffffff;
        margin: 0;
    }
    .lapor-header__eyebrow {
        font-family: 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.4);
        margin-bottom: 8px;
    }

    /* Layout Konten NYC Style */
    .profile-card {
        background: #ffffff;
        border-radius: 0; /* Kaku/Kotak sesuai NYC.gov */
        border: 1px solid #e2e8f0;
        border-left: 8px solid var(--navy, #003580); /* Aksen Biru Navy Utama */
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .profile-logo-container {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #eee;
    }

    /* Tombol & CTA (Red Removed) */
    .btn-barlow {
        font-family: 'Barlow Condensed', sans-serif;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
        padding: 12px 32px;
        transition: all 0.2s;
    }
    .btn-navy-bold {
        background-color: var(--navy, #003580);
        color: white;
        border: none;
    }
    .btn-navy-bold:hover {
        background-color: var(--navy-mid, #004099);
        color: white;
    }

    .cta-footer {
        background: var(--navy-dim, #002060);
        padding: 64px 0;
        border-top: 8px solid var(--navy, #003580); /* Red accent removed */
    }

    .section-title {
        font-family: 'Barlow Condensed', sans-serif;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--ink);
        letter-spacing: 0.02em;
    }
</style>

<header class="lapor-header">
    <div class="container">
        <div class="lapor-header__eyebrow">Profil Resmi</div>
        <h1 class="lapor-header__title display-3">Tentang Kami</h1>
    </div>
</header>

<section class="py-5 bg-light" style="font-family: 'Inter', sans-serif;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10"> {{-- Wide description space --}}
                <div class="profile-card p-4 p-md-5">
                    
                    <div class="profile-logo-container">
                        <img src="{{ asset('jakarta-csirt-logo.png') }}" alt="Logo JakartaProv-CSIRT" style="max-height: 80px;">
                    </div>

                    {{-- Deskripsi 1:1 Original --}}
                    <div class="mb-5">
                        <p class="fs-5 lh-lg text-dark mb-4">
                            Tim Tanggap Insiden Siber (Computer Security Incident Response Team) Pemerintah Provinsi DKI Jakarta yang selanjutnya disebut dengan <span class="fw-bold">JakartaProv-CSIRT</span> merupakan CSIRT Pemprov DKI Jakarta.
                        </p>
                        
                        <p class="fs-5 lh-lg text-secondary mb-4">
                            Tim JakartaProv-CSIRT ditetapkan oleh Sekretaris Daerah Provinsi DKI Jakarta dalam Keputusan Penjabat Sekretaris Daerah Provinsi DKI Jakarta Nomor 41 Tahun 2020 tentang Computer Security Incident Response Team Pemerintah Provinsi DKI Jakarta.
                        </p>

                        <p class="fs-5 lh-lg text-secondary">
                            Ketua JakartaProv-CSIRT adalah Kepala Dinas Komunikasi, Informatika dan Statistik Provinsi DKI Jakarta. Anggota Tim JakartaProv-CSIRT adalah staf fungsional dan/atau pengelola teknologi informasi di lingkungan Dinas Komunikasi, Informatika dan Statistik Provinsi DKI Jakarta.
                        </p>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <h5 class="section-title mb-3">Layanan Reaktif</h5>
                            <p class="text-secondary small">Layanan yang terkait dengan kebutuhan melakukan respon terhadap insiden siber termasuk penangkalan, penindakan dan pemulihan siber.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="section-title mb-3">Layanan Proaktif</h5>
                            <p class="text-secondary small">Layanan yang mendeteksi dan mencegah serangan siber sebelum ada dampak nyata.</p>
                        </div>
                    </div>

                    <div class="pt-4 border-top">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-square-fill me-2 text-primary"></i>
                            JakartaProv-CSIRT secara resmi di-launching pada <strong>23 Desember 2020</strong>. Konstituen JakartaProv-CSIRT meliputi seluruh Perangkat Daerah (OPD) di lingkungan Pemerintah Daerah Provinsi DKI Jakarta.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-footer text-center">
    <div class="container">
        <h2 class="text-white mb-4 display-4" style="font-family: 'Barlow Condensed', sans-serif; font-weight: 900; text-transform: uppercase;">Butuh Bantuan Teknis?</h2>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('incidents.create.step1') }}" class="btn btn-navy-bold btn-barlow fs-5">
                Lapor Insiden
            </a>
            <a href="{{ route('contact.create') }}" class="btn btn-outline-light btn-barlow fs-5">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>

@endsection