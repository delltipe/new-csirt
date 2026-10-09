<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\WarningPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelectiveDesignPolishTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Complaint #2: Redundant hero eyebrows removed from warnings, guides, and events,
     * while contextually meaningful eyebrows in other views are preserved.
     */
    public function test_redundant_eyebrows_removed_and_meaningful_eyebrows_retained(): void
    {
        // 1. Warnings page should not have "Alert & Notifikasi" eyebrow
        $warningsResp = $this->get(route('warnings.index'));
        $warningsResp->assertStatus(200);
        $warningsResp->assertDontSee('Alert & Notifikasi');
        $warningsResp->assertDontSee('<div class="warnings-header__eyebrow">', false);
        $warningsResp->assertSee('<h1>Peringatan Keamanan</h1>', false);

        // 2. Guides page should not have "Panduan & Sumber Daya" eyebrow
        $guidesResp = $this->get(route('guides.index'));
        $guidesResp->assertStatus(200);
        $guidesResp->assertDontSee('Panduan & Sumber Daya');
        $guidesResp->assertDontSee('<div class="guides-header__eyebrow">', false);
        $guidesResp->assertSee('<h1>Panduan Teknis Keamanan Siber</h1>', false);

        // 3. Events page should not have generic "JakartaProv-CSIRT" eyebrow
        $eventsResp = $this->get(route('events.index'));
        $eventsResp->assertStatus(200);
        $eventsResp->assertDontSee('<div class="events-header__eyebrow">', false);
        $eventsResp->assertSee('<h1>Event &amp; Webinar</h1>', false);

        // 4. Retained eyebrows should remain intact
        $lawsResp = $this->get(route('laws.index'));
        $lawsResp->assertStatus(200);
        $lawsResp->assertSee('laws-header__eyebrow');
        $lawsResp->assertSee('Publikasi');

        $profileResp = $this->get(route('profile'));
        $profileResp->assertStatus(200);
        $profileResp->assertSee('Profil Resmi');

        $rfcResp = $this->get(route('rfc2350'));
        $rfcResp->assertStatus(200);
        $rfcResp->assertSee('Dokumen Resmi');
    }

    /**
     * Test Complaint #3: Warning red is recalibrated to brand navy on routine interactions,
     * while legitimate security alert severity badges are preserved.
     */
    public function test_warning_red_recalibrated_to_navy_while_alert_badge_preserved(): void
    {
        $warning = WarningPost::create([
            'title' => 'Peringatan Zero-Day Vulnerability',
            'description' => 'Eksploitasi zero-day ditemukan pada komponen server.',
            'thumbnail' => 'https://example.com/thumb.jpg',
            'date' => now(),
            'source' => 'https://example.com/advisory',
        ]);

        // Warning index card: routine CTA and hover states use navy tokens
        $indexResp = $this->get(route('warnings.index'));
        $indexResp->assertStatus(200);
        $indexResp->assertSee('.warning-card:hover {', false);
        $indexResp->assertSee('border-color: var(--navy);', false);
        $indexResp->assertDontSee('.warning-card:hover { border-color: var(--alert);', false);
        $indexResp->assertSee('.warning-card__cta {', false);
        $indexResp->assertSee('color: var(--navy);', false);
        $indexResp->assertSee('color: var(--navy-mid);', false);

        // Warning detail view:
        $detailResp = $this->get(route('warnings.show', $warning->id));
        $detailResp->assertStatus(200);
        
        // 1. Legitimate threat alert classification is preserved with var(--alert)
        $detailResp->assertSee('background: var(--alert);', false);
        $detailResp->assertSee('Alert Keamanan');

        // 2. Routine CTA button uses brand navy
        $detailResp->assertSee('.warning-detail-cta {', false);
        $detailResp->assertSee('background: var(--navy);', false);
        $detailResp->assertSee('background: var(--navy-mid);', false);
        $detailResp->assertDontSee('.warning-detail-cta { background: var(--alert);', false);
    }

    /**
     * Test Complaint #18: Homepage carousel CTA is contextual — secondary "Tentang Kami"
     * renders only on institutional introduction slide, not on specific announcement slides.
     */
    public function test_carousel_cta_is_contextual_to_slide_type(): void
    {
        // Create an institutional introduction slide (urutan 0, tautan null)
        HeroSlide::create([
            'judul' => 'JAKARTAPROVCSIRT',
            'subjudul' => 'Tim Tanggap Insiden Siber Resmi DKI Jakarta',
            'tautan' => null,
            'teks_tautan' => 'LAPOR INSIDEN SEKARANG',
            'urutan' => 0,
            'is_active' => true,
        ]);

        // Create an announcement slide (urutan 1, specific news link)
        HeroSlide::create([
            'judul' => 'WASPADA RANSOMWARE BARU',
            'subjudul' => 'Varian ransomware baru menargetkan sistem pemerintahan',
            'tautan' => '/news/99',
            'teks_tautan' => 'BACA SELENGKAPNYA',
            'urutan' => 1,
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Primary actions are present on both slides
        $response->assertSee('LAPOR INSIDEN SEKARANG');
        $response->assertSee('BACA SELENGKAPNYA');

        // "Tentang Kami" should only appear once (on the intro slide, not duplicated on the announcement slide)
        $content = $response->getContent();
        $tentangKamiCount = substr_count($content, 'Tentang Kami <i class="bi bi-arrow-right"');
        $this->assertEquals(1, $tentangKamiCount, "Secondary 'Tentang Kami' CTA should only render on the introductory slide");
    }
}
