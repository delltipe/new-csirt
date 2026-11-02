<?php

namespace Tests\Feature;

use App\Models\CybersecurityGuide;
use App\Models\Infographic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecawPortalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the SecAw landing page renders successfully with HTTP 200
     * and contains the authoritative provincial context and module status badges.
     */
    public function test_secaw_landing_page_renders_successfully(): void
    {
        $response = $this->get(route('secaw.index'));

        $response->assertStatus(200);

        // Core headings and context
        $response->assertSee('Security Awareness (SecAw)');
        $response->assertSee('Edukasi & Kesadaran Siber', false);
        $response->assertSee('Provinsi DKI Jakarta');

        // Assessment preview and status indication
        $response->assertSee('Self Assessment Kesadaran Keamanan Siber');
        $response->assertSee('Fase 3 — Tahap Pengembangan');
        $response->assertSee('26');
        $response->assertSee('Indikator Pertanyaan');

        // Operational modules (Guides and Infographics)
        $response->assertSee('Panduan Teknis & SOP', false);
        $response->assertSee(route('guides.index'));
        $response->assertSee('Infografis Edukasi');
        $response->assertSee(route('infographics.index'));

        // Upcoming Phase 2 modules
        $response->assertSee('Video & Sosialisasi', false);
        $response->assertSee('Tools Keamanan Siber');

        // Official BSSA Diskominfotik DKI Jakarta escalations
        $response->assertSee('csirt@jakarta.go.id');
        $response->assertSee('0813-8887-0152');

        // Ensure legacy municipal/branch branding is not leaked
        $response->assertDontSee('Kota Administrasi Jakarta Barat');
        $response->assertDontSee('Call Center BATIK');
    }

    /**
     * Test that the navbar includes the SecAw entry inside the Publikasi dropdown.
     */
    public function test_navbar_contains_secaw_link(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(route('secaw.index'));
        $response->assertSee('Security Awareness (SecAw)');
    }

    /**
     * Test that the footer includes the SecAw link in its navigation links.
     */
    public function test_footer_contains_secaw_link(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(route('secaw.index'));
    }

    /**
     * Test that the SecAw page uses the shared application layout,
     * including navbar, accessibility widget, and footer.
     */
    public function test_secaw_page_uses_shared_layout(): void
    {
        $response = $this->get(route('secaw.index'));

        $response->assertStatus(200);
        $response->assertSee('nav-main');
        $response->assertSee('accessibility.js');
        $response->assertSee('accessibility-contrast.css');
        $response->assertSee('site-footer');
    }

    /**
     * Test that the SecAw analytics dashboard renders all eight required chart cards
     * with explicit empty states, no-data badges, and data integrity disclaimers.
     */
    public function test_secaw_analytics_dashboard_renders_all_eight_chart_cards_with_empty_states(): void
    {
        $response = $this->get(route('secaw.index'));

        $response->assertStatus(200);

        // Section header and data integrity banner
        $response->assertSee('Dasbor Analitik Kesadaran Siber');
        $response->assertSee('Transparansi Data Asesmen');
        $response->assertSee('Fase 4 — Integrasi Data');

        // Chart 1: Monthly cybersecurity awareness score
        $response->assertSee('Tren Skor Kesadaran Siber Bulanan');
        $response->assertSee('Periode bulan (Januari–Desember)');

        // Chart 2: Assessment-result distribution (Authoritative predicates: Baik Sekali, Baik, Kurang Baik, Buruk)
        $response->assertSee('Distribusi Predikat Hasil Asesmen');
        $response->assertSee('Baik Sekali');
        $response->assertSee('Buruk');
        $response->assertDontSee('85–100%');

        // Chart 3: Scores by category (16 technical + 10 social questions verified from reference)
        $response->assertSee('Skor Kesadaran Berdasarkan Kategori');
        $response->assertSee('Pilar Siber Teknis (16 Butir Pertanyaan)');
        $response->assertSee('Pilar Siber Sosial (10 Butir Pertanyaan)');

        // Chart 4: Respondent distribution by gender (Authoritative labels: Pria, Wanita)
        $response->assertSee('Distribusi Responden Berdasarkan Gender');
        $response->assertSee('Pria dan Wanita');

        // Chart 5: Respondent distribution by education (9 official tiers: SD, SMP, SMA, D1, D2, D3, D4/S1, S2, S3)
        $response->assertSee('Distribusi Responden Berdasarkan Jenjang Pendidikan');
        $response->assertSee('D4/S1');
        $response->assertSee('SMA');

        // Chart 6: Respondent distribution by age (6 official cohorts from questionnaire)
        $response->assertSee('Distribusi Responden Berdasarkan Kelompok Usia');
        $response->assertSee('15–24 Tahun');
        $response->assertSee('65 Tahun ke Atas');

        // Chart 7: Respondent distribution by domicile
        $response->assertSee('Distribusi Responden Berdasarkan Wilayah Domisili');
        $response->assertSee('Usulan Adaptasi Provinsi DKI Jakarta', false);

        // Chart 8: Awareness scores by subcategory (11 official subcategories)
        $response->assertSee('Skor Kesadaran Berdasarkan Subkategori Keamanan');
        $response->assertSee('Syarat &amp; Ketentuan Instalasi', false);
        $response->assertSee('Kata Sandi');

        // Verify explicit empty state notices are present across cards
        $response->assertSee('Belum Ada Data Terhimpun');
        $response->assertSee('Belum Ada Data Demografi');
        $response->assertSee('Menunggu Data Geografis');
    }

    /**
     * Test that the domicile chart requirements strictly cover all six administrative
     * jurisdictions of DKI Jakarta rather than municipal-only divisions.
     */
    public function test_secaw_analytics_dashboard_covers_provincial_dki_jakarta_domiciles(): void
    {
        $response = $this->get(route('secaw.index'));

        $response->assertStatus(200);

        $response->assertSee('Jakarta Pusat');
        $response->assertSee('Jakarta Utara');
        $response->assertSee('Jakarta Barat');
        $response->assertSee('Jakarta Selatan');
        $response->assertSee('Jakarta Timur');
        $response->assertSee('Kepulauan Seribu');

        // Ensure legacy municipal/kecamatan restrictions are not present
        $response->assertDontSee('Kecamatan Cengkareng');
        $response->assertDontSee('Kecamatan Grogol Petamburan');
    }

    /**
     * Test that the SecAw landing page module cards link to the newly implemented Phase 2 sections.
     */
    public function test_secaw_landing_page_module_cards_link_to_phase_2_sections(): void
    {
        $response = $this->get(route('secaw.index'));

        $response->assertStatus(200);

        // All 4 modules now have active destinations
        $response->assertSee(route('secaw.materi'));
        $response->assertSee(route('secaw.infografis'));
        $response->assertSee(route('secaw.video'));
        $response->assertSee(route('secaw.tools'));

        // All cards indicate availability
        $response->assertSee('Buka Materi');
        $response->assertSee('Galeri Infografis');
        $response->assertSee('Katalog Video');
        $response->assertSee('Direktori Tools');
    }

    /**
     * Test that the SecAw Materi module renders HTTP 200, displays existing guides,
     * and provides working text search and empty states.
     */
    public function test_secaw_materi_module_renders_and_supports_search(): void
    {
        CybersecurityGuide::create([
            'title' => 'Panduan Pengamanan Email Kedinasan ASN',
            'author' => 'Diskominfotik DKI Jakarta',
            'link' => 'https://csirt.jakarta.go.id/panduan-teknis/download?id=101',
        ]);

        CybersecurityGuide::create([
            'title' => 'Pedoman Penanganan Malware Ransomware',
            'author' => 'BSSN',
            'link' => 'https://csirt.jakarta.go.id/panduan-teknis/download?id=102',
        ]);

        // Default listing
        $response = $this->get(route('secaw.materi'));
        $response->assertStatus(200);
        $response->assertSee('Materi & Panduan Teknis', false);
        $response->assertSee('Panduan Pengamanan Email Kedinasan ASN');
        $response->assertSee('Pedoman Penanganan Malware Ransomware');
        $response->assertSee('Diskominfotik DKI Jakarta');
        $response->assertSee('https://csirt.jakarta.go.id/panduan-teknis/download?id=101');

        // Search matching one item
        $searchResponse = $this->get(route('secaw.materi', ['search' => 'Ransomware']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Pedoman Penanganan Malware Ransomware');
        $searchResponse->assertDontSee('Panduan Pengamanan Email Kedinasan ASN');

        // Search with no matches triggers sensible empty state
        $emptyResponse = $this->get(route('secaw.materi', ['search' => 'NonExistentTopicXYZ']));
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Tidak Ada Materi Ditemukan');
        $emptyResponse->assertSee('NonExistentTopicXYZ');
    }

    /**
     * Test that the SecAw Infografis module renders HTTP 200, lists existing infographics,
     * includes dialog lightbox attributes, and supports search.
     */
    public function test_secaw_infografis_module_renders_and_supports_lightbox_and_search(): void
    {
        Infographic::create([
            'title' => 'Waspada Modus Quishing Kode QR Palsu',
            'thumbnail' => 'https://csirt.jakarta.go.id/images/gallery/quishing.jpg',
        ]);

        Infographic::create([
            'title' => 'Tips Keamanan Akun Media Sosial',
            'thumbnail' => 'https://csirt.jakarta.go.id/images/gallery/medsos.jpg',
        ]);

        // Default listing
        $response = $this->get(route('secaw.infografis'));
        $response->assertStatus(200);
        $response->assertSee('Infografis Keamanan Siber');
        $response->assertSee('Waspada Modus Quishing Kode QR Palsu');
        $response->assertSee('Tips Keamanan Akun Media Sosial');
        $response->assertSee('https://csirt.jakarta.go.id/images/gallery/quishing.jpg');

        // Dialog markup present for accessible modal
        $response->assertSee('id="infographic-dialog"', false);
        $response->assertSee('aria-labelledby="dialog-title"', false);
        $response->assertSee('Perbesar Gambar');

        // Search matching
        $searchResponse = $this->get(route('secaw.infografis', ['search' => 'Quishing']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Waspada Modus Quishing Kode QR Palsu');
        $searchResponse->assertDontSee('Tips Keamanan Akun Media Sosial');

        // Empty state
        $emptyResponse = $this->get(route('secaw.infografis', ['search' => 'NoMatchingInfographic']));
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Tidak Ada Infografis Ditemukan');
    }

    /**
     * Test that the SecAw Video module renders HTTP 200, displays verified official
     * webinars with click-to-load play facade, and strictly avoids placeholder video IDs.
     */
    public function test_secaw_video_module_renders_verified_official_videos_without_placeholders(): void
    {
        $response = $this->get(route('secaw.video'));

        $response->assertStatus(200);
        $response->assertSee('Video & Sosialisasi Siber', false);

        // Verified titles and speakers
        $response->assertSee('Keluarga Aman Digital - Melindungi Ibu dan Anak dari Ancaman Siber');
        $response->assertSee('Muhammad Ismu Hadi (BSSN)');
        $response->assertSee('Penipuan Berkedok Judi Online - Jangan Jadi Korban Perangkap Digital!');
        $response->assertSee('Dark Web - Mengungkap Sisi Tersembunyi Internet &amp; Dampaknya bagi Masyarakat', false);

        // Verified real YouTube IDs and official channel links
        $response->assertSee('Ud9fc2EXxJI');
        $response->assertSee('vbzgoR9XskI');
        $response->assertSee('0fuvqzxfgPU');
        $response->assertSee('https://www.youtube.com/watch?v=Ud9fc2EXxJI');

        // Click-to-load play button and accessible labelling
        $response->assertSee('secaw-video-play-btn');
        $response->assertSee('Putar Video');

        // Attribution and provenance of Jakarta Barat educational references
        $response->assertSee('Kanal Resmi Kota Administrasi Jakarta Barat');
        $response->assertSee('Suku Dinas Kominfotik Kota Administrasi Jakarta Barat');
        $response->assertSee('bukan materi yang diproduksi secara langsung oleh CSIRT Pemerintah Provinsi DKI Jakarta');

        // Never contain Rickroll or fake placeholder IDs
        $response->assertDontSee('dQw4w9WgXcQ');

        // Search filtering on video
        $searchResponse = $this->get(route('secaw.video', ['search' => 'Judi Online']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Penipuan Berkedok Judi Online');
        $searchResponse->assertDontSee('Dark Web');

        // Empty state on zero matches
        $emptyResponse = $this->get(route('secaw.video', ['search' => 'NonExistentVideoQuery']));
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Tidak Ada Video Ditemukan');
    }

    /**
     * Test that the SecAw Tools module renders HTTP 200, displays the verified directory,
     * enforces the crucial confidentiality disclaimer, and provides category filtering.
     */
    public function test_secaw_tools_module_renders_directory_and_confidentiality_disclaimer(): void
    {
        $response = $this->get(route('secaw.tools'));

        $response->assertStatus(200);
        $response->assertSee('Tools Keamanan Siber');

        // Verified tools from reference catalogue
        $response->assertSee('Phishing Quiz by Google');
        $response->assertSee('Have I Been Pwned');
        $response->assertSee('VirusTotal');

        // Verified safe external links with noopener
        $response->assertSee('https://phishingquiz.withgoogle.com/');
        $response->assertSee('https://haveibeenpwned.com/');
        $response->assertSee('https://www.virustotal.com/');
        $response->assertSee('rel="noopener noreferrer"', false);
        $response->assertSee('target="_blank"', false);

        // Mandatory confidentiality and security warning
        $response->assertSee('Peringatan Keamanan & Kebijakan Data Rahasia Pemerintah:', false);
        $response->assertSee('Pengunjung dilarang keras mengunggah dokumen kedinasan berklasifikasi');

        // Category filtering: data breach only
        $breachResponse = $this->get(route('secaw.tools', ['kategori' => 'data-breach']));
        $breachResponse->assertStatus(200);
        $breachResponse->assertSee('Have I Been Pwned');
        $breachResponse->assertDontSee('Phishing Quiz by Google');

        // Search filtering: search VirusTotal
        $searchResponse = $this->get(route('secaw.tools', ['search' => 'VirusTotal']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('VirusTotal');
        $searchResponse->assertDontSee('Have I Been Pwned');

        // Empty state on zero matches
        $emptyResponse = $this->get(route('secaw.tools', ['search' => 'NonExistentTool']));
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Tidak Ada Perangkat Ditemukan');
    }

    /**
     * Test that subnav, breadcrumbs, and official guide PDF links exist across all SecAw pages,
     * and that subnav uses standard semantic HTML navigation without ARIA menubar roles.
     */
    public function test_secaw_navigation_and_breadcrumbs_across_all_modules(): void
    {
        $routes = [
            route('secaw.index'),
            route('secaw.materi'),
            route('secaw.infografis'),
            route('secaw.video'),
            route('secaw.tools'),
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);

            // Subnav landmark present with accessible name
            $response->assertSee('aria-label="Navigasi Modul Security Awareness"', false);

            // Subnav links present on every page
            $response->assertSee(route('secaw.index'));
            $response->assertSee(route('secaw.materi'));
            $response->assertSee(route('secaw.infografis'));
            $response->assertSee(route('secaw.video'));
            $response->assertSee(route('secaw.tools'));

            // Verified official tutorial PDF link present in subnav with explicit Jakbar provenance
            $response->assertSee('Tutorial%20Dashboard%20Sec-Aw.pdf', false);
            $response->assertSee('Tutorial Dashboard SecAw Jakbar (PDF)');
            $response->assertSee('Unduh Tutorial Dashboard SecAw Jakarta Barat (PDF Resmi)');

            // Breadcrumb navigation present
            $response->assertSee('Jejak navigasi');

            // Semantic navigation: no ARIA menubar/menuitem/none roles
            $content = $response->getContent();
            preg_match('/<nav[^>]*class="[^"]*secaw-subnav[^"]*"[^>]*>(.*?)<\/nav>/s', $content, $matches);
            $this->assertNotEmpty($matches, "SecAw subnav landmark must be present on {$url}");
            $subnavHtml = $matches[1];

            $this->assertStringNotContainsString('role="menubar"', $subnavHtml);
            $this->assertStringNotContainsString('role="menuitem"', $subnavHtml);
            $this->assertStringNotContainsString('role="none"', $subnavHtml);

            // Exactly the current route link in the subnav has aria-current="page"
            $this->assertSame(1, substr_count($subnavHtml, 'aria-current="page"'), "Exactly one link in the subnav must have aria-current=\"page\" on {$url}");
            $this->assertMatchesRegularExpression('/href="' . preg_quote($url, '/') . '"[^>]*aria-current="page"/s', $subnavHtml, "The active route {$url} must have aria-current=\"page\" on its link");
        }
    }

    /**
     * Test pagination on Materi and Infografis when records exceed page size.
     */
    public function test_secaw_materi_and_infografis_pagination(): void
    {
        // Create 10 guides (limit is 9 per page)
        for ($i = 1; $i <= 10; $i++) {
            CybersecurityGuide::create([
                'title' => "Panduan Pengamanan Siber Batch {$i}",
                'author' => "Penerbit {$i}",
                'link' => "https://csirt.jakarta.go.id/panduan-teknis/download?id={$i}",
            ]);
        }

        $responsePage1 = $this->get(route('secaw.materi'));
        $responsePage1->assertStatus(200);
        $responsePage1->assertSee('Panduan Pengamanan Siber Batch 10'); // latest

        $responsePage2 = $this->get(route('secaw.materi', ['page' => 2]));
        $responsePage2->assertStatus(200);
        $responsePage2->assertSee('Panduan Pengamanan Siber Batch 1');
    }
}
