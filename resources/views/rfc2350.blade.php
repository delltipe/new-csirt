@extends('layouts.app')

@section('content')
<style>
    .rfc-page-header {
        background: var(--ink);
        padding: 52px 0 44px;
        position: relative;
    }

    .rfc-page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: repeating-linear-gradient(90deg, var(--ink) 0, var(--ink) 79px, var(--navy-dim) 80px);
        opacity: 0.28;
        pointer-events: none;
    }

    .rfc-page-header .container,
    .rfc-layout { position: relative; z-index: 1; }

    .rfc-eyebrow {
        color: var(--muted-on-dark);
        font-family: var(--font-display);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.12em;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .rfc-page-header h1 {
        color: var(--white);
        font-family: var(--font-display);
        font-size: clamp(32px, 5vw, 54px);
        font-weight: 800;
        letter-spacing: 0.02em;
        line-height: 1;
        margin: 0;
        text-transform: uppercase;
    }

    .rfc-page-header p {
        color: var(--muted-on-dark);
        margin: 12px 0 0;
        max-width: 680px;
    }

    .rfc-shell {
        background: var(--mist);
        padding: 48px 0 72px;
    }

    .rfc-layout {
        display: grid;
        gap: 28px;
        grid-template-columns: minmax(0, 1fr) 270px;
    }

    .rfc-document,
    .rfc-sidebar__card {
        background: var(--white);
        border: 1px solid var(--border);
    }

    .rfc-document { padding: 40px; }

    .rfc-document h2 {
        border-top: 2px solid var(--ink);
        color: var(--ink);
        font-family: var(--font-display);
        font-size: 24px;
        font-weight: 800;
        line-height: 1.25;
        margin: 48px 0 20px;
        padding-top: 18px;
    }

    .rfc-document section:first-child h2 { margin-top: 0; }

    .rfc-document h3 {
        color: var(--navy);
        font-family: var(--font-display);
        font-size: 17px;
        font-weight: 700;
        line-height: 1.35;
        margin: 30px 0 10px;
    }

    .rfc-document p,
    .rfc-document li {
        color: var(--ink);
        line-height: 1.75;
    }

    .rfc-document p + p { margin-top: 14px; }

    .rfc-document ol,
    .rfc-document ul {
        list-style: revert;
        margin: 12px 0 0 24px;
    }

    .rfc-document li + li { margin-top: 8px; }

    .rfc-meta {
        border-left: 4px solid var(--navy);
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin-bottom: 34px;
        padding: 20px;
        background: var(--navy-tint);
    }

    .rfc-meta__label {
        color: var(--mid);
        display: block;
        font-family: var(--font-display);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        margin-bottom: 3px;
        text-transform: uppercase;
    }

    .rfc-meta__value { color: var(--ink); font-weight: 600; }

    .rfc-key {
        background: var(--mist);
        border: 1px solid var(--border);
        margin-top: 18px;
        padding: 20px;
    }

    .rfc-key code {
        color: var(--ink);
        display: block;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .rfc-sidebar { align-self: start; display: grid; gap: 16px; position: sticky; top: 20px; }
    .rfc-sidebar__card { padding: 22px; }

    .rfc-sidebar h2 {
        color: var(--ink);
        font-family: var(--font-display);
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.08em;
        margin-bottom: 14px;
        text-transform: uppercase;
    }

    .rfc-sidebar ol { list-style: decimal; margin-left: 20px; }
    .rfc-sidebar li + li { margin-top: 8px; }
    .rfc-sidebar a { color: var(--navy); font-size: 13px; line-height: 1.4; }
    .rfc-sidebar a:hover { text-decoration: underline; }

    .rfc-sidebar a.rfc-download {
        align-items: center;
        background: var(--navy);
        color: var(--white);
        display: inline-flex;
        font-family: var(--font-display);
        font-size: 13px;
        font-weight: 700;
        gap: 8px;
        justify-content: center;
        letter-spacing: 0.04em;
        padding: 12px 16px;
        text-transform: uppercase;
        transition: background var(--ease);
        width: 100%;
    }

    .rfc-sidebar a.rfc-download:hover {
        background: var(--navy-mid);
        color: var(--white);
        text-decoration: none;
    }
    .rfc-sidebar__note { color: var(--mid); font-size: 13px; line-height: 1.6; margin-top: 12px; }

    @media (max-width: 960px) {
        .rfc-layout { grid-template-columns: 1fr; }
        .rfc-sidebar { position: static; }
    }

    @media (max-width: 640px) {
        .rfc-shell { padding: 28px 0 48px; }
        .rfc-document { padding: 24px; }
        .rfc-meta { grid-template-columns: 1fr; }
    }
</style>

<header class="rfc-page-header">
    <div class="container">
        <p class="rfc-eyebrow">Dokumen Resmi</p>
        <h1>RFC 2350</h1>
        <p>Informasi layanan, tanggung jawab, dan kontak JakartaProv-CSIRT.</p>
    </div>
</header>

<div class="rfc-shell">
    <div class="container rfc-layout">
        <article class="rfc-document" aria-labelledby="rfc-document-title">
            <h2 id="rfc-document-title" class="sr-only">RFC 2350 JakartaProv-CSIRT</h2>
            <div class="rfc-meta" aria-label="Identifikasi dokumen">
                <div><span class="rfc-meta__label">Judul</span><span class="rfc-meta__value">RFC 2350 JakartaProv-CSIRT</span></div>
                <div><span class="rfc-meta__label">Versi</span><span class="rfc-meta__value">1.2</span></div>
                <div><span class="rfc-meta__label">Tanggal Publikasi</span><span class="rfc-meta__value">30 September 2025</span></div>
                <div><span class="rfc-meta__label">Status</span><span class="rfc-meta__value">Berlaku hingga dokumen terbaru dipublikasikan</span></div>
            </div>

            <section id="informasi-dokumen">
                <h2>1. Informasi Mengenai Dokumen</h2>
                <p>Dokumen ini berisi deskripsi JakartaProv-CSIRT berdasarkan RFC 2350, yaitu informasi dasar mengenai JakartaProv-CSIRT, tanggung jawab, layanan yang diberikan, dan cara menghubungi JakartaProv-CSIRT.</p>
                <h3>1.1. Tanggal Update Terakhir</h3>
                <p>Dokumen versi 1.2 ini diterbitkan pada 30 September 2025.</p>
                <h3>1.2. Daftar Distribusi untuk Pemberitahuan</h3>
                <p>Tidak ada daftar distribusi untuk pemberitahuan mengenai pembaruan dokumen.</p>
                <h3>1.3. Lokasi Dokumen</h3>
                <p>Dokumen tersedia pada halaman RFC 2350 JakartaProv-CSIRT.</p>
                <h3>1.4. Keaslian Dokumen</h3>
                <p>Dokumen telah ditandatangani dengan sertifikat elektronik milik Dinas Komunikasi, Informatika dan Statistik.</p>
            </section>

            <section id="kontak">
                <h2>2. Informasi Data dan Kontak</h2>
                <h3>2.1. Nama Tim</h3>
                <p>Tim Tanggap Insiden Siber (Computer Security Incident Response Team) Provinsi DKI Jakarta, disingkat JakartaProv-CSIRT.</p>
                <h3>2.2. Alamat</h3>
                <p>Dinas Komunikasi, Informatika dan Statistik Provinsi DKI Jakarta<br>Jalan Medan Merdeka Selatan No. 8-9, Jakarta Pusat 10110<br>DKI Jakarta, Indonesia</p>
                <h3>2.3. Zona Waktu</h3>
                <p>Jakarta (GMT+07:00).</p>
                <h3>2.4. Nomor Telepon dan Fax</h3>
                <p>(021) 3823253</p>
                <h3>2.5. Alamat Surat Elektronik</h3>
                <p><a href="mailto:csirt@jakarta.go.id">csirt@jakarta.go.id</a></p>
                <h3>2.6. Kunci Publik dan Informasi Enkripsi</h3>
                <div class="rfc-key">
                    <p><strong>Bits:</strong> 4096<br><strong>ID:</strong> 0x7B1A4B82D1C4F8A4</p>
                    <code>57FA 4DD3 DD3D 165A DC7B 1A73 7B1A 4B82 D1C4 F8A4</code>
                </div>
                <h3>2.7. Anggota Tim</h3>
                <p>Pengarah JakartaProv-CSIRT adalah Sekretaris Daerah Provinsi DKI Jakarta. Penanggung Jawab adalah Asisten Pemerintahan Sekda Provinsi DKI Jakarta. Ketua adalah Kepala Dinas Komunikasi, Informatika dan Statistik Provinsi DKI Jakarta, dan Sekretaris adalah Sekretaris Dinas Komunikasi, Informatika dan Statistik Provinsi DKI Jakarta.</p>
                <p>Anggota tim terdiri dari unsur Bidang, Unit Pelaksana, dan Suku Dinas pada Komunikasi, Informatika dan Statistik Provinsi DKI Jakarta serta pengelola teknologi informasi pada Perangkat Daerah di lingkungan Pemerintah Provinsi DKI Jakarta.</p>
                <h3>2.8. Catatan Kontak</h3>
                <p>Metode yang disarankan untuk menghubungi JakartaProv-CSIRT adalah melalui email atau telepon pada hari kerja, pukul 07.30-16.00 Senin-Kamis dan 07.30-16.30 Jumat.</p>
            </section>

            <section id="tentang-csirt">
                <h2>3. Mengenai JakartaProv-CSIRT</h2>
                <h3>3.1. Visi</h3>
                <p>Terwujudnya sistem keamanan informasi yang aman dan terpercaya di lingkungan Pemerintah Provinsi DKI Jakarta.</p>
                <h3>3.2. Misi</h3>
                <ol type="a">
                    <li>Mengkoordinasikan penanganan insiden keamanan siber di lingkungan Pemerintah Provinsi DKI Jakarta.</li>
                    <li>Menjadi pusat pelaporan serta penanganan insiden keamanan informasi di lingkungan Pemerintah Provinsi DKI Jakarta.</li>
                </ol>
                <h3>3.3. Konstituen</h3>
                <p>Konstituen JakartaProv-CSIRT meliputi Perangkat Daerah penyelenggara Sistem Elektronik di lingkungan Pemerintah Provinsi DKI Jakarta.</p>
                <h3>3.4. Sponsorship dan Afiliasi</h3>
                <p>Pendanaan JakartaProv-CSIRT bersumber dari Anggaran Pendapatan dan Belanja Daerah (APBD).</p>
                <h3>3.5. Otoritas</h3>
                <p>Sesuai Peraturan Gubernur Provinsi DKI Jakarta Nomor 15 Tahun 2025 tentang Pelaksanaan Persandian untuk Pengamanan Informasi, JakartaProv-CSIRT berwenang mengoordinasikan pengamanan sistem elektronik sebelum, saat, dan setelah insiden siber.</p>
                <p>JakartaProv-CSIRT melakukan penanggulangan dan pemulihan atas permintaan konstituennya, serta dapat berkoordinasi dengan BSSN, akademisi, tenaga ahli keamanan, dan pihak lain untuk insiden yang tidak dapat ditangani secara mandiri.</p>
            </section>

            <section id="kebijakan">
                <h2>4. Kebijakan</h2>
                <h3>4.1. Jenis Insiden dan Tingkat Dukungan</h3>
                <p>JakartaProv-CSIRT melayani penanganan insiden siber berikut:</p>
                <ul>
                    <li>Web defacement</li>
                    <li>DDOS (Distributed Denial of Service)</li>
                    <li>Malware</li>
                    <li>Phishing</li>
                    <li>Spamming</li>
                    <li>Network incident</li>
                </ul>
                <p>Dukungan yang diberikan kepada konstituen dapat bervariasi menurut jenis, dampak insiden, dan layanan yang digunakan.</p>
                <h3>4.2. Kerja Sama, Interaksi, dan Pengungkapan Informasi</h3>
                <p>JakartaProv-CSIRT melakukan kerja sama dan berbagi informasi dengan BSSN sebagai Gov-CSIRT, TTIS, atau organisasi lain dalam lingkup keamanan siber. Seluruh informasi yang diterima akan dirahasiakan.</p>
                <h3>4.3. Komunikasi dan Autentikasi</h3>
                <p>Komunikasi biasa dapat menggunakan email konvensional, telepon, atau fax. Informasi yang sensitif, terbatas, atau rahasia dapat menggunakan enkripsi PGP melalui email.</p>
            </section>

            <section id="layanan">
                <h2>5. Layanan dan Fungsi</h2>
                <h3>5.1. Penanggulangan dan Pemulihan Insiden Siber</h3>
                <p>JakartaProv-CSIRT melakukan kegiatan berikut dalam penanggulangan dan pemulihan insiden:</p>
                <ol type="a">
                    <li>Deteksi insiden untuk menemukan aktivitas mencurigakan atau tanda insiden keamanan siber.</li>
                    <li>Analisis insiden untuk memahami asal usul, dampak, dan metode serangan.</li>
                    <li>Mitigasi dan penanggulangan untuk mengurangi dampak serta menghentikan serangan.</li>
                    <li>Pemulihan untuk membantu organisasi mengembalikan operasi normal.</li>
                    <li>Pengumpulan eviden untuk menyelidiki asal usul insiden dan mengumpulkan bukti bila diperlukan.</li>
                    <li>Rekomendasi pencegahan untuk mencegah insiden serupa.</li>
                </ol>
                <h3>5.2. Penyampaian Informasi Insiden Siber</h3>
                <p>Pemberian informasi dan peringatan yang relevan mengenai potensi ancaman atau kerentanan membantu pemilik sistem elektronik merespons ancaman dengan cepat dan efektif.</p>
                <h3>5.3. Diseminasi Informasi</h3>
                <p>Diseminasi informasi membantu penyelenggara sistem elektronik menyusun kebijakan dan tindakan yang sesuai untuk mengurangi risiko serta merespons insiden siber.</p>
                <h3>5.4. Fungsi Utama</h3>
                <ol>
                    <li>Pemberian peringatan terkait keamanan siber.</li>
                    <li>Perumusan panduan teknis penanganan insiden siber.</li>
                    <li>Penerimaan dan pencatatan laporan atau aduan, serta rekomendasi langkah penanganan awal.</li>
                    <li>Pemilahan atau triage insiden sesuai kriteria untuk menentukan prioritas penanganan.</li>
                    <li>Penyelenggaraan koordinasi penanganan insiden kepada pihak berkepentingan.</li>
                    <li>Diseminasi informasi untuk mencegah atau mengurangi dampak insiden siber.</li>
                </ol>
            </section>

            <section id="pelaporan">
                <h2>6. Pelaporan Insiden</h2>
                <p>Laporan insiden keamanan siber dapat dikirimkan melalui website JakartaProv-CSIRT atau email csirt@jakarta.go.id dengan melampirkan sekurang-kurangnya:</p>
                <ol type="a">
                    <li>Kontak narahubung.</li>
                    <li>Bukti insiden berupa foto, tangkapan layar, atau berkas log yang ditemukan.</li>
                    <li>Informasi lain sesuai ketentuan yang berlaku.</li>
                </ol>
            </section>

            <section id="disclaimer">
                <h2>7. Disclaimer</h2>
                <ol type="a">
                    <li>JakartaProv-CSIRT hanya merespons dan menangani insiden siber pada sistem elektronik yang dikelola Perangkat Daerah di lingkungan Pemerintah Provinsi DKI Jakarta.</li>
                    <li>Penanganan insiden bergantung pada ketersediaan sumber daya.</li>
                    <li>JakartaProv-CSIRT tidak bertanggung jawab atas kesalahan, kelalaian, atau kerusakan akibat penggunaan informasi dalam dokumen ini.</li>
                    <li>JakartaProv-CSIRT tidak memiliki wewenang dalam persoalan hukum.</li>
                </ol>
            </section>
        </article>

        <aside class="rfc-sidebar" aria-label="Navigasi dokumen RFC 2350">
            <div class="rfc-sidebar__card">
                <h2>Daftar Isi</h2>
                <ol>
                    <li><a href="#informasi-dokumen">Informasi Dokumen</a></li>
                    <li><a href="#kontak">Data dan Kontak</a></li>
                    <li><a href="#tentang-csirt">Tentang CSIRT</a></li>
                    <li><a href="#kebijakan">Kebijakan</a></li>
                    <li><a href="#layanan">Layanan dan Fungsi</a></li>
                    <li><a href="#pelaporan">Pelaporan Insiden</a></li>
                    <li><a href="#disclaimer">Disclaimer</a></li>
                </ol>
            </div>
            <div class="rfc-sidebar__card">
                <h2>Dokumen Asli</h2>
                <a class="rfc-download" href="{{ asset('documents/rfc-2350-jakartaprov-csirt-v1.2.pdf') }}" download>
                    <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> Unduh PDF
                </a>
                <a class="rfc-download" href="{{ route('publickey') }}" style="margin-top: 10px;">
                    <i class="bi bi-key" aria-hidden="true"></i> Unduh Public Key
                </a>
                <p class="rfc-sidebar__note">PDF bertanda tangan tersedia sebagai dokumen resmi yang menjadi rujukan halaman ini.</p>
            </div>
        </aside>
    </div>
</div>
@endsection
