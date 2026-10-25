<?php

namespace Tests\Feature;

use App\Models\CybersecurityGuide;
use App\Models\HeroSlide;
use App\Models\LawRulePost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class GoatCounterAnalyticsPreviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PublicPreviewSeeder::class);
    }

    public function test_analytics_is_completely_disabled_when_endpoint_is_unset(): void
    {
        config(['services.goatcounter.endpoint' => null]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('gc.zgo.at/count.js');
        $response->assertDontSee('window.goatcounter');
        $response->assertDontSee('footer-privacy-note');
    }

    public function test_analytics_scripts_and_sanitizers_render_when_endpoint_is_configured(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('https://gc.zgo.at/count.js', false);
        $response->assertSee('data-goatcounter="https://jakarta-csirt-preview.goatcounter.com/count"', false);
        $response->assertSee('window.goatcounter = {', false);
        $response->assertSee('excludedPrefixes', false);
        $response->assertSee('document.referrer', false);
        $response->assertSee('search-submit', false);
        $response->assertSee('footer-privacy-note', false);
        $response->assertSee('Privasi Pratinjau', false);
    }

    public function test_page_events_and_navigation_links_render_expected_attributes(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        $homeResponse = $this->get('/');
        $homeResponse->assertOk();

        // Allowed public feature links on Home are instrumented
        $homeResponse->assertSee('data-goatcounter-click="home-hero-profile-link-click"', false);
        $homeResponse->assertSee('data-goatcounter-click="home-alert-warning-link-click"', false);
        $homeResponse->assertSee('data-goatcounter-click="nav-profile-click"', false);
        $homeResponse->assertSee('data-goatcounter-click="nav-rfc2350-click"', false);
        $homeResponse->assertSee('data-goatcounter-click="nav-statistics-click"', false);
        $homeResponse->assertSee('data-goatcounter-click="download-publickey-link-click"', false);
        $homeResponse->assertSee('data-goatcounter-click="nav-partner-jayaraya-click"', false);

        // Ticket tracking is strictly excluded from analytics
        $homeResponse->assertDontSee('nav-ticket-track-click');

        // Document downloads on RFC 2350
        $rfcResponse = $this->get('/rfc2350');
        $rfcResponse->assertOk();
        $rfcResponse->assertSee('data-goatcounter-click="download-rfc2350-pdf-link-click"', false);
        $rfcResponse->assertSee('data-goatcounter-click="download-publickey-link-click"', false);
    }

    public function test_hero_cta_pointing_to_incident_reporting_is_not_instrumented(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        // In seeded slides, slide 0 has empty tautan which defaults to bug-hunter.dashboard
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();

        // Ensure the incident-reporting CTA in slide 0 does not have tracking attributes
        $content = $homeResponse->getContent();
        $this->assertMatchesRegularExpression(
            '/<a href="[^"]*bug-hunter[^"]*" class="btn-hero-primary"(?![^>]*data-goatcounter-click)/',
            $content
        );

        // Verify fallback static hero (when no slides exist) also leaves incident CTA uninstrumented
        HeroSlide::query()->delete();
        $staticHomeResponse = $this->get('/');
        $staticHomeResponse->assertOk();
        $staticContent = $staticHomeResponse->getContent();

        $this->assertMatchesRegularExpression(
            '/<a href="[^"]*bug-hunter[^"]*" class="btn-hero-primary"(?![^>]*data-goatcounter-click)/',
            $staticContent
        );
        $staticHomeResponse->assertSee('data-goatcounter-click="home-hero-profile-link-click"', false);
    }

    public function test_dynamic_hero_cta_to_public_feature_is_instrumented(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        // Create a custom slide pointing to an allowed public route
        HeroSlide::create([
            'judul' => 'Slide Berita Publik',
            'subjudul' => 'Informasi keamanan publik',
            'gambar' => 'hero.jpg',
            'tautan' => '/news/1',
            'teks_tautan' => 'BACA BERITA',
            'urutan' => 0,
            'is_active' => true,
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('data-goatcounter-click="home-hero-primary-link-click"', false);
        $response->assertSee('data-goatcounter-title="Aksi Utama Hero Slider"', false);
    }

    public function test_external_resource_links_are_not_classified_as_downloads(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        // Law 1 in seeded data has external link only (file_path is null)
        $lawResponse = $this->get('/laws/1');
        $lawResponse->assertOk();
        $lawResponse->assertSee('data-goatcounter-click="external-law-resource-link-click"', false);
        $lawResponse->assertDontSee('data-goatcounter-click="download-law-link-click"');

        // Guide 1 in seeded data has external link only (file_path is null)
        $guideResponse = $this->get('/guides/1');
        $guideResponse->assertOk();
        $guideResponse->assertSee('data-goatcounter-click="external-guide-resource-link-click"', false);
        $guideResponse->assertDontSee('data-goatcounter-click="download-guide-link-click"');
    }

    public function test_actual_file_downloads_use_download_event_names(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        // Update an existing law with a local file_path
        $localLaw = LawRulePost::firstOrFail();
        $localLaw->file_path = 'peraturan/peraturan-lokal.pdf';
        $localLaw->save();

        $lawResponse = $this->get('/laws/' . $localLaw->id);
        $lawResponse->assertOk();
        $lawResponse->assertSee('data-goatcounter-click="download-law-link-click"', false);
        $lawResponse->assertDontSee('data-goatcounter-click="external-law-resource-link-click"');

        // Update an existing guide with a local file_path
        $localGuide = CybersecurityGuide::firstOrFail();
        $localGuide->file_path = 'panduan/hardening.pdf';
        $localGuide->save();

        $guideResponse = $this->get('/guides/' . $localGuide->id);
        $guideResponse->assertOk();
        $guideResponse->assertSee('data-goatcounter-click="download-guide-link-click"', false);
        $guideResponse->assertDontSee('data-goatcounter-click="external-guide-resource-link-click"');
    }

    public function test_excluded_routes_never_render_analytics_scripts(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        $routesToVerify = [
            '/login' => 403,
            '/register' => 403,
            '/bug-hunter' => 302,
            '/bug-hunter/laporan' => 302,
            '/bug-hunter/laporan/baru' => 200,
            '/bug-hunter/laporan/selesai' => 200,
            '/lacak-laporan' => 403,
            '/admin/login' => 404,
            '/admin/dashboard' => 404,
        ];

        foreach ($routesToVerify as $route => $expectedStatus) {
            $response = $this->get($route);
            $response->assertStatus($expectedStatus);
            $response->assertDontSee('gc.zgo.at/count.js');
            $response->assertDontSee('window.goatcounter');
            $response->assertDontSee('footer-privacy-note');
        }
    }

    public function test_path_and_referrer_callback_behavior_executed_in_javascript(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        $response = $this->get('/');
        $response->assertOk();
        $html = $response->getContent();

        // Extract window.goatcounter definition from actual rendered HTML
        $this->assertMatchesRegularExpression('/window\.goatcounter\s*=\s*\{[\s\S]*?\n\s*\};/', $html);
        preg_match('/window\.goatcounter\s*=\s*(\{[\s\S]*?\n\s*\});/', $html, $matches);
        $goatcounterObjJs = $matches[1];

        $nodeHarness = <<<JS
const window = {
    location: { pathname: '/default' }
};
const document = {
    referrer: ''
};

window.goatcounter = {$goatcounterObjJs};

const testCases = {
    clean_guide_query: window.goatcounter.path('/guides/1?secret=value'),
    clean_search_query: window.goatcounter.path('/search?q=sensitive+term'),
    clean_hash: window.goatcounter.path('/news/2?token=abc#section'),
    event_guide_download: window.goatcounter.path('download-guide-link-click'),
    event_guide_external: window.goatcounter.path('external-guide-resource-link-click'),
    event_search_submit: window.goatcounter.path('search-submit'),
    event_nav_profile: window.goatcounter.path('nav-profile-click'),
    event_hero_cta: window.goatcounter.path('home-hero-primary-link-click'),
    excluded_login: window.goatcounter.path('/login'),
    excluded_login_query: window.goatcounter.path('/login?redirect=admin'),
    excluded_register: window.goatcounter.path('/register'),
    excluded_logout: window.goatcounter.path('/logout'),
    excluded_bug_hunter: window.goatcounter.path('/bug-hunter'),
    excluded_bug_hunter_sub: window.goatcounter.path('/bug-hunter/laporan/baru'),
    excluded_lacak_laporan: window.goatcounter.path('/lacak-laporan?ticket=123'),
    excluded_admin: window.goatcounter.path('/admin'),
    excluded_admin_dashboard: window.goatcounter.path('/admin/dashboard'),
    default_page_path: (() => {
        window.location.pathname = '/statistics';
        return window.goatcounter.path();
    })(),
    default_excluded_path: (() => {
        window.location.pathname = '/admin';
        return window.goatcounter.path();
    })(),
    referrer_with_query: (() => {
        document.referrer = 'https://search.example.com/search?q=sensitive+password&id=1';
        return window.goatcounter.referrer();
    })(),
    referrer_with_path: (() => {
        document.referrer = 'https://internal.portal.gov.id/confidential/page';
        return window.goatcounter.referrer();
    })(),
    referrer_empty: (() => {
        document.referrer = '';
        return window.goatcounter.referrer();
    })()
};

console.log(JSON.stringify(testCases));
JS;

        $nodeBinary = (new ExecutableFinder())->find('node');
        if ($nodeBinary === null) {
            $this->markTestSkipped('Node.js executable is not available in the test environment.');
        }

        $process = new Process([$nodeBinary]);
        $process->setInput($nodeHarness);
        $process->mustRun();

        $results = json_decode($process->getOutput(), true);
        $this->assertIsArray($results);

        // 1. Verify query strings and hashes are stripped from page URLs
        $this->assertSame('/guides/1', $results['clean_guide_query']);
        $this->assertSame('/search', $results['clean_search_query']);
        $this->assertSame('/news/2', $results['clean_hash']);

        // 2. Verify click events and search-submit retain their custom names intact
        $this->assertSame('download-guide-link-click', $results['event_guide_download']);
        $this->assertSame('external-guide-resource-link-click', $results['event_guide_external']);
        $this->assertSame('search-submit', $results['event_search_submit']);
        $this->assertSame('nav-profile-click', $results['event_nav_profile']);
        $this->assertSame('home-hero-primary-link-click', $results['event_hero_cta']);

        // 3. Verify excluded paths return null to cancel tracking
        $this->assertNull($results['excluded_login']);
        $this->assertNull($results['excluded_login_query']);
        $this->assertNull($results['excluded_register']);
        $this->assertNull($results['excluded_logout']);
        $this->assertNull($results['excluded_bug_hunter']);
        $this->assertNull($results['excluded_bug_hunter_sub']);
        $this->assertNull($results['excluded_lacak_laporan']);
        $this->assertNull($results['excluded_admin']);
        $this->assertNull($results['excluded_admin_dashboard']);

        // 4. Verify default path behavior
        $this->assertSame('/statistics', $results['default_page_path']);
        $this->assertNull($results['default_excluded_path']);

        // 5. Verify referrer origin sanitization
        $this->assertSame('https://search.example.com', $results['referrer_with_query']);
        $this->assertSame('https://internal.portal.gov.id', $results['referrer_with_path']);
        $this->assertSame('', $results['referrer_empty']);
    }

    public function test_search_submit_tracking_intercepts_form_without_collecting_terms(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        $response = $this->get('/');
        $response->assertOk();
        $html = $response->getContent();

        // Search submit listener must attach to search forms
        $this->assertStringContainsString('document.querySelectorAll(\'form[action*="/search"], form.nav-search\')', $html);
        $this->assertStringContainsString("form.addEventListener('submit'", $html);
        $this->assertStringContainsString("path: 'search-submit'", $html);
        $this->assertStringContainsString("title: 'Pencarian Diajukan'", $html);
        $this->assertStringContainsString("event: true", $html);

        // Verify the search submit handler does not access input values or search query terms
        $searchSubmitScript = substr($html, strpos($html, 'Intercept all search forms for search-submit event'));
        $searchSubmitScript = substr($searchSubmitScript, 0, strpos($searchSubmitScript, '</script>'));

        $this->assertDoesNotMatchRegularExpression('/\.value\b/', $searchSubmitScript);
        $this->assertDoesNotMatchRegularExpression('/\bFormData\b/', $searchSubmitScript);
        $this->assertDoesNotMatchRegularExpression('/\blocation\.search\b/', $searchSubmitScript);
        $this->assertDoesNotMatchRegularExpression('/[\'"]q[\'"]/', $searchSubmitScript);
    }
}
