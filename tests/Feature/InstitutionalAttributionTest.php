<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstitutionalAttributionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Verify that the navbar contains the authoritative BSSA attribution beside Diskominfotik
     * without reintroducing the removed .nav-strip regression.
     */
    public function test_navbar_renders_authoritative_bssa_attribution_without_nav_strip(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Verify .nav-strip regression is absent
        $response->assertDontSee('nav-strip');

        // Verify BSSA logo asset and institutional labeling exist beside Diskominfotik logo
        $response->assertSee('bssa-logo.jpeg');
        $response->assertSee('BSSA — Bidang Siber, Sandi dan Aplikasi');
        $response->assertSee('Diskominfotik DKI Jakarta — Bidang Siber, Sandi dan Aplikasi (BSSA)');

        // Verify existing essential navbar structures remain intact
        $response->assertSee('Beranda');
        $response->assertSee('Profil');
        $response->assertSee('Publikasi');
        $response->assertSee('Lacak Status Tiket');
        $response->assertSee('nav-toggle');
        $response->assertSee('nav-collapse');
    }

    /**
     * Verify that the profile page uses the unified canonical BSSA naming.
     */
    public function test_profile_renders_canonical_bssa_attribution(): void
    {
        $response = $this->get(route('profile'));

        $response->assertStatus(200);

        // Verify canonical naming with 'dan Aplikasi'
        $response->assertSee('Bidang Siber, Sandi dan Aplikasi (BSSA)');

        // Verify the old incomplete wording is no longer present
        $response->assertDontSee('Bidang Siber dan Sandi (BSSA)');
    }

    /**
     * Verify that the footer across public pages includes the canonical BSSA attribution.
     */
    public function test_footer_renders_canonical_bssa_attribution(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // Verify canonical naming in footer contact section
        $response->assertSee('Bidang Siber, Sandi dan Aplikasi (BSSA) Diskominfotik DKI Jakarta');
    }
}
