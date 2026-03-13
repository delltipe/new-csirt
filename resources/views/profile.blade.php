@extends('layouts.app')

@section('content')
<section class="py-5 text-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white;">
    <div class="container py-4">
        <div class="bg-white d-inline-block p-4 rounded-4 shadow-lg mb-4">
            <img src="{{ asset('images/jakarta-csirt-logo.png') }}" alt="Jakarta Prov CSIRT" style="height: 120px; width: auto;">
        </div>
        <h1 class="display-4 fw-bold mb-0">Tentang Kami</h1>
        <p class="lead opacity-75 mt-2">Profil Resmi JakartaProv-CSIRT</p>
    </div>
</section>

<section class="py-5 bg-body-tertiary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    
                    <div class="mb-5">
                        <p class="fs-5 lh-lg text-secondary">
                            Tim Tanggap Insiden Siber (Computer Security Incident Response Team) Pemerintah Provinsi DKI Jakarta yang selanjutnya disebut dengan <span class="fw-bold text-dark">JakartaProv-CSIRT</span> merupakan CSIRT Pemprov DKI Jakarta.
                        </p>
                        <p class="fs-5 lh-lg text-secondary">
                            Tim JakartaProv-CSIRT ditetapkan oleh Sekretaris Daerah Provinsi DKI Jakarta dalam Keputusan Penjabat Sekretaris Daerah DKI Jakarta Nomor: <span class="badge bg-secondary-subtle text-secondary-emphasis">41 Tahun 2020</span> Tentang Computer Security Incident Response Team.
                        </p>
                    </div>

                    <div class="p-4 bg-light rounded-4 mb-5 border-start border-4 border-primary">
                        <h4 class="fw-bold mb-3">Struktur Pengelola</h4>
                        <p class="mb-0 text-secondary">
                            Kepala Dinas Komunikasi Informatika dan Statistik Provinsi DKI Jakarta ditunjuk sebagai <strong>Ketua CSIRT Propinsi DKI Jakarta</strong> dan ditugaskan untuk melaksanakan memimpin, mengkoordinasikan, memfasilitasi pengembangan kemampuan SDM, pengalokasian sumber daya, memantau, serta melaporkan pelaksanaan terkait JakartaProv-CSIRT.
                        </p>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-12">
                            <h4 class="fw-bold mb-4 text-primary"><i class="bi bi-target"></i> Tujuan Pembentukan</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="h-100 p-4 border rounded-4 bg-white shadow-sm">
                                <div class="icon-box mb-3 text-primary fs-3"><i class="bi bi-shield-check"></i></div>
                                <p class="small text-secondary mb-0">Membangun mengkoordinasikan, mengkolaborasikan dan mengoperasionalkan sistem mitigasi, manajemen krisis, penanggulangan dan pemulihan terhadap insiden keamanan siber.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="h-100 p-4 border rounded-4 bg-white shadow-sm">
                                <div class="icon-box mb-3 text-primary fs-3"><i class="bi bi-people"></i></div>
                                <p class="small text-secondary mb-0">Membangun kapasitas sumber daya penanggulangan dan pemulihan insiden keamanan siber pada sektor Pemerintah Daerah Provinsi DKI Jakarta.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h4 class="fw-bold mb-4"><i class="bi bi-gear-wide-connected"></i> Layanan Kami</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-3 bg-transparent">
                                <h6 class="fw-bold"><i class="bi bi-lightning-charge text-warning me-2"></i> Layanan Reaktif</h6>
                                <span class="text-secondary">Layanan terkait dengan kebutuhan melakukan respon terhadap insiden siber termasuk penangkalan, penindakan dan pemulihan siber.</span>
                            </li>
                            <li class="list-group-item px-0 py-3 bg-transparent">
                                <h6 class="fw-bold"><i class="bi bi-eye text-info me-2"></i> Layanan Proaktif</h6>
                                <span class="text-secondary">Layanan yang mendeteksi dan mencegah serangan siber sebelum ada dampak nyata.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="text-center pt-4 border-top">
                        <p class="text-muted small">
                            <i class="bi bi-rocket-takeoff"></i> JakartaProv-CSIRT secara resmi di-launching pada <strong>23 Desember 2020</strong>. <br>
                            Konstituen meliputi seluruh Perangkat Daerah (OPD) di lingkungan Pemerintah Daerah Provinsi DKI Jakarta.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background: #101823;">
    <div class="container text-center">
        <h3 class="text-white fw-bold mb-4">Butuh Bantuan Teknis?</h3>
        <a href="{{ route('contact') }}" class="btn btn-outline-light px-4 py-2 fw-bold">Hubungi Kami</a>
    </div>
</section>
@endsection