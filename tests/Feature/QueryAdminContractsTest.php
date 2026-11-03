<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\CybersecurityGuide;
use App\Models\CybersecurityNews;
use App\Models\Event;
use App\Models\IncidentReport;
use App\Models\User;
use App\Models\WarningPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryAdminContractsTest extends TestCase
{
    use RefreshDatabase;

    private function news(string $title, string $date): void
    {
        CybersecurityNews::create(['title' => $title, 'description' => 'Description', 'thumbnail' => 'image.png', 'source' => 'source', 'date' => $date]);
    }

    public function test_news_filters_combine_and_keep_query_on_pagination(): void
    {
        for ($i = 1; $i <= 7; $i++) $this->news("Target {$i}", '2024-02-10');
        $this->news('Target wrong date', '2023-02-10');
        $this->news('Other title', '2024-02-10');

        $response = $this->get('/news?q=Target&day=10&month=2&year=2024');
        $response->assertOk()->assertViewHas('news', fn ($news) => $news->total() === 7 && $news->count() === 6);
        $response->assertSee('q=Target&amp;day=10&amp;month=2&amp;year=2024&amp;page=2', false);
        $this->get('/news?q=Target&day=10&month=2&year=2024&page=2')
            ->assertOk()->assertViewHas('news', fn ($news) => $news->total() === 7 && $news->count() === 1);
        $this->get('/news?month=2&year=2023')->assertViewHas('news', fn ($news) => $news->total() === 1);
        $this->get('/news?q=Missing')->assertViewHas('news', fn ($news) => $news->total() === 0);
        $this->from('/news')->get('/news?day=31&month=2&year=2024')->assertRedirect('/news')->assertSessionHasErrors('day');
        $this->from('/news')->get('/news?month=13')->assertRedirect('/news')->assertSessionHasErrors('month');
        $this->get('/news')->assertSee('Filter berita tidak valid');
    }

    public function test_warnings_are_ordered_by_actual_date(): void
    {
        foreach (['2022-01-01', '2024-01-01', '2023-01-01'] as $date) {
            WarningPost::create(['title' => $date, 'description' => 'Description', 'thumbnail' => 'image.png', 'source' => 'source', 'date' => $date]);
        }
        $this->get('/warnings')->assertOk()->assertViewHas('warnings', fn ($items) => $items->pluck('title')->all() === ['2024-01-01', '2023-01-01', '2022-01-01']);
    }

    public function test_guide_author_filter_survives_pagination(): void
    {
        for ($i = 0; $i < 13; $i++) CybersecurityGuide::create(['title' => "Guide {$i}", 'author' => 'Alice', 'link' => 'https://example.com']);
        CybersecurityGuide::create(['title' => 'Other guide', 'author' => 'Bob', 'link' => 'https://example.com']);
        $this->get('/guides?author=Alice')->assertOk()->assertViewHas('guides', fn ($items) => $items->total() === 13)->assertSee('author=Alice&amp;page=2', false);
        $this->get('/guides?author=Alice&page=2')->assertViewHas('guides', fn ($items) => $items->total() === 13 && $items->count() === 1);
    }

    public function test_removed_list_urls_offer_a_way_home_and_keep_write_routes(): void
    {
        $this->markTestSkipped('Production admin routes are disabled in the public preview.');
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        foreach (['news', 'events', 'infographics', 'warnings', 'laws', 'guides'] as $tab) {
            $this->get('/admin/'.$tab)->assertStatus(405)->assertSee('Kembali ke Beranda')->assertSee('href="'.route('home').'"', false);
        }
        $this->get('/halaman-tidak-ada')->assertNotFound()->assertSee('Kembali ke Beranda')->assertSee('href="'.route('home').'"', false);
        $this->assertNotNull(\Illuminate\Support\Facades\Route::getRoutes()->getByName('admin.news.store'));
    }

    public function test_dashboard_pages_are_independent_and_links_keep_tab_and_other_pages(): void
    {
        $this->markTestSkipped('Production admin routes are disabled in the public preview.');
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        for ($i = 1; $i <= 17; $i++) {
            $this->news("News {$i}", "2024-01-".str_pad((string) $i, 2, '0', STR_PAD_LEFT));
            Event::create(['title' => "Event {$i}", 'event_date' => "2024-01-".str_pad((string) $i, 2, '0', STR_PAD_LEFT)]);
        }
        CybersecurityGuide::create(['title' => 'Short list', 'author' => 'Alice', 'link' => 'https://example.com']);
        $this->get('/admin?news_page=2')->assertOk()
            ->assertViewHas('news', fn ($p) => $p->currentPage() === 2 && $p->count() === 2)
            ->assertViewHas('events', fn ($p) => $p->currentPage() === 1 && $p->count() === 15 && str_contains($p->url(2), 'news_page=2') && str_ends_with($p->url(2), 'events_page=2#events'))
            ->assertViewHas('guides', fn ($p) => $p->currentPage() === 1 && $p->count() === 1);
        $this->get('/admin?news_page=2&events_page=2')->assertOk()
            ->assertViewHas('events', fn ($p) => $p->currentPage() === 2 && $p->count() === 2)
            ->assertViewHas('guides', fn ($p) => $p->count() === 1);
    }

    public function test_status_filters_survive_dedicated_admin_list_pagination(): void
    {
        $this->markTestSkipped('Production admin routes are disabled in the public preview.');
        $admin = User::factory()->create(['is_admin' => true]);
        $reporter = User::factory()->create();
        $this->actingAs($admin);
        for ($i = 1; $i <= 16; $i++) {
            ContactMessage::create(['name' => 'Tester', 'email' => 'test@example.com', 'subject' => "Message {$i}", 'message' => 'Message', 'status' => 'pending']);
            IncidentReport::create(['user_id' => $reporter->id, 'tiket_no' => sprintf('INS-2024-%04d', $i), 'kategori_insiden' => 'Phishing', 'lokasi_url' => 'https://example.com', 'deskripsi' => 'Description', 'tindakan_teknis' => 'Action', 'status' => IncidentReport::STATUS_PENDING]);
        }
        ContactMessage::create(['name' => 'Other', 'email' => 'other@example.com', 'subject' => 'Other', 'message' => 'Message', 'status' => 'selesai']);
        IncidentReport::create(['user_id' => $reporter->id, 'tiket_no' => 'INS-2024-0017', 'kategori_insiden' => 'Phishing', 'lokasi_url' => 'https://example.com', 'deskripsi' => 'Description', 'tindakan_teknis' => 'Action', 'status' => IncidentReport::STATUS_REJECTED]);
        $this->get('/admin/contacts?status=pending')->assertOk()->assertViewHas('contacts', fn ($p) => $p->total() === 16 && str_contains($p->url(2), 'status=pending'));
        $this->get('/admin/contacts?status=pending&page=2')->assertViewHas('contacts', fn ($p) => $p->total() === 16 && $p->count() === 1);
        $this->get('/admin/incidents?status='.IncidentReport::STATUS_PENDING)->assertOk()->assertViewHas('incidents', fn ($p) => $p->total() === 16 && str_contains($p->url(2), 'status='.IncidentReport::STATUS_PENDING));
        $this->get('/admin/incidents?status='.IncidentReport::STATUS_PENDING.'&page=2')->assertViewHas('incidents', fn ($p) => $p->total() === 16 && $p->count() === 1);
    }
}
