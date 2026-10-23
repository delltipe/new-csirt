<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SafeContactFormDemoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PublicPreviewSeeder::class);
    }

    public function test_contact_page_renders_with_demonstration_warning(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();

        // 1. Clear demonstration warning presence and requirements (Section 4.A)
        $response->assertSee('Mode Simulasi Pratinjau Publik');
        $response->assertSee('Halaman formulir ini beroperasi dalam');
        $response->assertSee('mode simulasi');
        $response->assertSee('Tidak ada pesan yang dikirim atau disimpan');
        $response->assertSee('Wajib gunakan informasi fiktif');
        $response->assertSee('Dilarang memasukkan data sensitif');
    }

    public function test_contact_page_contains_interactive_review_modal_and_safe_elements(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();

        // 2. Review step modal and dialog semantics (Section 4.C)
        $response->assertSee('id="demoReviewModal"', false);
        $response->assertSee('aria-labelledby="demoReviewModalLabel"', false);
        $response->assertSee('Pratinjau Pesan Kontak');
        $response->assertSee('Ini adalah pratinjau lokal dari formulir yang Anda isi.');
        $response->assertSee('id="btnConfirmDemo"', false);
        $response->assertSee('Konfirmasi Simulasi');
        $response->assertSee('Ubah Data');

        // Review table value placeholders
        $response->assertSee('id="reviewName"', false);
        $response->assertSee('id="reviewEmail"', false);
        $response->assertSee('id="reviewPhone"', false);
        $response->assertSee('id="reviewOrganization"', false);
        $response->assertSee('id="reviewInquiryType"', false);
        $response->assertSee('id="reviewSubject"', false);
        $response->assertSee('id="reviewMessage"', false);

        // Verification disclaimer — explains limitation without faking server verification
        $response->assertSee('Verifikasi matematika sisi server tidak dievaluasi dalam simulasi demo ini.');
    }

    public function test_contact_page_contains_simulated_completion_state_template(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();

        // 3. Simulated completion text (Section 4.D)
        $response->assertSee('Simulasi berhasil. Form berhasil melewati validasi yang tersedia dalam mode demo. Pesan Anda tidak dikirim atau disimpan.');
        $response->assertSee('id="demoSuccessAlert"', false);
    }

    public function test_noscript_warning_is_rendered(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();

        // 4. JavaScript-disabled protection
        $response->assertSee('<noscript>', false);
        $response->assertSee('JavaScript dinonaktifkan');
        $response->assertSee('Pengiriman data ke server dinonaktifkan pada pratinjau publik ini.');
    }

    public function test_backend_post_restriction_remains_strictly_enforced(): void
    {
        $initialCount = DB::table('contact_us')->count();
        $this->assertEquals(0, $initialCount);

        Storage::fake('public');

        $payload = [
            'name' => 'Fictional Visitor',
            'email' => 'visitor@example.com',
            'phone' => '081234567890',
            'organization' => 'Simulated Org',
            'inquiry_type' => 'general',
            'subject' => 'Pertanyaan Simulasi',
            'message' => 'Ini adalah pesan demonstrasi.',
            'captcha_answer' => 15,
        ];

        // Direct HTTP POST attempt
        $response = $this->from('/contact')->post('/contact', $payload);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors(['pesan' => 'Pengiriman pesan kontak dinonaktifkan pada versi pratinjau publik.']);

        // Database must remain completely unmutated
        $this->assertEquals(0, DB::table('contact_us')->count());

        // File storage must remain empty
        $this->assertEmpty(Storage::disk('public')->allFiles());
    }

    public function test_server_rejection_error_is_displayed_on_contact_page_when_submitted_directly(): void
    {
        // When a POST request is made directly (as when JavaScript is disabled),
        // following the redirect renders the server error alert on the contact page.
        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Fictional Visitor',
            'email' => 'visitor@example.com',
            'pesan' => 'Direct post test',
        ]);

        $response->assertRedirect('/contact');

        $followed = $this->followRedirects($response);
        $followed->assertOk();
        $followed->assertSee('Pengiriman pesan kontak dinonaktifkan pada versi pratinjau publik.');
        $followed->assertSee('demo-server-error');
    }

    public function test_no_analytics_events_capture_contact_form_inputs_or_contents(): void
    {
        config(['services.goatcounter.endpoint' => 'https://jakarta-csirt-preview.goatcounter.com/count']);

        $response = $this->get('/contact');
        $response->assertOk();
        $content = $response->getContent();

        // Ensure no goatcounter click or event attribute is attached to contact form elements
        $this->assertDoesNotMatchRegularExpression(
            '/<form[^>]*id="contactForm"[^>]*data-goatcounter/',
            $content
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<input[^>]*name="(name|email|phone|organization|subject|captcha_answer)"[^>]*data-goatcounter/',
            $content
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<textarea[^>]*name="message"[^>]*data-goatcounter/',
            $content
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<button[^>]*id="btnSubmitContact"[^>]*data-goatcounter/',
            $content
        );
    }
}
