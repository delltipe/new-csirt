<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Create hero-linked news in berita_siber so hero tautan points to internal /news/{id}, not legacy csirt.jakarta.go.id
        $news115Id = DB::table('berita_siber')->insertGetId([
            'title' => 'Penipuan Hacker untuk Menjalankan Perintah PowerShell Berbahaya di Windows',
            'description' => "Pada tanggal 12 Februari 2025, Microsoft Threat Intelligence mengungkapkan strategi serangan siber baru yang digunakan oleh kelompok peretas yang didukung negara asal Korea Utara, Emerald Sleet, yang juga dikenal sebagai Kimsuky atau VELVET CHOLLIMA.\n\nStrategi Serangan:\n- Teknik Rekayasa Sosial: Kelompok ini memanfaatkan teknik rekayasa sosial untuk menipu pengguna agar menjalankan perintah PowerShell berbahaya dengan hak akses administratif.\n- Impersonasi Pejabat: Penyerang berpura-pura menjadi pejabat pemerintah Korea Selatan untuk membangun kepercayaan.\n- Kampanye Spear-Phishing: Setelah membangun hubungan, mereka meluncurkan kampanye spear-phishing berisi lampiran PDF yang mengarahkan korban untuk mengklik URL registasi perangkat.\n- Eksekusi Kode Berbahaya: Link memberikan instruksi rinci mendorong pengguna membuka PowerShell sebagai administrator dan mengeksekusi kode.\n\nRantai Serangan:\nSetelah kode PowerShell dieksekusi, ia mengunduh alat tambahan dari server jarak jauh, termasuk aplikasi desktop jarak jauh berbasis browser dan file sertifikat dengan PIN yang telah ditentukan, kemudian mendaftarkan perangkat korban dengan server penyerang memberikan akses jarak jauh untuk eksfiltrasi data dan spionase.\n\nPerubahan Taktik:\nTaktik ini menandai pergeseran dari metode tradisional yang mengandalkan backdoor PebbleDash/RDP Wrapper, mengeksploitasi kesalahan manusia untuk melewati keamanan konvensional.\n\nKonteks Lebih Luas:\nPowerShell sebagai alat administratif sah menjadi kendaraan aktivitas berbahaya karena integrasi mendalam dengan Windows.\n\nRekomendasi Mitigasi Microsoft:\n- Implementasikan solusi anti-phishing canggih\n- Mendidik karyawan mengidentifikasi phishing\n- Membatasi akses administratif PowerShell dan menerapkan aturan pengurangan permukaan serangan\n\nKesimpulan: Pentingnya kewaspadaan terhadap serangan rekayasa sosial yang mengeksploitasi kerentanan teknis dan psikologi manusia.",
            'thumbnail' => 'https://csirt.jakarta.go.id/images/berita/openart-image_F2OqW3Ag_1739431791382_raw_20250213073038.jpg',
            'source' => 'https://cyberpress.org/hackers-trick-users-into-running-malicious-powershell-commands/',
            'date' => '2025-02-13 14:30:38',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $news112Id = DB::table('berita_siber')->insertGetId([
            'title' => 'Ako Ransomware Memanfaatkan Windows APIs untuk Menargetkan Sistem Anda',
            'description' => "Ako ransomware, varian RaaS berbasis C++, muncul pada tahun 2020 dan menggunakan teknik seperti isolasi mesin yang ditargetkan serta manuver evasif untuk mengenkripsi sistem korban.\n\nBeroperasi di bawah model RaaS, Ako memungkinkan afiliasi untuk menyebarkannya dengan imbalan pembayaran tebusan.\n\nProses Serangan:\nGrafik serangan menggambarkan proses infeksi multi-tahap, di mana akses awal dicapai melalui eksploitasi kerentanan RDP atau kelemahan gerbang keamanan email. Setelah pijakan terjalin, pergerakan lateral terjadi melalui penyalahgunaan alat administratif sah seperti PsExec dan WMI. Ransomware kemudian mengenkripsi file penting termasuk share jaringan, sambil menonaktifkan layanan keamanan dan menghapus salinan bayangan untuk menghalangi pemulihan.\n\nTeknik yang Digunakan:\n- Ingress Tool Transfer: Mengunduh kode berbahaya melewati kontrol keamanan\n- Process Injection: Menjalankan kode dalam memori proses sah\n- System Location Discovery: Menggunakan API Windows GetSystemDefaultLCID, GetLocaleInfoA, GetUser DefaultLocaleName untuk mengumpulkan info bahasa sistem\n\nDampak Serangan:\nPenyerang menghapus salinan bayangan volume via vssadmin.exe/wmic.exe, memodifikasi registri untuk akses drive jaringan, melakukan rekognisi via GetAdaptersInfo/IcmpSendEcho, menemukan drive/file via GetLogicalDriveStringsW/FindFirstFileW, lalu mengenkripsi file target menggunakan kombinasi RSA dan AES-256 CBC.\n\nLangkah Pencegahan:\nPrioritaskan deteksi utilitas asli PowerShell yang mengunduh payload berbahaya, identifikasi proses terkompromi dengan memantau perilaku tidak biasa, terapkan pencegahan perilaku di endpoint, deteksi penghapusan Salinan Bayangan Volume via analisis baris perintah, gunakan strategi cadangan data dan manajemen akun istimewa.",
            'thumbnail' => 'https://csirt.jakarta.go.id/images/berita/69cb4bc7-6711-4968-8a20-6f88622ae577_20250114012411.jpeg',
            'source' => 'https://cyberpress.org/ako-ransomware-exploits-windows-apis/',
            'date' => '2025-01-14 08:24:11',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $slides = [
            [
                'judul' => 'JAKARTAPROVCSIRT',
                'subjudul' => 'Pemerintah Provinsi DKI Jakarta — Computer Security Incident Response Team. Menjaga infrastruktur digital dan data kritis Jakarta dari ancaman siber, 24 jam sehari, 7 hari seminggu.',
                'gambar' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1920&q=80',
                'tautan' => null,
                'teks_tautan' => 'LAPOR INSIDEN SEKARANG',
                'urutan' => 0,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Scraped from https://csirt.jakarta.go.id/index.php slideshow (2026-09-01) — excludes LAPOR banner
            [
                'judul' => 'SEMUA TENTANG WEB FILTERING',
                'subjudul' => 'Mengapa kita harus menginstall antivirus dan memahami web filtering untuk keamanan jaringan Pemprov DKI Jakarta.',
                'gambar' => 'https://csirt.jakarta.go.id/images/banner/Mengapa%20kita%20harus%20menginstall%20Anti%20virus.psd(1)_20250625015529.jpg',
                'tautan' => '/',
                'teks_tautan' => 'KE BERANDA',
                'urutan' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'judul' => 'PENIPUAN HACKER UNTUK MENJALANKAN PERINTAH POWERSHELL BERBAHAYA DI WINDOWS',
                'subjudul' => 'Microsoft ungkap taktik Emerald Sleet/Kimsuky: rekayasa sosial dan spear-phishing menipu pengguna menjalankan PowerShell administratif.',
                'gambar' => 'https://csirt.jakarta.go.id/images/banner/openart-image_F2OqW3Ag_1739431791382_raw_20250213073428.jpg',
                'tautan' => '/news/' . $news115Id,
                'teks_tautan' => 'BACA SELENGKAPNYA',
                'urutan' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'judul' => 'AKO RANSOMWARE MEMANFAATKAN WINDOWS APIS UNTUK MENARGETKAN SISTEM ANDA',
                'subjudul' => 'Waspada ransomware Ako yang mengeksploitasi Windows APIs — kenali rantai serangan dan langkah mitigasi.',
                'gambar' => 'https://csirt.jakarta.go.id/images/banner/69cb4bc7-6711-4968-8a20-6f88622ae577_20250114012745.jpeg',
                'tautan' => '/news/' . $news112Id,
                'teks_tautan' => 'BACA SELENGKAPNYA',
                'urutan' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('slide_hero')->insert($slides);
    }
}
