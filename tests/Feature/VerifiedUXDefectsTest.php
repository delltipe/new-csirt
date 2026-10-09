<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerifiedUXDefectsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Complaint #6: Event hero thumbnail is restored and visible in responsive layout.
     */
    public function test_event_hero_thumbnail_is_rendered_and_responsive(): void
    {
        $event = Event::create([
            'title' => 'Simulasi Tanggap Insiden Siber 2026',
            'description' => 'Simulasi kesiapsiagaan menghadapi ancaman siber.',
            'event_date' => now()->addDays(5),
            'location' => 'Balai Kota DKI Jakarta',
            'event_type' => 'workshop',
            'thumbnail' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80',
        ]);

        $response = $this->get(route('events.show', $event->id));

        $response->assertStatus(200);
        $response->assertSee('event-hero__thumb-wrap');
        $response->assertSee('event-hero__thumb');
        // Ensure display: none is not applied to thumb-wrap in responsive CSS
        $response->assertDontSee('.event-hero__thumb-wrap { display: none; }');
        $response->assertSee('.event-hero__thumb-wrap { display: block;');
    }

    /**
     * Test Complaint #5: RFC 2350 sidebar list has corrected indentation.
     */
    public function test_rfc2350_sidebar_list_has_corrected_indentation(): void
    {
        $response = $this->get(route('rfc2350'));

        $response->assertStatus(200);
        $response->assertSee('.rfc-sidebar ol { list-style: decimal; padding-left: 18px; margin: 0; }', false);
        $response->assertDontSee('.rfc-sidebar ol { list-style: decimal; margin-left: 20px; }', false);
    }

    /**
     * Test Complaint #12: Navbar and footer Public Key links include download affordance.
     */
    public function test_public_key_links_include_download_affordance(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        // Both navbar and footer should indicate the download affordance
        $response->assertSee('aria-label="Public Key (Unduh ASC)"', false);
        $response->assertSee('bi-download');
    }

    /**
     * Test Complaint #1: BSSA logo asset is optimized and <= 50 KB.
     */
    public function test_bssa_logo_asset_is_optimized_under_50kb(): void
    {
        $path = public_path('bssa-logo.png');
        $this->assertFileExists($path);

        $size = filesize($path);
        // Ensure payload is under 50KB (down from 1.10 MB)
        $this->assertLessThan(50 * 1024, $size, "BSSA logo should be under 50KB, actual: {$size} bytes");

        // Verify dimensions are 136x136
        $imageSize = getimagesize($path);
        $this->assertEquals(136, $imageSize[0]);
        $this->assertEquals(136, $imageSize[1]);
    }
}
